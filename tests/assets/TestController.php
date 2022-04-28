<?php declare(strict_types = 1);

namespace Vairogs\Assets;

use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use UnexpectedValueException;
use Vairogs\Cache\Cache;

class TestController extends AbstractController
{
    public function __call(string $method, array $arguments)
    {
        return $this->{$method}(...$arguments);
    }

    public function setContainer(ContainerInterface $container): ?ContainerInterface
    {
        if (!$this->throwOnUnexpectedService) {
            return parent::setContainer(container: $container);
        }

        $expected = self::getSubscribedServices();

        foreach ($container->getServiceIds() as $id) {
            if ('service_container' === $id) {
                continue;
            }
            if (!isset($expected[$id])) {
                throw new UnexpectedValueException(message: sprintf('Service "%s" is not expected, as declared by "%s::getSubscribedServices()".', $id, AbstractController::class));
            }
            $type = substr(string: $expected[$id], offset: 1);
            if (!$container->get(id: $id) instanceof $type) {
                throw new UnexpectedValueException(message: sprintf('Service "%s" is expected to be an instance of "%s", as declared by "%s::getSubscribedServices()".', $id, $type, AbstractController::class));
            }
        }

        return parent::setContainer($container);
    }

    #[Cache(expires: 30)]
    public function fooAction(): JsonResponse
    {
        return new JsonResponse(data: time());
    }
}
