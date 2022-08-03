<?php

declare(strict_types=1);

namespace Tests\Etrias\TrueConnector\Api;

use Etrias\TrueConnector\Api\ApiConfig;
use Etrias\TrueConnector\Api\CertificatesApi;
use Etrias\TrueConnector\HttpClient\Plugin\Authentication;
use Etrias\TrueConnector\HttpClient\Plugin\ErrorHandler;
use GuzzleHttp\Client;
use Http\Client\Common\HttpMethodsClient;
use Http\Client\Common\Plugin\BaseUriPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Http\Client\Common\PluginClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use JMS\Serializer\SerializerBuilder;
use kamermans\OAuth2\GrantType\ClientCredentials;
use PHPUnit\Framework\TestCase;

abstract class ApiTestCase extends TestCase
{
    protected CertificatesApi $certificatesApi;

    protected function setUp(): void
    {
        $serializer = SerializerBuilder::create()
            ->setCacheDir(sys_get_temp_dir().'/etrias/true-connector/jms-cache')
            ->addMetadataDir(__DIR__.'/../../../src/Serializer/Metadata', 'Etrias\TrueConnector')
            ->addDefaultDeserializationVisitors()
            ->addDefaultSerializationVisitors()
            ->addDefaultHandlers()
            ->build()
        ;
        $client = new HttpMethodsClient(
            new PluginClient(HttpClientDiscovery::find(), [
                new ErrorHandler(),
                new BaseUriPlugin(Psr17FactoryDiscovery::findUrlFactory()->createUri(getenv('TRUE_API_SSL_BASE_URI'))),
                new HeaderDefaultsPlugin([
                    'accept' => 'application/json',
                    'content-type' => 'application/json',
                    'x-true-customer-id' => getenv('TRUE_API_CUSTOMER_ID'),
                ]),
                new Authentication(new ClientCredentials(new Client(['base_uri' => getenv('TRUE_API_ACCOUNTS_BASE_URI').'/oauth/access-token']), [
                    'client_id' => getenv('TRUE_API_CLIENT_ID'),
                    'client_secret' => getenv('TRUE_API_CLIENT_SECRET'),
                    'scope' => explode(',', getenv('TRUE_API_SCOPES')),
                ])),
            ]),
            new GuzzleMessageFactory()
        );

        $apiConfig = new ApiConfig((int) getenv('TRUE_API_CUSTOMER_ID'));
        $this->certificatesApi = new CertificatesApi($client, $serializer, null, $apiConfig);
    }
}
