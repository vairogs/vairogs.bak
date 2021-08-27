<?php declare(strict_types = 1);

namespace Vairogs\Component\Cache\EventListener;

use Doctrine\Common\Annotations\Reader;
use JetBrains\PhpStorm\ArrayShape;
use Psr\Cache\InvalidArgumentException;
use ReflectionException;
use Symfony\Component\Cache\Adapter\ChainAdapter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Vairogs\Component\Cache\Annotation\Cache as Annotation;
use Vairogs\Component\Cache\Utils\Attribute;
use Vairogs\Component\Cache\Utils\Header;
use Vairogs\Component\Cache\Utils\Pool;
use function class_exists;
use function in_array;
use function is_string;
use function method_exists;

class CacheEventListener implements EventSubscriberInterface
{
    private const HEADERS = [
        Header::INVALIDATE,
        Header::SKIP,
    ];
    private const ROUTE = '_route';

    protected ChainAdapter $client;
    protected Attribute $attribute;

    public function __construct(Reader $reader, protected bool $enabled, ?TokenStorageInterface $tokenStorage, ...$adapters)
    {
        if ($this->enabled) {
            $this->client = new ChainAdapter(Pool::createPoolFor(Annotation::class, $adapters));
            $this->client->prune();
            $this->attribute = new Attribute($reader, $tokenStorage);
        }
    }

    #[ArrayShape([KernelEvents::CONTROLLER => "array", KernelEvents::RESPONSE => "string", KernelEvents::REQUEST => "string"])]
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => [
                'onKernelController',
                -100,
            ],
            KernelEvents::RESPONSE => 'onKernelResponse',
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    /**
     * @throws InvalidArgumentException
     * @throws ReflectionException
     */
    public function onKernelController(ControllerEvent $controllerEvent): void
    {
        if (!$this->check($controllerEvent)) {
            return;
        }

        if (null !== ($annotation = $this->attribute->getAnnotation($controllerEvent, Annotation::class))) {
            $annotation->setData($this->attribute->getAttributes($controllerEvent, Annotation::class));
            /* @var $annotation Annotation */
            $key = $annotation->getKey($controllerEvent->getRequest()?->get(self::ROUTE));
            $response = null;
            if (is_string($key)) {
                $response = $this->getCache($key);
            }
            if (null !== $response) {
                $controllerEvent->setController(static function () use ($response) {
                    return $response;
                });
            }
        }
    }

    private function check(KernelEvent $kernelEvent): bool
    {
        if (!$this->enabled || !$kernelEvent->isMainRequest()) {
            return false;
        }

        if (method_exists($kernelEvent, 'getResponse') && !$kernelEvent->getResponse()?->isSuccessful()) {
            return false;
        }

        return !empty($controller = $this->attribute->getController($kernelEvent)) && class_exists($controller[0]);
    }

    /**
     * @throws InvalidArgumentException
     */
    private function getCache(string $key): mixed
    {
        $cache = $this->client->getItem($key);
        if ($cache->isHit()) {
            return $cache->get();
        }

        return null;
    }

    /**
     * @throws InvalidArgumentException
     * @throws ReflectionException
     */
    public function onKernelRequest(RequestEvent $requestEvent): void
    {
        if (!$this->check($requestEvent)) {
            return;
        }

        if (($annotation = $this->attribute->getAnnotation($requestEvent, Annotation::class)) && $this->needsInvalidation($requestEvent->getRequest())) {
            $annotation->setData($this->attribute->getAttributes($requestEvent, Annotation::class));
            $key = $annotation->getKey($requestEvent->getRequest()
                ->get(self::ROUTE));
            $this->client->deleteItem($key);
        }
    }

    private function needsInvalidation(Request $request): bool
    {
        if ($request->getMethod() === Request::METHOD_PURGE) {
            return true;
        }

        $invalidate = $request->headers->get(Header::CACHE_VAR);

        return null !== $invalidate && in_array($invalidate, self::HEADERS, true);
    }

    /**
     * @throws InvalidArgumentException
     * @throws ReflectionException
     */
    public function onKernelResponse(ResponseEvent $responseEvent): void
    {
        if (!$this->check($responseEvent)) {
            return;
        }

        if (null !== ($annotation = $this->attribute->getAnnotation($responseEvent, Annotation::class))) {
            $annotation->setData($this->attribute->getAttributes($responseEvent, Annotation::class));
            $key = $annotation->getKey($responseEvent->getRequest()
                ->get(self::ROUTE));
            $cache = $this->getCache($key);
            $skip = Header::SKIP === $responseEvent->getRequest()->headers->get(Header::CACHE_VAR);
            if (null === $cache && !$skip) {
                $this->setCache($key, $responseEvent->getResponse(), $annotation->getExpires());
            }
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    private function setCache(string $key, mixed $value, ?int $expires): void
    {
        $cache = $this->client->getItem($key);
        $cache->set($value);
        $cache->expiresAfter($expires);

        $this->client->save($cache);
    }
}
