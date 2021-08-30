<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Vairogs\Component\Sitemap\Builder\Director;
use Vairogs\Component\Sitemap\Builder\XmlBuilder;
use Vairogs\Component\Sitemap\Provider;
use Vairogs\Component\Sitemap\Utils\ErrorResponse;
use Vairogs\Component\Utils\DependencyInjection\Dependency;
use function file_get_contents;
use function getcwd;
use function is_file;

class SitemapController extends AbstractController
{
    #[Route(path: '/sitemap.xml', name: 'sitemap.xml', defaults: ['_format' => 'xml'], methods: [Request::METHOD_GET])]
    public function sitemap(Request $request, ValidatorInterface $validator, ?Provider $provider = null, array $options = []): Response
    {
        if (is_file($sitemap = getcwd() . '/sitemap.xml')) {
            return new Response(file_get_contents($sitemap));
        }
        if (null === $provider || (false === $options[Dependency::ENABLED])) {
            throw new NotFoundHttpException('To use vairogs/sitemap, you must enable it and provide a Provider');
        }
        $model = $provider->populate($request->getSchemeAndHttpHost());
        $constraintViolationList = $validator->validate($model);
        if (0 !== $constraintViolationList->count()) {
            return (new ErrorResponse($constraintViolationList))->getResponse();
        }

        return new Response((new Director(''))->build(new XmlBuilder($model)));
    }
}
