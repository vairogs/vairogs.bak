<?php declare(strict_types = 1);

namespace Vairogs\Utils\Doctrine\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use function in_array;

trait Status
{
    #[ORM\Column(type: Types::INTEGER, nullable: false, options: ['default' => 0])]
    private int $status = 0;

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateStatus(): static
    {
        if (!in_array(needle: $this->status, haystack: [0, 1, ], strict: true)) {
            $this->status = 0;
        }

        return $this;
    }
}
