<?php declare(strict_types = 1);

namespace Vairogs\Utils\Doctrine\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Vairogs\Extra\Constants;

trait Status
{
    #[ORM\Column(type: Types::INTEGER, nullable: false, options: [Constants\Definition::DEFAULT => Constants\Status::ZERO])]
    private int $status = Constants\Status::ZERO;

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        if (Constants\Status::ONE !== $status) {
            $status = Constants\Status::ZERO;
        }

        $this->status = $status;

        return $this;
    }
}
