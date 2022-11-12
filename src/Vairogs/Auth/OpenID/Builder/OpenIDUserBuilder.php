<?php declare(strict_types = 1);

namespace Vairogs\Auth\OpenID\Builder;

use Vairogs\Auth\OpenID\Model\OpenIDUser;

interface OpenIDUserBuilder
{
    public function getUser(array $response): OpenIDUser;

    public function setUserClass(string $class): static;

    public function getUserClass(): string;
}
