<?php declare(strict_types = 1);

namespace Vairogs\Component\Auth\OpenID;

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Vairogs\Component\Auth\OpenID\DependencyInjection\VairogsAuthOpenIDExtension;

class OpenID extends Bundle
{
    /**
     * @var string
     */
    public const ALIAS = 'openid';

    public const OPENID_BASE_ALIAS = 'auth';

    /**
     * @return null|ExtensionInterface
     */
    public function getContainerExtension(): ?ExtensionInterface
    {
        return $this->extension ?? new VairogsAuthOpenIDExtension();
    }
}
