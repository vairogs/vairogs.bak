<?php

namespace Vairogs\Component\Utils\Doctrine\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass()
 */
#[ORM\MappedSuperclass]
abstract class Log
{
    /**
     * @ORM\Column(type="text")
     */
    #[ORM\Column(type: 'text')]
    protected string $message;

    /**
     * @ORM\Column(type="array")
     */
    #[ORM\Column(type: 'array')]
    protected array $context;

    /**
     * @ORM\Column(type="smallint")
     */
    #[ORM\Column(type: 'smallint')]
    protected int $level;

    /**
     * @ORM\Column(type="string", length=50)
     */
    #[ORM\Column(type: 'string', length: 50)]
    protected string $levelName;

    /**
     * @ORM\Column(type="array")
     */
    #[ORM\Column(type: 'extra')]
    protected array $extra;

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): Log
    {
        $this->message = $message;
        return $this;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function setContext(array $context): Log
    {
        $this->context = $context;
        return $this;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): Log
    {
        $this->level = $level;
        return $this;
    }

    public function getLevelName(): string
    {
        return $this->levelName;
    }

    public function setLevelName(string $levelName): Log
    {
        $this->levelName = $levelName;
        return $this;
    }

    public function getExtra(): array
    {
        return $this->extra;
    }

    public function setExtra(array $extra): Log
    {
        $this->extra = $extra;
        return $this;
    }
}
