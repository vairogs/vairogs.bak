<?php declare(strict_types = 1);

namespace Vairogs\Component\Captcha\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

trait HoneyTrait
{
    #[Assert\IsNull(message: 'vairogs_captcha.error.honey.value_set')]
    private mixed $honey;

    public function getHoney(): mixed
    {
        return $this->honey;
    }

    public function setHoney(mixed $honey): self
    {
        $this->honey = $honey;

        return $this;
    }
}
