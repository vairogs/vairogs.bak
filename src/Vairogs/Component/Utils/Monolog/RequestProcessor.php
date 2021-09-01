<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Monolog;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestProcessor
{
    private const EXTRA = 'extra';

    protected ?Request $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    public function processRecord(array $record): array
    {
        $record[self::EXTRA] = [];

        if (null !== $this->request) {
            $record[self::EXTRA]['client_ip'] = $this->request->getClientIp();
            $record[self::EXTRA]['client_port'] = $this->request->getPort();
            $record[self::EXTRA]['uri'] = $this->request->getUri();
            $record[self::EXTRA]['method'] = $this->request->getMethod();

            if (null !== $queryString = $this->request->getQueryString()) {
                $record[self::EXTRA]['query_string'] = $queryString;
            }

            if ([] !== $post = $this->request->request->all()) {
                $record[self::EXTRA][Request::METHOD_POST] = $post;
            }

            if ([] !== $get = $this->request->query->all()) {
                $record[self::EXTRA][Request::METHOD_GET] = $get;
            }

            if ([] !== $files = $this->request->files->all()) {
                $record[self::EXTRA]['FILES'] = $files;
            }
        }

        return $record;
    }
}
