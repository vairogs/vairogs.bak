<?php declare(strict_types = 1);

namespace Vairogs\Component\Captcha\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Honey extends HiddenType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'attr' => ['value' => ''],
            'required' => false,
            'translation_domain' => 'vairogs_captcha',
        ]);
    }
}
