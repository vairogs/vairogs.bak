<?php declare(strict_types = 1);

namespace Vairogs\Component\Cache\Utils;

use Doctrine\Common\Annotations\Reader;
use InvalidArgumentException;
use JsonSerializable;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use function end;
use function explode;
use function is_array;
use function method_exists;
use function reset;
use function sprintf;

class Event
{
    private const PARAMS = '_route_params';

    public function __construct(protected Reader $reader, protected ?TokenStorageInterface $tokenStorage = null)
    {
    }

    /**
     * @throws ReflectionException
     * @throws InvalidArgumentException
     */
    public function getAttributes(KernelEvent $kernelEvent, string $class): array
    {
        if (null !== ($annotation = $this->getAnnotation($kernelEvent, $class))) {
            $request = $kernelEvent->getRequest();

            $user = $this->getUser();

            return match ($annotation->getStrategy()) {
                Strategy::GET => $request->attributes->get(self::PARAMS) + $request->query->all(),
                Strategy::POST => $request->request->all(),
                Strategy::USER => $user,
                Strategy::MIXED => [
                    Strategy::GET => $request->attributes->get(self::PARAMS) + $request->query->all(),
                    Strategy::POST => $request->request->all(),
                    Strategy::USER => $user,
                ],
                Strategy::ALL => $request->attributes->get(self::PARAMS) + $request->query->all() + $request->request->all() + $user,
                default => throw new InvalidArgumentException(sprintf('Unknown strategy: %s', $annotation->getStrategy())),
            };
        }

        return [];
    }

    /**
     * @throws ReflectionException
     */
    public function getAnnotation(KernelEvent $kernelEvent, string $class): ?object
    {
        $controller = $this->getController($kernelEvent);
        $reflectionClass = new ReflectionClass(reset($controller));

        if ($method = $reflectionClass->getMethod(end($controller))) {
            foreach ($method->getAttributes($class) as $attribute) {
                if ($class === $attribute->getName()) {
                    return $attribute->newInstance();
                }
            }

            if (($object = $this->reader->getMethodAnnotation($method, $class)) instanceof $class) {
                return $object;
            }
        }

        return null;
    }

    public function getController(KernelEvent $kernelEvent): array
    {
        $controller = $kernelEvent->getRequest()
            ->get('_controller');

        if ((null !== $controller) && is_array($instance = explode('::', $controller, 2)) && isset($instance[1])) {
            return $instance;
        }

        return [];
    }

    private function getUser(): array
    {
        $user = $this->tokenStorage?->getToken()?->getUser();

        if (null !== $user) {
            if (method_exists($user, 'toArray')) {
                return $user->toArray();
            }

            if (method_exists($user, '__toArray')) {
                return $user->__toArray();
            }

            if ($user instanceof JsonSerializable) {
                return $user->jsonSerialize();
            }
        }

        return [];
    }
}
