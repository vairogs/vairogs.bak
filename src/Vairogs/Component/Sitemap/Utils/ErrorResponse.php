<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\Utils;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ErrorResponse
{
    private Response $response;

    public function __construct(private ConstraintViolationListInterface $constraintViolationList)
    {
        $this->response = new Response();
        $this->response->headers->set('Content-Type', 'application/xml');
        /** @noinspection UnusedFunctionResultInspection */
        $this->response->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function getResponse(): Response
    {
        // @formatter:off
        $buffer = '<?xml version="1.0" encoding="UTF-8"?>
<errors>
';
        // @formatter:on
        foreach ($this->constraintViolationList as $error) {
            /** @var ConstraintViolation $error */
            // @formatter:off
            $buffer .= "\t" . '<error>' .
                "\n\t\t" . '<property_path>' . $error->getPropertyPath() . '</property_path>' .
                "\n\t\t" . '<message>' . $error->getMessage() . '</message>' .
                "\n\t" . '</error>' . "\n";
            // @formatter:on
        }
        $buffer .= '</errors>
<!-- error from sitemap library for Symfony vairogs/sitemap -->';

        $this->response->setContent($buffer);

        return $this->response;
    }
}
