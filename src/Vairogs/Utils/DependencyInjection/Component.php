<?php declare(strict_types = 1);

namespace Vairogs\Utils\DependencyInjection;

final class Component
{
    final public const AUTH = 'auth';
    final public const AUTH_OPENID = 'openid';
    final public const AUTH_OPENIDCONNECT = 'openidconnect';
    final public const CACHE = 'cache';
    final public const CAPTCHA = 'captcha';
    final public const SITEMAP = 'sitemap';
    final public const TRANSLATABLE = 'translatable';
    final public const TRANSLATABLE_ADMINTYPE = 'admintype';
    final public const TRANSLATABLE_TRANSLATION = 'translation';
}
