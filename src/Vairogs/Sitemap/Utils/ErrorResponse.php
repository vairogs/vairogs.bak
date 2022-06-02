<?php declare(strict_types = 1);

namespace Vairogs\Sitemap\Utils;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Vairogs\Extra\Constants\ContentType;

class ErrorResponse
{
    private readonly Response $response;

    public function __construct(private readonly ConstraintViolationListInterface $violations)
    {
        $this->response = new Response();
        $this->response->headers->set(key: 'Content-Type', values: ContentType::XML);
        $this->response->setStatusCode(code: Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function getResponse(): Response
    {
        $buffer = '<?xml version="1.0" encoding="UTF-8"?>
<errors>
';

        foreach ($this->violations as $error) {
            /* @var ConstraintViolation $error */
            $buffer .= "\t" . '<error>' .
                "\n\t\t" . '<property_path>' . $error->getPropertyPath() . '</property_path>' .
                "\n\t\t" . '<message>' . $error->getMessage() . '</message>' .
                "\n\t" . '</error>' . "\n";
        }

        $buffer .= '</errors>
<!-- error from sitemap library for Symfony vairogs/sitemap -->';

        $this->response->setContent(content: $buffer);

        return $this->response;
    }
}
