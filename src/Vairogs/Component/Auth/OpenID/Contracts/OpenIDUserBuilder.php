<?php declare(strict_types = 1);

namespace Vairogs\Component\Auth\OpenID\Contracts;

interface OpenIDUserBuilder
{
    /**
     * @param array $response
     * @return OpenIDUser
     */
    public function getUser(array $response): OpenIDUser;

    /**
     * @param string $class
     * @return OpenIDUserBuilder
     */
    public function setUserClass(string $class): OpenIDUserBuilder;
}
