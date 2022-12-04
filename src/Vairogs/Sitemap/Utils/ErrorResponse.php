<?php declare(strict_types = 1);

namespace Vairogs\Sitemap\Utils;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Vairogs\Functions\Constants\ContentType;

readonly class ErrorResponse
{
    private Response $response;

    public function __construct(private ConstraintViolationListInterface $violations)
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

        foreach ($this->violations as $violation) {
            /* @var ConstraintViolation $violation */
            $buffer .= "\t" . '<error>' .
                "\n\t\t" . '<property_path>' . $violation->getPropertyPath() . '</property_path>' .
                "\n\t\t" . '<message>' . $violation->getMessage() . '</message>' .
                "\n\t" . '</error>' . "\n";
        }

        $buffer .= '</errors>
<!-- error from sitemap library for Symfony vairogs/sitemap -->';

        $this->response->setContent(content: $buffer);

        return $this->response;
    }
}
