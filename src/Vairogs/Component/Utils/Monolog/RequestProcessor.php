<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Monolog;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestProcessor
{
    protected ?Request $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    public function processRecord(array $record): array
    {
        $record['extra'] = [];
        if (null !== $this->request) {
            $record['extra']['client_ip'] = $this->request->getClientIp();
            $record['extra']['client_port'] = $this->request->getPort();
            $record['extra']['uri'] = $this->request->getUri();
            $record['extra']['method'] = $this->request->getMethod();

            if (null !== $queryString = $this->request->getQueryString()) {
                $record['extra']['query_string'] = $queryString;
            }

            if ([] !== $post = $this->request->request->all()) {
                $record['extra']['POST'] = $post;
            }

            if ([] !== $get = $this->request->query->all()) {
                $record['extra']['GET'] = $get;
            }

            if ([] !== $files = $this->request->files->all()) {
                $record['extra']['FILES'] = $files;
            }
        }

        return $record;
    }
}
