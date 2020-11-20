<?php

namespace Vairogs\Component\Utils\Doctrine\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass()
 */
abstract class Log
{
    /**
     * @ORM\Column(name="message", type="text")
     */
    protected string $message;

    /**
     * @ORM\Column(name="context", type="array")
     */
    protected array $context;

    /**
     * @ORM\Column(name="level", type="smallint")
     */
    protected int $level;

    /**
     * @ORM\Column(name="level_name", type="string", length=50)
     */
    protected string $levelName;

    /**
     * @ORM\Column(name="extra", type="array")
     */
    protected array $extra;

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return Log
     */
    public function setMessage(string $message): Log
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * @param array $context
     * @return Log
     */
    public function setContext(array $context): Log
    {
        $this->context = $context;
        return $this;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @param int $level
     * @return Log
     */
    public function setLevel(int $level): Log
    {
        $this->level = $level;
        return $this;
    }

    /**
     * @return string
     */
    public function getLevelName(): string
    {
        return $this->levelName;
    }

    /**
     * @param string $levelName
     * @return Log
     */
    public function setLevelName(string $levelName): Log
    {
        $this->levelName = $levelName;
        return $this;
    }

    /**
     * @return array
     */
    public function getExtra(): array
    {
        return $this->extra;
    }

    /**
     * @param array $extra
     * @return Log
     */
    public function setExtra(array $extra): Log
    {
        $this->extra = $extra;
        return $this;
    }
}
