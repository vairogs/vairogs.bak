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
    #[Route(path: '/sitemap.xml', defaults: ['_format' => 'xml'], methods: [Request::METHOD_GET])]
    public function sitemapXml(Request $request, ValidatorInterface $validator, ?Provider $provider = null, array $options = []): Response
    {
        if (is_file(filename: $sitemap = getcwd() . '/sitemap.xml')) {
            return new Response(content: file_get_contents(filename: $sitemap));
        }

        if (null === $provider || (false === $options[Dependency::ENABLED])) {
            throw new NotFoundHttpException(message: 'To use vairogs/component-sitemap, you must enable it and provide a Provider');
        }

        $model = $provider->populate(host: $request->getSchemeAndHttpHost());
        $constraintViolationList = $validator->validate(value: $model);

        if (0 !== $constraintViolationList->count()) {
            return (new ErrorResponse(constraintViolationList: $constraintViolationList))->getResponse();
        }

        return new Response(content: (new Director(buffer: ''))->build(builder: new XmlBuilder(sitemap: $model)));
    }
}
