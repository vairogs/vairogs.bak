<?php declare(strict_types = 1);

namespace Vairogs\Tests\Assets\Captcha;

use Vairogs\Captcha\Form\Model\HoneyTrait;

class TestFormModel
{
    use HoneyTrait;

    private string $name;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
