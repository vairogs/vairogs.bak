<?php declare(strict_types = 1);

namespace Vairogs\Addon\Auth\OpenID\Steam\Model;

use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Stringable;

/**
 * @ORM\MappedSuperclass()
 */
class SteamGifts extends Steam implements Stringable
{
    /**
     * @return string
     */
    #[Pure] public function __toString(): string
    {
        return $this->getUsername() ?? $this->getOpenID();
    }
}
