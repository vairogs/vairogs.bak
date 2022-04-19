<?php declare(strict_types = 1);

namespace Vairogs\Cache\Event;

use Exception;
use InvalidArgumentException;
use JsonSerializable;
use ReflectionClass;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\Security\Core\Security;
use Vairogs\Cache\Strategy;
use function end;
use function explode;
use function is_array;
use function reset;
use function sprintf;

class CacheEvent
{
    public function __construct(private readonly Security $security)
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getAttributes(KernelEvent $kernelEvent, string $class): array
    {
        $params = '_route_params';

        if (null !== ($attribute = $this->getAtribute(kernelEvent: $kernelEvent, class: $class))) {
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

    public function getAtribute(KernelEvent $kernelEvent, string $class): ?object
    {
        $controller = $this->getController(kernelEvent: $kernelEvent);

        try {
            $method = (new ReflectionClass(objectOrClass: reset(array: $controller)))->getMethod(name: end(array: $controller));
            foreach ($method->getAttributes(name: $class) as $attribute) {
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
        $controller = $kernelEvent->getRequest()?->get(key: '_controller');

        if ((null !== $controller) && is_array(value: $instance = explode(separator: '::', string: $controller, limit: 2)) && isset($instance[1])) {
            return $instance;
        }

        return [];
    }

    private function getUser(): array
    {
        $user = $this->security->getUser();

        if ($user instanceof JsonSerializable) {
            return $user->jsonSerialize();
        }

        return [];
    }
}
