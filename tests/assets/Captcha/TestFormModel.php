<?php declare(strict_types = 1);

namespace Vairogs\Tests\Assets\Captcha;

use Vairogs\Captcha\Form\Model\Honey;

class TestFormModel
{
    use Honey;

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
