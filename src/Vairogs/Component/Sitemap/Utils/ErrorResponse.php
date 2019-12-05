<?php declare(strict_types = 1);

namespace Vairogs\Component\Sitemap\Utils;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ErrorResponse
{
    /**
     * @var ConstraintViolationListInterface
     */
    private $errors;

    /**
     * @var Response
     */
    private $response;

    /**
     * @param ConstraintViolationListInterface $errors
     */
    public function __construct(ConstraintViolationListInterface $errors)
    {
        $this->errors = $errors;
        $this->response = new Response();
        $this->response->headers->set('Content-Type', 'application/xml');
        $this->response->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        // @formatter:off
        $buffer = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" .
            '<errors>' . "\n";
        // @formatter:on
        foreach ($this->errors as $error) {
            /** @var ConstraintViolation $error */
            // @formatter:off
            $buffer .= "\t" . '<error>' .
                "\n\t\t" . '<property_path>' . $error->getPropertyPath() . '</property_path>' .
                "\n\t\t" . '<message>' . $error->getMessage() . '</message>' .
                "\n\t" . '</error>' . "\n";
            // @formatter:on
        }
        $buffer .= '</errors>' . "\n" . '<!-- error from sitemap library for Symfony vairogs/sitemap -->';;

        $this->response->setContent($buffer);

        return $this->response;
    }
}
