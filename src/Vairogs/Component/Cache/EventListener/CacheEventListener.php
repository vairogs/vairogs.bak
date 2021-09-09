<?php declare(strict_types = 1);

namespace Vairogs\Component\Cache\EventListener;

use Doctrine\Common\Annotations\Reader;
use JetBrains\PhpStorm\ArrayShape;
use Psr\Cache\InvalidArgumentException;
use ReflectionException;
use Symfony\Component\Cache\Adapter\ChainAdapter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Vairogs\Component\Cache\Annotation\Cache as Annotation;
use Vairogs\Component\Cache\Utils\Event;
use Vairogs\Component\Cache\Utils\Header;
use Vairogs\Component\Cache\Utils\Pool;
use Vairogs\Extra\Constants\Type\Basic;
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

    protected ChainAdapter $adapter;
    protected Event $event;

    public function __construct(Reader $reader, protected bool $enabled, ?TokenStorageInterface $tokenStorage, ...$adapters)
    {
        if ($this->enabled) {
            $this->adapter = new ChainAdapter(Pool::createPool(Annotation::class, $adapters));
            $this->adapter->prune();
            $this->event = new Event($reader, $tokenStorage);
        }
    }

    #[ArrayShape([
        KernelEvents::CONTROLLER => Basic::ARRAY,
        KernelEvents::RESPONSE => Basic::STRING,
        KernelEvents::REQUEST => Basic::STRING,
    ])]
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

        if (null !== ($annotation = $this->event->getAnnotation($controllerEvent, Annotation::class))) {
            /* @var $annotation Annotation */
            $annotation->setData($this->event->getAttributes($controllerEvent, Annotation::class));
            $response = null;

            if (is_string($route = $this->getRoute($controllerEvent))) {
                $key = $annotation->getKey($route);

                if (!$this->needsInvalidation($controllerEvent->getRequest())) {
                    $response = $this->getCache($key);
                } else {
                    $this->adapter->deleteItem($key);
                }
            }

            if (null !== $response) {
                $controllerEvent->setController(static fn() => $response);
            }
        }
    }

    private function check(ControllerEvent|RequestEvent|ResponseEvent $kernelEvent): bool
    {
        if (!$this->enabled || !$kernelEvent->isMainRequest()) {
            return false;
        }

        if (method_exists($kernelEvent, 'getResponse') && !$kernelEvent->getResponse()?->isSuccessful()) {
            return false;
        }

        return !empty($controller = $this->event->getController($kernelEvent)) && class_exists($controller[0]);
    }

    private function getRoute(RequestEvent|ResponseEvent|ControllerEvent $kernelEvent): ?string
    {
        return $kernelEvent->getRequest()?->get('_route');
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
     */
    private function getCache(string $key): mixed
    {
        $cache = $this->adapter->getItem($key);

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

        if (($annotation = $this->event->getAnnotation($requestEvent, Annotation::class)) && $this->needsInvalidation($requestEvent->getRequest())) {
            $annotation->setData($this->event->getAttributes($requestEvent, Annotation::class));
            $this->adapter->deleteItem($annotation->getKey($this->getRoute($requestEvent)));
        }
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

        if (null !== ($annotation = $this->event->getAnnotation($responseEvent, Annotation::class))) {
            $annotation->setData($this->event->getAttributes($responseEvent, Annotation::class));
            $key = $annotation->getKey($this->getRoute($responseEvent));
            $skip = Header::SKIP === $responseEvent->getRequest()->headers->get(Header::CACHE_VAR);

            if (null === $this->getCache($key) && !$skip) {
                $this->setCache($key, $responseEvent->getResponse(), $annotation->getExpires());
            }
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    private function setCache(string $key, Response $value, ?int $expires): void
    {
        $cache = $this->adapter->getItem($key);
        $cache->set($value);
        $cache->expiresAfter($expires);

        $this->adapter->save($cache);
    }
}
