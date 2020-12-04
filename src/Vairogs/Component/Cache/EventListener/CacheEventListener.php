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
use Vairogs\Component\Cache\Utils\Adapter\Cache as Adapter;
use Vairogs\Component\Cache\Utils\Attribute;
use Vairogs\Component\Cache\Utils\Header;
use Vairogs\Component\Cache\Utils\Pool;
use function class_exists;
use function in_array;
use function method_exists;

class CacheEventListener implements EventSubscriberInterface
{
    private const HEADERS = [
        Header::INVALIDATE,
        Header::SKIP,
    ];
    private const ROUTE = '_route';

    protected ChainAdapter $client;
    protected bool $enabled;
    protected Attribute $attribute;

    /**
     * @param Reader $reader
     * @param bool $enabled
     * @param null|TokenStorageInterface $storage
     * @param Adapter[] ...$adapters
     */
    public function __construct(Reader $reader, bool $enabled, ?TokenStorageInterface $storage, array ...$adapters)
    {
        $this->enabled = $enabled;
        if ($this->enabled) {
            $this->client = new ChainAdapter(Pool::createPoolFor(Annotation::class, $adapters));
            $this->client->prune();
            $this->attribute = new Attribute($reader, $storage);
        }
    }

    /**
     * @return array
     */
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
     * @param ControllerEvent $event
     *
     * @throws InvalidArgumentException
     * @throws ReflectionException
     */
    public function onKernelController(ControllerEvent $event): void
    {
        if (!$this->check($event)) {
            return;
        }

        if ($annotation = $this->attribute->getAnnotation($event, Annotation::class)) {
            $annotation->setData($this->attribute->getAttributes($event, Annotation::class));
            /* @var $annotation Annotation */
            $response = $this->getCache($annotation->getKey($event->getRequest()
                ->get(self::ROUTE)));
            if (null !== $response) {
                $event->setController(static function () use ($response) {
                    return $response;
                });
            }
        }
    }

    /**
     * @param KernelEvent $event
     *
     * @return bool
     */
    private function check(KernelEvent $event): bool
    {
        if (!$this->enabled || !$this->client || !$event->isMasterRequest()) {
            return false;
        }

        if (method_exists($event, 'getResponse') && $event->getResponse() && !$event->getResponse()
                ->isSuccessful()) {
            return false;
        }

        if (empty($controller = $this->attribute->getController($event)) || !class_exists($controller[0])) {
            return false;
        }

        return true;
    }

    /**
     * @param string $key
     *
     * @return mixed
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
     * @param RequestEvent $event
     *
     * @throws InvalidArgumentException
     * @throws ReflectionException
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$this->check($event)) {
            return;
        }

        if (($annotation = $this->attribute->getAnnotation($event, Annotation::class)) && $this->needsInvalidation($event->getRequest())) {
            $annotation->setData($this->attribute->getAttributes($event, Annotation::class));
            $key = $annotation->getKey($event->getRequest()
                ->get(self::ROUTE));
            $this->client->deleteItem($key);
        }
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function needsInvalidation(Request $request): bool
    {
        if ($request->getMethod() === Request::METHOD_PURGE) {
            return true;
        }

        $invalidate = $request->headers->get(Header::CACHE_VAR);

        return null !== $invalidate && in_array($invalidate, self::HEADERS, true);
    }

    /**
     * @param ResponseEvent $event
     *
     * @throws InvalidArgumentException
     * @throws ReflectionException
     */
    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$this->check($event)) {
            return;
        }

        if ($annotation = $this->attribute->getAnnotation($event, Annotation::class)) {
            $annotation->setData($this->attribute->getAttributes($event, Annotation::class));
            $key = $annotation->getKey($event->getRequest()
                ->get(self::ROUTE));
            $cache = $this->getCache($key);
            $skip = Header::SKIP === $event->getRequest()->headers->get(Header::CACHE_VAR);
            if (null === $cache && !$skip) {
                $this->setCache($key, $event->getResponse(), $annotation->getExpires());
            }
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param null|int $expires
     *
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
