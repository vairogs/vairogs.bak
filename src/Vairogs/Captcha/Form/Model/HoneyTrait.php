<?php declare(strict_types = 1);

namespace Vairogs\Captcha\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

trait HoneyTrait
{
    #[Assert\IsNull(message: 'The CSRF token is invalid. Please try to resubmit the form')]
    private mixed $honey = null;

    public function getHoney(): mixed
    {
        return $this->honey;
    }

    public function setHoney(mixed $honey): static
    {
        $this->honey = $honey;

        return $this;
    }
}
