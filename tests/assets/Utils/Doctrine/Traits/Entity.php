<?php declare(strict_types = 1);

namespace Vairogs\Assets\Utils\Doctrine\Traits;

use Vairogs\Utils\Doctrine\Traits;

class Entity
{
    use Traits\Entity;

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
