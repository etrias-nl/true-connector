<?php

declare(strict_types=1);

namespace Etrias\TrueConnector\Api;

use Etrias\TrueConnector\Model\CertificateRequest;
use Etrias\TrueConnector\Model\CertificatesRequest;

class CertificatesApi extends AbstractApi
{
    public function all(CertificatesRequest $request = null): void
    {
        $request ??= new CertificatesRequest();

        $uri = $this->createUri('/api/certificates', [], [
            'page' => $request->getPageNumber(),
            'status' => $request->getStatus(),
            'limit' => $request->getLimit(),
            'sort' => $request->getSort(),
        ]);

        $response = $this->getJson($uri)->getBody();
    }

    public function request(CertificateRequest $request): void
    {
        $uri = $this->createUri('/api/certificates/{issuer}', [
            'issuer' => $request->getIssuer(),
        ]);

        $request = $this->addGlobalConfig($request);

        $this->postJson($uri, $request);
    }
}
