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

class Attribute
{
    private const ROUTE_PARAMS = '_route_params';

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
                Strategy::GET => $request->attributes->get(self::ROUTE_PARAMS) + $request->query->all(),
                Strategy::POST => $request->request->all(),
                Strategy::USER => $user,
                Strategy::MIXED => [
                    Strategy::GET => $request->attributes->get(self::ROUTE_PARAMS) + $request->query->all(),
                    Strategy::POST => $request->request->all(),
                    Strategy::USER => $user,
                ],
                Strategy::ALL => $request->attributes->get(self::ROUTE_PARAMS) + $request->query->all() + $request->request->all() + $user,
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
            return $this->reader->getMethodAnnotation($method, $class);
        }

        return null;
    }

    public function getController(KernelEvent $kernelEvent): array
    {
        $kernelController = $kernelEvent->getRequest()
            ->get('_controller');

        if ((null !== $kernelController) && is_array($controller = explode('::', $kernelController, 2)) && isset($controller[1])) {
            return $controller;
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
