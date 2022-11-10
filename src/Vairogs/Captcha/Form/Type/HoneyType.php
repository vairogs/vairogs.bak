<?php declare(strict_types = 1);

namespace Vairogs\Captcha\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vairogs\Core\Vairogs;

use function sprintf;

class HoneyType extends HiddenType
{
    public const CAPTCHA = 'captcha';

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(defaults: [
            'attr' => ['value' => ''],
            'required' => false,
            'translation_domain' => sprintf('%s_%s', Vairogs::VAIROGS, self::CAPTCHA),
        ]);
    }
}
