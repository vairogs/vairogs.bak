<?php declare(strict_types = 1);

namespace Vairogs\Component\Translation\Form\Type;

use Symfony\Component\Form\AbstractType;

class TranslatorType extends AbstractType
{
    public const DEFAULT_CLASS = '';
    public const DEFAULT_TYPE = 'text';

    private array $locales;
    private array $userLocale;
}
