<?php declare(strict_types = 1);

namespace Vairogs\Component\Auth\OpenID\Contracts;

interface OpenIDUser
{
    /**
     * @return string
     */
    public function getOpenID(): string;

    /**
     * @return bool
     */
    public function returnsEmail(): bool;

    /**
     * @return string|null
     */
    public function getEmail(): ?string;
}
