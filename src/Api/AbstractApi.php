<?php

declare(strict_types=1);

namespace Etrias\TrueConnector\Api;

use Etrias\TrueConnector\Model\CustomerAwareRequest;
use Etrias\TrueConnector\Model\RequestInterface;
use GuzzleHttp\Psr7\Uri;
use Http\Client\Common\HttpMethodsClientInterface;
use Http\Discovery\Psr17FactoryDiscovery;
use JMS\Serializer\SerializerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

abstract class AbstractApi
{
    protected const JSON_FORMAT = 'json';

    protected HttpMethodsClientInterface $client;
    protected SerializerInterface $serializer;
    protected UriFactoryInterface $uriFactory;
    protected ApiConfig $apiConfig;

    public function __construct(
        HttpMethodsClientInterface $client,
        SerializerInterface $serializer,
        ?UriFactoryInterface $uriFactory = null,
        ?ApiConfig $apiConfig = null
    ) {
        $this->client = $client;
        $this->serializer = $serializer;
        $this->uriFactory = $uriFactory ?? Psr17FactoryDiscovery::findUrlFactory();
        $this->apiConfig = $apiConfig ?? new ApiConfig();
    }

    protected function postJson(UriInterface $uri, $data): ResponseInterface
    {
        return $this->client->post($uri, [], $this->serializer->serialize($data, self::JSON_FORMAT));
    }

    protected function patchJson(UriInterface $uri, $data): ResponseInterface
    {
        return $this->client->patch($uri, [], $this->serializer->serialize($data, self::JSON_FORMAT));
    }

    protected function putJson(UriInterface $uri, $data): ResponseInterface
    {
        return $this->client->put($uri, [], $this->serializer->serialize($data, self::JSON_FORMAT));
    }

    protected function getJson(UriInterface $uri): ResponseInterface
    {
        return $this->client->get($uri);
    }

    protected function addGlobalConfig(RequestInterface $request): RequestInterface
    {
        if ($request instanceof CustomerAwareRequest && null === $request->getCustomerId()) {
            $request->setCustomerId($this->apiConfig->getCustomerId());
        }

        return $request;
    }

    protected function deserialize(ResponseInterface $response, string $type): object
    {
        return $this->serializer->deserialize((string) $response->getBody(), $type, self::JSON_FORMAT);
    }

    /**
     * @return object[]
     */
    protected function deserializeList(ResponseInterface $response, string $type): array
    {
        return $this->serializer->deserialize((string) $response->getBody(), 'array<'.$type.'>', self::JSON_FORMAT);
    }

    protected function createUri(string $template, array $variables = [], array $query = []): UriInterface
    {
        foreach ($query as $key => $value) {
            if (null === $value) {
                unset($query[$key]);
            } elseif (\is_bool($value)) {
                $query[$key] = $value ? '1' : '0';
            } elseif ($value instanceof \DateTimeInterface) {
                $query[$key] = $value->format(\DateTimeInterface::W3C);
            } elseif (\is_array($value)) {
                $query[$key] = implode(',', $value);
            }
        }

        return Uri::withQueryValues($this->uriFactory->createUri(\GuzzleHttp\uri_template($template, $variables)), $query);
    }
}
