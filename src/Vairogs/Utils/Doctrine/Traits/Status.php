<?php declare(strict_types = 1);

namespace Vairogs\Utils\Doctrine\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Vairogs\Extra\Constants\Definition;
use Vairogs\Extra\Constants\Status as Constant;

use function in_array;

trait Status
{
    #[ORM\Column(type: Types::INTEGER, nullable: false, options: [Definition::DEFAULT => Constant::ZERO])]
    private int $status = Constant::ZERO;

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateStatus(): self
    {
        if (!in_array($this->status, [Constant::ZERO, Constant::ONE, ], true)) {
            $this->status = Constant::ZERO;
        }

        return $this;
    }
}
