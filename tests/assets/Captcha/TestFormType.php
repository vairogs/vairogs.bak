<?php declare(strict_types = 1);

namespace Vairogs\Tests\Assets\Captcha;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Vairogs\Captcha\Form\Type\HoneyType;

class TestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $formBuilder
            ->add(child: 'honey', type: HoneyType::class)
            ->add(child: 'name', type: TextType::class);
    }
}
