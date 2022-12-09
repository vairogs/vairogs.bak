<?php declare(strict_types = 1);

namespace Vairogs\Functions\Tests\Traits;

trait ExtraVariables
{
    private string $name;
    private static string $title;

    public static function getTitle(): string
    {
        return self::$title;
    }

    public static function setTitle(string $title): void
    {
        self::$title = $title;
    }

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
