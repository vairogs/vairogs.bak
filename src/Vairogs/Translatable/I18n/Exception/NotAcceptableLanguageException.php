<?php declare(strict_types = 1);

namespace Vairogs\Translatable\I18n\Exception;

use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

class NotAcceptableLanguageException extends NotAcceptableHttpException
{
    public function __construct(private string $requestedLanguage, private array $availableLanguages)
    {
        parent::__construct(message: sprintf('The requested language "%s" is not available. Available languages are: "%s"', $requestedLanguage, implode(separator: ', ', array: $availableLanguages)));
    }

    public function getRequestedLanguage(): string
    {
        return $this->requestedLanguage;
    }

    public function getAvailableLanguages(): array
    {
        return $this->availableLanguages;
    }
}
