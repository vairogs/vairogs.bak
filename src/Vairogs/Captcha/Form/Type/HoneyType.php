<?php declare(strict_types = 1);

namespace Vairogs\Captcha\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vairogs\Core\DependencyInjection\Component;
use Vairogs\Core\Vairogs;
use function sprintf;

class HoneyType extends HiddenType
{
    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(defaults: [
            'attr' => ['value' => ''],
            'required' => false,
            'translation_domain' => sprintf('%s_%s', Vairogs::VAIROGS, Component::CAPTCHA),
        ]);
    }
}
