<?php declare(strict_types = 1);

namespace Vairogs\Addon\Auth\OpenID\Steam\Contracts;

use Vairogs\Auth\OpenID\Contracts\OpenIDUser;

interface User extends OpenIDUser, UserSetters, UserGetters
{
}
