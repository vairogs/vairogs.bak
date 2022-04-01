<?php declare(strict_types = 1);

namespace Vairogs\Utils\DependencyInjection;

final class Component
{
    public const AUTH = 'auth';
    public const AUTH_OPENID = 'openid';
    public const AUTH_OPENIDCONNECT = 'openidconnect';
    public const CACHE = 'cache';
    public const CAPTCHA = 'captcha';
    public const SITEMAP = 'sitemap';
    public const TRANSLATABLE = 'translatable';
    public const TRANSLATABLE_ADMINTYPE = 'admintype';
    public const TRANSLATABLE_TRANSLATION = 'translation';
}
