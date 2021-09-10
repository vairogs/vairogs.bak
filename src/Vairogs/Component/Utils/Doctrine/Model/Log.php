<?php

namespace Vairogs\Component\Utils\Doctrine\Model;

use Doctrine\ORM\Mapping as ORM;
use Vairogs\Extra\Constants\Type\Basic;
use Vairogs\Extra\Constants\Type\Numeric;

/**
 * @ORM\MappedSuperclass()
 */
#[ORM\MappedSuperclass]
class Log
{
    /**
     * @ORM\Column(type=Basic::TEXT)
     */
    #[ORM\Column(type: Basic::TEXT)]
    protected string $message;

    /**
     * @ORM\Column
     */
    #[ORM\Column]
    protected array $context;

    /**
     * @ORM\Column(type=Numeric::SMALLINT)
     */
    #[ORM\Column(type: Numeric::SMALLINT)]
    protected int $level;

    /**
     * @ORM\Column(length=50)
     */
    #[ORM\Column(length: 50)]
    protected string $levelName;

    /**
     * @ORM\Column
     */
    #[ORM\Column]
    protected array $extra;

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function setContext(array $context): static
    {
        $this->context = $context;

        return $this;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getLevelName(): string
    {
        return $this->levelName;
    }

    public function setLevelName(string $levelName): static
    {
        $this->levelName = $levelName;

        return $this;
    }

    public function getExtra(): array
    {
        return $this->extra;
    }

    public function setExtra(array $extra): static
    {
        $this->extra = $extra;

        return $this;
    }
}
