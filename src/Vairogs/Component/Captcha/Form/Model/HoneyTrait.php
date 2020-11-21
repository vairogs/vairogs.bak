<?php declare(strict_types = 1);

namespace Vairogs\Component\Captcha\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

trait HoneyTrait
{
    /**
     * @var null
     * @Assert\IsNull(
     *     message="vairogs_captcha.error.honey.value_set"
     * )
     */
    private $honey;

    /**
     * @return null
     */
    public function getHoney()
    {
        return $this->honey;
    }

    /**
     * @param null $honey
     *
     * @return self
     */
    public function setHoney($honey): self
    {
        $this->honey = $honey;

        return $this;
    }
}
