<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\Monolog;

use Symfony\Component\HttpFoundation\RequestStack;

class RequestProcessor
{
    /**
     * @param RequestStack $requestStack
     */
    public function __construct(protected RequestStack $requestStack)
    {
    }

    /**
     * @param array $record
     * @return array
     */
    public function processRecord(array $record): array
    {
        $record['extra'] = [];
        if (null !== $request = $this->requestStack->getCurrentRequest()) {
            $record['extra']['client_ip'] = $request->getClientIp();
            $record['extra']['client_port'] = $request->getPort();
            $record['extra']['uri'] = $request->getUri();
            $record['extra']['method'] = $request->getMethod();

            if (null !== $string = $request->getQueryString()) {
                $record['extra']['query_string'] = $string;
            }

            if ([] !== $post = $request->request->all()) {
                $record['extra']['POST'] = $post;
            }

            if ([] !== $get = $request->query->all()) {
                $record['extra']['GET'] = $get;
            }

            if ([] !== $files = $request->files->all()) {
                $record['extra']['FILES'] = $files;
            }
        }

        return $record;
    }
}
