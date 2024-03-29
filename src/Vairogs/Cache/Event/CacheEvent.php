<?php declare(strict_types = 1);

namespace Vairogs\Cache\Event;

use Exception;
use InvalidArgumentException;
use JsonSerializable;
use ReflectionClass;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Vairogs\Cache\Strategy;

use function end;
use function explode;
use function is_array;
use function method_exists;
use function reset;
use function sprintf;

final readonly class CacheEvent
{
    public function __construct(private Security $security)
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getAttributes(KernelEvent $kernelEvent, string $class): array
    {
        $params = '_route_params';

        if (null !== ($attribute = $this->getAttribute(kernelEvent: $kernelEvent, class: $class))) {
            $request = $kernelEvent->getRequest();

            $user = $this->getUser();

            return match ($attribute->getStrategy()) {
                Strategy::GET => $request->attributes->get(key: $params) + $request->query->all(),
                Strategy::POST => $request->request->all(),
                Strategy::USER => $user,
                Strategy::MIXED => [
                    Strategy::GET => $request->attributes->get(key: $params) + $request->query->all(),
                    Strategy::POST => $request->request->all(),
                    Strategy::USER => $user,
                ],
                Strategy::ALL => $request->attributes->get(key: $params) + $request->query->all() + $request->request->all() + $user,
                default => throw new InvalidArgumentException(message: sprintf('Unknown strategy: %s', $attribute->getStrategy())),
            };
        }

        return [];
    }

    public function getAttribute(KernelEvent $kernelEvent, string $class): ?object
    {
        $controller = $this->getController(kernelEvent: $kernelEvent);

        try {
            $reflectionMethod = (new ReflectionClass(objectOrClass: reset(array: $controller)))->getMethod(name: end(array: $controller));
            foreach ($reflectionMethod->getAttributes(name: $class) as $attribute) {
                if ($class === $attribute->getName()) {
                    return $attribute->newInstance();
                }
            }
        } catch (Exception) {
            // exception === no attribute
        }

        return null;
    }

    public function getController(KernelEvent $kernelEvent): array
    {
        $controller = $kernelEvent->getRequest()->get(key: '_controller');

        if ((null !== $controller) && is_array(value: $instance = explode(separator: '::', string: (string) $controller, limit: 2)) && isset($instance[1])) {
            return $instance;
        }

        return [];
    }

    private function getUser(): array
    {
        $user = $this->security->getUser();

        return match (true) {
            null !== $user && method_exists(object_or_class: $user, method: 'toArray') => $user->toArray(),
            $user instanceof JsonSerializable => $user->jsonSerialize(),
            default => [],
        };
    }
}
