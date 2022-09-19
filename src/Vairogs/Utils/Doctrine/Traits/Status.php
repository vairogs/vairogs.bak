<?php declare(strict_types = 1);

namespace Vairogs\Utils\Doctrine\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Vairogs\Extra\Constants\Definition;
use Vairogs\Extra\Constants\Status as Constant;

trait Status
{
    #[ORM\Column(type: Types::INTEGER, nullable: false, options: [Definition::DEFAULT => Constant::DISABLED_N])]
    private int $status = Constant::DISABLED_N;

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        if (Constant::ENABLED_N !== $status) {
            $status = Constant::DISABLED_N;
        }

        $this->status = $status;

        return $this;
    }
}
