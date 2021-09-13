<?php declare(strict_types = 1);

namespace Vairogs\Component\Auth\OpenID;

use InvalidArgumentException;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use JsonException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use UnexpectedValueException;
use Vairogs\Addon\Auth\OpenID\Constants\OpenID;
use Vairogs\Component\Auth\OpenID\Contracts\OpenIDUser;
use Vairogs\Component\Auth\OpenID\Contracts\OpenIDUserBuilder;
use Vairogs\Component\Utils\Helper\Json;
use Vairogs\Component\Utils\Helper\Uri;
use Vairogs\Extra\Constants\Type\Basic;
use function array_keys;
use function explode;
use function file_get_contents;
use function http_build_query;
use function is_array;
use function preg_match;
use function sprintf;
use function str_replace;
use function stream_context_create;
use function stripslashes;
use function strlen;
use function urldecode;

class OpenIDProvider
{
    private const PROVIDER_OPTIONS = 'provider_options';

    protected Request $request;
    protected ?string $profileUrl;
    protected ?string $userClass;

    public function __construct(RequestStack $requestStack, protected RouterInterface $router, protected string $name, protected string $cacheDir, protected array $options = [])
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->profileUrl = $this->options[self::PROVIDER_OPTIONS]['profile_url'] ?? null;
        $this->userClass = $this->options['user_class'] ?? null;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @throws JsonException
     */
    public function fetchUser(): ?OpenIDUser
    {
        if (null !== $user = $this->validate()) {
            $builderClass = $this->options['user_builder'];
            /** @var OpenIDUserBuilder $builder */
            $builder = new $builderClass();
            $builder->setUserClass($this->userClass ?? $builder->getUserClass());

            if (null === $this->profileUrl) {
                $user = $builder->getUser($this->request->query->all());
            } else {
                foreach ($this->options[self::PROVIDER_OPTIONS]['profile_url_replace'] as $option) {
                    if (null !== ($replace = $this->options[$option] ?? $this->options[self::PROVIDER_OPTIONS][$option] ?? null)) {
                        $this->profileUrl = str_replace(sprintf('#%s#', $option), $replace, $this->profileUrl);
                    }
                }

                $data = $this->getData($user);
                $data['cache_dir'] = $this->cacheDir;
                $user = $builder->getUser($data);
            }
        }

        if (null === $user) {
            throw new UnexpectedValueException('Invalid login or request has timed out');
        }

        return $user;
    }

    public function validate(int $timeout = 30): ?string
    {
        $get = $this->request->query->all();
        $params = [
            OpenID::ASSOC_HANDLE => $get['openid_assoc_handle'],
            OpenID::SIGNED => $get['openid_signed'],
            OpenID::SIG => $get['openid_sig'],
            OpenID::NS => 'http://specs.openid.net/auth/2.0',
        ];

        foreach (explode(',', $get['openid_signed']) as $item) {
            $params['openid.' . $item] = stripslashes($get['openid_' . str_replace('.', '_', $item)]);
        }

        $params[OpenID::MODE] = 'check_authentication';
        $data = http_build_query($params);
        $context = stream_context_create([
            'http' => [
                'method' => Request::METHOD_POST,
                'header' => "Accept-language: en\r\n" . "Content-type: application/x-www-form-urlencoded\r\n" . 'Content-Length: ' . strlen($data) . "\r\n",
                'content' => $data,
                'timeout' => $timeout,
            ],
        ]);
        preg_match($this->options['preg_check'], urldecode($get['openid_claimed_id']), $matches);
        $openID = (is_array($matches) && isset($matches[1])) ? $matches[1] : null;

        return 1 === preg_match("#is_valid\s*:\s*true#i", file_get_contents($this->options['openid_url'] . '/' . $this->options['api_key'], false, $context)) ? $openID : null;
    }

    /**
     * @throws JsonException
     */
    private function getData(string $openID): mixed
    {
        return Json::decode(file_get_contents(str_replace('#openid#', $openID, $this->profileUrl)), 1);
    }

    public function redirect(): RedirectResponse
    {
        $redirectUri = $this->router->generate($this->options['redirect_route'], $this->options[self::PROVIDER_OPTIONS]['redirect_route_params'] ?? [], UrlGeneratorInterface::ABSOLUTE_URL);

        return new RedirectResponse($this->urlPath($redirectUri));
    }

    public function urlPath(?string $return = null, ?string $altRealm = null): string
    {
        $realm = $altRealm ?: (Uri::getSchema($this->request) . $this->request->server->get('HTTP_HOST'));

        if (null !== $return) {
            if (!$this->validateUrl($return)) {
                throw new InvalidArgumentException('Invalid return url');
            }
        } else {
            $return = $realm . $this->request->server->get('SCRIPT_NAME');
        }

        return $this->options['openid_url'] . '/' . $this->options['api_key'] . '/?' . http_build_query($this->getParams($return, $realm));
    }

    #[Pure]
    private function validateUrl(string $url): bool
    {
        return Uri::isUrl($url);
    }

    #[ArrayShape([
        OpenID::NS => Basic::STRING,
        OpenID::MODE => Basic::STRING,
        OpenID::RETURN_TO => 'string|string[]',
        OpenID::REALM => 'null|string',
        OpenID::IDENTITY => Basic::STRING,
        OpenID::CLAIMED_ID => Basic::STRING,
        OpenID::SREG_REQUIRED => 'array|mixed',
        OpenID::NS_SREG => Basic::STRING,
    ])]
    private function getParams(string $return, ?string $realm): array
    {
        if (isset($this->options[self::PROVIDER_OPTIONS]['replace'])) {
            $opt = $this->options[self::PROVIDER_OPTIONS]['replace'];
            $return = str_replace(array_keys($opt), $opt, $return);
        }

        $params = [
            OpenID::NS => 'http://specs.openid.net/auth/2.0',
            OpenID::MODE => 'checkid_setup',
            OpenID::RETURN_TO => $return,
            OpenID::REALM => $realm,
            OpenID::IDENTITY => 'http://specs.openid.net/auth/2.0/identifier_select',
            OpenID::CLAIMED_ID => 'http://specs.openid.net/auth/2.0/identifier_select',
        ];

        if ('sreg' === ($this->options[self::PROVIDER_OPTIONS]['ns_mode'] ?? '')) {
            $params[OpenID::NS_SREG] = 'http://openid.net/extensions/sreg/1.1';
            $params[OpenID::SREG_REQUIRED] = $this->options[self::PROVIDER_OPTIONS]['sreg_fields'] ?? [];
        }

        return $params;
    }
}
