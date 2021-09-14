<?php declare(strict_types = 1);

namespace Vairogs\Component\Auth\OpenIDConnect\Configuration;

use Vairogs\Component\Auth\OpenIDConnect\OpenIDConnectProvider;

class DefaultProvider extends OpenIDConnectProvider
{
    public function getValidateTokenUrl(): string
    {
        return '';
    }

    public function getRefreshTokenUrl(): string
    {
        return '';
    }

    public function getRevokeTokenUrl(): string
    {
        return '';
    }
}
