<?php declare(strict_types = 1);

namespace Vairogs\Tests\Captcha\Type;

use Symfony\Component\Form\Test\TypeTestCase;
use Vairogs\Tests\Assets\Captcha\TestFormModel;
use Vairogs\Tests\Assets\Captcha\TestFormType;

class HoneyTypeTest extends TypeTestCase
{
    public function testHoneyType(): void
    {
        $formData = [
            'honey' => null,
            'name' => __CLASS__,
        ];

        $formDataInvalid = [
            'honey' => __CLASS__,
            'name' => __FUNCTION__,
        ];

        $model = new TestFormModel();
        $modelInvalid = new TestFormModel();

        $form = $this->factory->create(type: TestFormType::class, data: $model);
        $form->submit(submittedData: $formData);
        $this->assertTrue(condition: $form->isSynchronized());
        $this->assertTrue(condition: $form->isSubmitted());
        $this->assertTrue(condition: $form->isValid());

        $formInvalid = $this->factory->create(type: TestFormType::class, data: $modelInvalid);
        $formInvalid->submit(submittedData: $formDataInvalid);
        $this->assertTrue(condition: $formInvalid->isSynchronized());
        $this->assertTrue(condition: $formInvalid->isSubmitted());
        $this->assertFalse(condition: $formInvalid->isValid());
    }
}
