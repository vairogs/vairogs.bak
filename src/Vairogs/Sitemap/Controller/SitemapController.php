<?php declare(strict_types = 1);

namespace Vairogs\Sitemap\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Vairogs\Core\DependencyInjection\Component;
use Vairogs\Core\Vairogs;
use Vairogs\Extra\Constants\Status;
use Vairogs\Sitemap\Builder\Director;
use Vairogs\Sitemap\Builder\XmlBuilder;
use Vairogs\Sitemap\Provider;
use Vairogs\Sitemap\Utils\ErrorResponse;
use function file_get_contents;
use function getcwd;
use function is_file;
use function sprintf;

class SitemapController extends AbstractController
{
    #[Route(path: '/sitemap.xml', defaults: ['_format' => 'xml'], methods: [Request::METHOD_GET])]
    public function sitemapXml(Request $request, ValidatorInterface $validator, ?Provider $provider = null): Response
    {
        if (is_file(filename: $sitemap = getcwd() . '/sitemap.xml')) {
            return new Response(content: file_get_contents(filename: $sitemap));
        }

        if (null === $provider || false === $this->getParameter(sprintf('%s.%s.%s', Vairogs::VAIROGS, Component::SITEMAP, Status::ENABLED))) {
            throw new NotFoundHttpException(message: 'To use vairogs/sitemap, you must enable it and provide a Provider');
        }

        $model = $provider->populate(host: $request->getSchemeAndHttpHost());
        $constraintViolationList = $validator->validate(value: $model);

        if (0 !== $constraintViolationList->count()) {
            return (new ErrorResponse(constraintViolationList: $constraintViolationList))->getResponse();
        }

        return new Response(content: (new Director(buffer: ''))->build(builder: new XmlBuilder(sitemap: $model)));
    }
}
