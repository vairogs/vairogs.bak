<?php declare(strict_types = 1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Config\VairogsConfig;
use Vairogs\Addon\Auth\OpenID\Steam\Builder\SteamGiftsUserBuilder;
use Vairogs\Addon\Auth\OpenID\Steam\Model\SteamGifts;
use Vairogs\Auth\OpenIDConnect\Configuration\DefaultProvider;
use Vairogs\Auth\OpenIDConnect\Utils\Constants\Enum\Redirect;
use Vairogs\Core\Registry\Registry;
use Vairogs\Utils\Helper\Composer;
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
        ->set(id: 'vairogs.auth.openidconnect.registry')
        ->class(class: Registry::class)
        ->public()
        ->args(arguments: [tagged_iterator(tag: 'vairogs.auth.openidconnect.clients')]);

    $services
        ->set(id: 'vairogs.auth.openid.registry')
        ->class(class: Registry::class)
        ->public()
        ->args(arguments: [tagged_iterator(tag: 'vairogs.auth.openid.clients')]);

    $services->alias(id: Registry::class . ' $$openIDConnectRegistry', referencedId: 'vairogs.auth.openidconnect.clients');
    $services->alias(id: Registry::class . ' $$openIDRegistry', referencedId: 'vairogs.auth.openids.clients');

    $clientConfig = [
        'client_id' => 'vairogs',
        'client_secret' => 'vairogs',
        'id_token_issuer' => 'vairogs',
        'public_key' => '../config/certs/vairogs/public.pem',
        'base_uri_post' => 'vairogs',
        'base_uri' => 'vairogs',
        'use_session' => true,
        'verify' => false,
        'redirect' => [
            'type' => Redirect::ROUTE->value,
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
    ];
    $clientConfig2 = $clientConfig;

    $clientConfig2['redirect'] = [
        'type' => Redirect::URI->value,
        'uri' => 'https://www.google.com',
    ];

    $containerConfigurator->extension(
        namespace: 'vairogs',
        config: [
            'auth' => [
                'openidconnect' => [
                    'clients' => [
                        'vairogs' => $clientConfig,
                        'vairogs2' => $clientConfig2,
                    ],
                ],
                'openid' => [
                    'clients' => [
                        'steamgifts' => [
                            'api_key' => (new Composer())->getenv(name: 'STEAM_API_KEY'),
                            'openid_url' => 'https://steamcommunity.com/openid/login',
                            'preg_check' => '#^https://steamcommunity.com/openid/id/([0-9]{17,25})#',
                            'user_builder' => SteamGiftsUserBuilder::class,
                            'user_class' => SteamGifts::class,
                            'redirect_route' => 'tests_foo',
                            'provider_options' => [
                                'profile_url' => 'https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2/?key=#api_key#&steamids=#openid#',
                                'profile_url_replace' => [
                                    'api_key',
                                    'openid',
                                ],
                                'owned_url' => 'https://api.steampowered.com/IPlayerService/GetOwnedGames/v1/?key=#api_key#&steamid=#openid#&format=json&skip_unvetted_apps=0&include_free_sub=1',
                                'owned_url_json' => 'https://api.steampowered.com/IPlayerService/GetOwnedGames/v1/?key=#api_key#&format=json&input_json={"steamid":#openid#,"skip_unvetted_apps":0,"include_free_sub":1,"appids_filter":[#appid#]}',
                                'owned_url_replace' => [
                                    'api_key',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    );
};
