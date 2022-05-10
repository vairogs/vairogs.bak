<?php declare(strict_types = 1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Config\VairogsConfig;
use Vairogs\Auth\OpenIDConnect\Configuration\DefaultProvider;
use Vairogs\Core\Registry\Registry;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return static function (VairogsConfig $config, ContainerConfigurator $containerConfigurator): void {
    $config
        ->cache()
        ->enabled(value: true);

    $auth = $config
        ->auth()
        ->enabled(value: true);

    $auth
        ->openidconnect()
        ->enabled(value: true);

    $auth
        ->openid()
        ->enabled(value: true);

    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    $services
        ->set(id: Registry::class)
        ->public()
        ->args(arguments: [tagged_iterator(tag: 'vairogs.auth.openidconnect.clients')]);

    $containerConfigurator->extension(
        namespace: 'vairogs',
        config: [
            'auth' => [
                'openidconnect' => [
                    'clients' => [
                        'vairogs' => [
                            'client_id' => 'vairogs',
                            'client_secret' => 'vairogs',
                            'id_token_issuer' => 'vairogs',
                            'public_key' => '../config/certs/vairogs/public.pem',
                            'base_uri_post' => 'vairogs',
                            'base_uri' => 'vairogs',
                            'use_session' => true,
                            'verify' => false,
                            'redirect' => [
                                'type' => 'route',
                                'route' => 'tests_foo',
                            ],
                            'user_provider' => DefaultProvider::class,
                            'uris' => [
                                'autologin' => [
                                    'params' => [
                                        'authorize',
                                    ],
                                    'url_params' => [
                                        'prompt' => 'none',
                                        'response_type' => 'code',
                                        'scope' => 'openid',
                                    ],
                                    'method' => Request::METHOD_GET,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    );
};
