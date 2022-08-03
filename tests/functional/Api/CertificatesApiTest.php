<?php

declare(strict_types=1);

namespace Tests\Etrias\TrueConnector\Api;

use Etrias\TrueConnector\Model\CertificateRequest;
use Etrias\TrueConnector\Model\CertificatesRequest;

/**
 * @internal
 */
final class CertificatesApiTest extends ApiTestCase
{
//    public function testAll(): void
//    {
//        $request = (new CertificatesRequest());
//        $itemCount = $i = 0;
//        foreach ($this->certificatesApi->all($request, $itemCount) as $order) {
//            ++$i;
//        }
//
//        self::assertSame($itemCount, $i);
//    }

    public function testRequest(): void
    {
        $request = (new CertificateRequest())
            ->setCommonName('brandslife.nl')
            ->setHostnames(['www.brandslife.nl', 'brandslife.nl'])
        ;

        $this->certificatesApi->request($request);
    }
}
