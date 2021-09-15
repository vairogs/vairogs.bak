<?php declare(strict_types = 1);

namespace Vairogs\Component\Auth\OpenIDConnect\Configuration;

use ErrorException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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

    public function __construct(array $options, array $additional = [], protected bool $useSession = false, protected string $method = Request::METHOD_POST, protected ?SessionInterface $session = null)
    {
        $this->base = rtrim(string: $additional['base_uri'], characters: '/') . '/';
        $this->basePost = null !== ($additional['base_uri_post'] ?? null) ? rtrim(string: $additional['base_uri_post'], characters: '/') . '/' : null;
        unset($additional['base_uri'], $additional['base_uri_post']);

        $this->params = !empty($options['params']) ? $options['params'] : [];

        $this->setGetParams(options: $options, additional: $additional);
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
     * @throws ErrorException
     */
    public function redirect(): Response
    {
        return new RedirectResponse(url: $this->getUrl());
    }

    /**
     * @throws ErrorException
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

    /**
     * @throws ErrorException
     */
    protected function buildUrl($language = null): void
    {
        $this->setIdToken();

        if (null !== $language) {
            $this->urlParams['lang'] = (string) $language;
        }

        $url = $this->base;
        if (Request::METHOD_POST === $this->method && null !== $this->basePost) {
            $url = $this->basePost;
        }

        if (!empty($this->params)) {
            $url .= implode(separator: '/', array: $this->params);
        }

        if (!empty($this->urlParams)) {
            $params = http_build_query(data: $this->urlParams);
            $url .= '?' . $params;
        }

        $url = urldecode(string: $url);
        $this->setUrl(url: $url);
    }

    /**
     * @throws ErrorException
     */
    private function setIdToken(): void
    {
        if (Request::METHOD_GET === $this->method && isset($this->urlParams['id_token_hint']) && null !== $this->session && $this->session->has(name: 'id_token')) {
            if (false === $this->useSession) {
                throw new ErrorException(message: sprintf('"%s" parameter must be set to "true" in order to use id_token_hint', 'use_session'));
            }
            $this->urlParams['id_token_hint'] = $this->session->get('id_token');
        }
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
}
