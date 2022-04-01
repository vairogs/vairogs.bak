<?php declare(strict_types = 1);

namespace Vairogs\Cache\EventListener;

use JetBrains\PhpStorm\ArrayShape;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\ChainAdapter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;
use Vairogs\Cache\Cache;
use Vairogs\Cache\Header;
use Vairogs\Cache\Utils\Event;
use Vairogs\Cache\Utils\Pool;
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

    public function __construct(protected bool $enabled, Security $security, ...$adapters)
    {
        if ($this->enabled) {
            $this->adapter = new ChainAdapter(adapters: Pool::createPool(class: Cache::class, adapters: $adapters));
            $this->adapter->prune();
            $this->event = new Event(security: $security);
        }
    }

    #[ArrayShape([
        KernelEvents::CONTROLLER => 'array',
        KernelEvents::RESPONSE => 'string',
        KernelEvents::REQUEST => 'string',
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
     */
    public function onKernelController(ControllerEvent $controllerEvent): void
    {
        if (!$this->check(kernelEvent: $controllerEvent)) {
            return;
        }

        /** @var Cache $attribute */
        if (null !== ($attribute = $this->event->getAtribute(kernelEvent: $controllerEvent, class: Cache::class))) {
            $attribute->setData(data: $this->event->getAttributes(kernelEvent: $controllerEvent, class: Cache::class));
            $response = null;

            if (is_string(value: $route = $this->getRoute(kernelEvent: $controllerEvent))) {
                $key = $attribute->getKey(prefix: $route);

                if (!$this->needsInvalidation(request: $controllerEvent->getRequest())) {
                    $response = $this->getCache(key: $key);
                } else {
                    $this->adapter->deleteItem(key: $key);
                }
            }

            if (null !== $response) {
                $controllerEvent->setController(controller: static fn () => $response);
            }
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    public function onKernelRequest(RequestEvent $requestEvent): void
    {
        if (!$this->check(kernelEvent: $requestEvent)) {
            return;
        }

        /** @var Cache $attribute */
        if (($attribute = $this->event->getAtribute(kernelEvent: $requestEvent, class: Cache::class)) && $this->needsInvalidation(request: $requestEvent->getRequest())) {
            $attribute->setData(data: $this->event->getAttributes(kernelEvent: $requestEvent, class: Cache::class));
            $this->adapter->deleteItem(key: $attribute->getKey(prefix: $this->getRoute(kernelEvent: $requestEvent)));
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    public function onKernelResponse(ResponseEvent $responseEvent): void
    {
        if (!$this->check(kernelEvent: $responseEvent)) {
            return;
        }

        /** @var Cache $attribute */
        if (null !== ($attribute = $this->event->getAtribute(kernelEvent: $responseEvent, class: Cache::class))) {
            $attribute->setData(data: $this->event->getAttributes(kernelEvent: $responseEvent, class: Cache::class));
            $key = $attribute->getKey(prefix: $this->getRoute(kernelEvent: $responseEvent));
            $skip = Header::SKIP === $responseEvent->getRequest()->headers->get(key: Header::CACHE_VAR);

            if (!$skip && null === $this->getCache(key: $key)) {
                $this->setCache(key: $key, value: $responseEvent->getResponse(), expires: $attribute->getExpires());
            }
        }
    }

    private function check(ControllerEvent|RequestEvent|ResponseEvent $kernelEvent): bool
    {
        if (!$this->enabled || !$kernelEvent->isMainRequest()) {
            return false;
        }

        if (method_exists(object_or_class: $kernelEvent, method: 'getResponse') && !$kernelEvent->getResponse()?->isSuccessful()) {
            return false;
        }

        return !empty($controller = $this->event->getController(kernelEvent: $kernelEvent)) && class_exists(class: $controller[0]);
    }

    private function getRoute(RequestEvent|ResponseEvent|ControllerEvent $kernelEvent): ?string
    {
        return $kernelEvent->getRequest()?->get(key: '_route');
    }

    private function needsInvalidation(Request $request): bool
    {
        if (Request::METHOD_PURGE === $request->getMethod()) {
            return true;
        }

        $invalidate = $request->headers->get(key: Header::CACHE_VAR);

        return null !== $invalidate && in_array(needle: $invalidate, haystack: self::HEADERS, strict: true);
    }

    /**
     * @throws InvalidArgumentException
     */
    private function getCache(string $key): mixed
    {
        $cache = $this->adapter->getItem(key: $key);

        if ($cache->isHit()) {
            return $cache->get();
        }

        return null;
    }

    /**
     * @throws InvalidArgumentException
     */
    private function setCache(string $key, Response $value, ?int $expires): void
    {
        $cache = $this->adapter->getItem(key: $key);
        $cache->set(value: $value);
        $cache->expiresAfter(time: $expires);

        $this->adapter->save(item: $cache);
    }
}
