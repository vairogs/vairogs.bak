<?php declare(strict_types = 1);

namespace Vairogs\Cache\EventListener;

use JetBrains\PhpStorm\ArrayShape;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\PropertyInfo\Type;
use Vairogs\Cache\Cache;
use Vairogs\Cache\CacheManager;
use Vairogs\Cache\Event\CacheEvent;
use Vairogs\Cache\Header;

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

    protected readonly CacheEvent $event;

    public function __construct(protected bool $enabled, Security $security, private readonly CacheManager $cacheManager)
    {
        if ($this->enabled) {
            $this->event = new CacheEvent(security: $security);
        }
    }

    #[ArrayShape([
        KernelEvents::CONTROLLER => Type::BUILTIN_TYPE_ARRAY,
        KernelEvents::RESPONSE => Type::BUILTIN_TYPE_STRING,
        KernelEvents::REQUEST => Type::BUILTIN_TYPE_STRING,
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

    public function onKernelController(ControllerEvent $controllerEvent): void
    {
        if (!$this->check(kernelEvent: $controllerEvent)) {
            return;
        }

        if (null !== ($attribute = $this->event->getAtribute(kernelEvent: $controllerEvent, class: Cache::class))) {
            /* @var Cache $attribute */
            $attribute->setData(data: $this->event->getAttributes(kernelEvent: $controllerEvent, class: Cache::class));
            $response = null;

            if (is_string(value: $route = $controllerEvent->getRequest()->get(key: self::ROUTE))) {
                $key = $attribute->getKey(prefix: $route);

                if (!$this->needsInvalidation(request: $controllerEvent->getRequest())) {
                    $response = $this->cacheManager->get(key: $key);
                } else {
                    $this->cacheManager->delete(key: $key);
                }
            }

            if (null !== $response) {
                $controllerEvent->setController(controller: static fn () => $response);
            }
        }
    }

    public function onKernelRequest(RequestEvent $requestEvent): void
    {
        if (!$this->check(kernelEvent: $requestEvent)) {
            return;
        }

        if (($attribute = $this->event->getAtribute(kernelEvent: $requestEvent, class: Cache::class)) && $this->needsInvalidation(request: $requestEvent->getRequest())) {
            /* @var Cache $attribute */
            $attribute->setData(data: $this->event->getAttributes(kernelEvent: $requestEvent, class: Cache::class));
            $this->cacheManager->delete(key: $attribute->getKey(prefix: $requestEvent->getRequest()->get(key: self::ROUTE)));
        }
    }

    public function onKernelResponse(ResponseEvent $responseEvent): void
    {
        if (!$this->check(kernelEvent: $responseEvent)) {
            return;
        }

        if ($attribute = $this->event->getAtribute(kernelEvent: $responseEvent, class: Cache::class)) {
            /* @var Cache $attribute */
            $attribute->setData(data: $this->event->getAttributes(kernelEvent: $responseEvent, class: Cache::class));
            $key = $attribute->getKey(prefix: $responseEvent->getRequest()->get(key: self::ROUTE));
            $skip = Header::SKIP === $responseEvent->getRequest()->headers->get(key: Header::CACHE_VAR);

            if (!$skip && null === $this->cacheManager->get(key: $key)) {
                $this->cacheManager->set(key: $key, value: $responseEvent->getResponse(), expiresAfter: $attribute->getExpires());
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

        return [] !== ($controller = $this->event->getController(kernelEvent: $kernelEvent)) && class_exists(class: $controller[0]);
    }

    private function needsInvalidation(Request $request): bool
    {
        if (Request::METHOD_PURGE === $request->getMethod()) {
            return true;
        }

        $invalidate = $request->headers->get(key: Header::CACHE_VAR);

        return null !== $invalidate && in_array(needle: $invalidate, haystack: self::HEADERS, strict: true);
    }
}
