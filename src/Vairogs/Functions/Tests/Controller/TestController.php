<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests\Controller;

use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use UnexpectedValueException;

use function sprintf;
use function substr;
use function time;

#[Route('/tests', name: 'tests_')]
class TestController extends AbstractController
{
    public function __call(string $method, array $arguments)
    {
        return $this->{$method}(...$arguments);
    }

    /**
     * @noinspection PhpUnhandledExceptionInspection
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     * @noinspection PhpUndefinedFieldInspection
     * @noinspection PhpNamedArgumentMightBeUnresolvedInspection
     */
    public function setContainer(ContainerInterface $container): ?ContainerInterface
    {
        if (!$this->throwOnUnexpectedService) {
            return parent::setContainer(container: $container);
        }

        $expected = self::getSubscribedServices();

        foreach ($container->getServiceIds() as $serviceId) {
            if ('service_container' === $serviceId) {
                continue;
            }
            if (!isset($expected[$serviceId])) {
                throw new UnexpectedValueException(message: sprintf('Service "%s" is not expected, as declared by "%s::getSubscribedServices()".', $serviceId, AbstractController::class));
            }
            $type = substr(string: $expected[$serviceId], offset: 1);
            if (!$container->get(id: $serviceId) instanceof $type) {
                throw new UnexpectedValueException(message: sprintf('Service "%s" is expected to be an instance of "%s", as declared by "%s::getSubscribedServices()".', $serviceId, $type, AbstractController::class));
            }
        }

        return parent::setContainer(container: $container);
    }

    #[Route('/foo', name: 'foo')]
    public function fooAction(): JsonResponse
    {
        return new JsonResponse(data: time());
    }
}
