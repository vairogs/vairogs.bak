<?php declare(strict_types = 1);

namespace Vairogs\Utils\Doctrine\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Vairogs\Extra\Constants\Definition;

trait Status
{
    #[ORM\Column(type: Types::INTEGER, nullable: false, options: [Definition::DEFAULT => Definition::DISABLED])]
    private int $status = Definition::DISABLED;

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        if (Definition::ENABLED !== $status) {
            $status = Definition::DISABLED;
        }

        $this->status = $status;

        return $this;
    }
}
