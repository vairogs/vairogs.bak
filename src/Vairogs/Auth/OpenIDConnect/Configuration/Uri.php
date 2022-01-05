<?php declare(strict_types = 1);

namespace Vairogs\Auth\OpenIDConnect\Configuration;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Vairogs\Auth\OpenIDConnect\Exception\OpenIDConnectException;
use function array_merge;
use function http_build_query;
use function implode;
use function rtrim;
use function sprintf;
use function urldecode;

class Uri
{
    protected array $params = [];
    protected array $urlParams = [];
    protected string $url;
    protected string $base;
    protected ?string $basePost;

    public function __construct(array $options, array $extra = [], protected bool $useSession = false, protected string $method = Request::METHOD_POST, protected ?SessionInterface $session = null)
    {
        $this->base = rtrim(string: $extra['base_uri'], characters: '/') . '/';
        $this->basePost = null !== ($extra['base_uri_post'] ?? null) ? rtrim(string: $extra['base_uri_post'], characters: '/') . '/' : null;
        unset($extra['base_uri'], $extra['base_uri_post']);

        $this->params = !empty($options['params']) ? $options['params'] : [];

        $this->setGetParams(options: $options, additional: $extra);
    }

    /**
     * @throws OpenIDConnectException
     */
    public function redirect(): Response
    {
        return new RedirectResponse(url: $this->getUrl());
    }

    /**
     * @throws OpenIDConnectException
     */
    public function getUrl($language = null): string
    {
        $this->buildUrl(language: $language);

        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function addParam($value): void
    {
        $this->params[] = $value;
    }

    public function addUrlParam($name, $value): void
    {
        $this->urlParams[$name] = $value;
    }

    public function getBase(): string
    {
        return $this->base;
    }

    public function getBasePost(): ?string
    {
        return $this->basePost;
    }

    /**
     * @throws OpenIDConnectException
     */
    protected function buildUrl($language = null): void
    {
        $this->setIdToken();

        if (null !== $language) {
            $this->urlParams['lang'] = (string) $language;
        }

        $clientUrl = $this->base;
        if (Request::METHOD_POST === $this->method && null !== $this->basePost) {
            $clientUrl = $this->basePost;
        }

        if (!empty($this->params)) {
            $clientUrl .= implode(separator: '/', array: $this->params);
        }

        if (!empty($this->urlParams)) {
            $clientUrl .= '?' . http_build_query(data: $this->urlParams);
        }

        $this->setUrl(url: urldecode(string: $clientUrl));
    }

    private function setGetParams($options, $additional): void
    {
        if (Request::METHOD_GET === $this->method) {
            if (isset($options['url_params']['post_logout_redirect_uri'])) {
                $options['url_params']['post_logout_redirect_uri'] = $additional['redirect_uri'];
                unset($additional['redirect_uri']);
            }
            $this->urlParams = !empty($options['url_params']) ? array_merge($options['url_params'], $additional) : $additional;
        }
    }

    /**
     * @throws OpenIDConnectException
     */
    private function setIdToken(): void
    {
        if (Request::METHOD_GET === $this->method && isset($this->urlParams['id_token_hint']) && null !== $this->session && $this->session->has(name: 'id_token')) {
            if (!$this->useSession) {
                throw new OpenIDConnectException(message: sprintf('"%s" parameter must be set to "true" in order to use id_token_hint', 'use_session'));
            }
            $this->urlParams['id_token_hint'] = $this->session->get(name: 'id_token');
        }
    }
}
