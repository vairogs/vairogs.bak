<?php declare(strict_types = 1);

namespace Vairogs\Addon\Auth\OpenID\Steam\Model;

use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

/**
 * @ORM\MappedSuperclass()
 */
#[ORM\MappedSuperclass]
class SteamGifts extends Steam
{
    #[Pure]
    public function __toString(): string
    {
        return $this->getUsername() ?? $this->getOpenID();
    }
}
