<?php

declare(strict_types=1);

namespace Etrias\TrueConnector\HttpClient\Plugin;

use Etrias\TrueConnector\Exception\TrueException;
use Http\Client\Common\Plugin;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ErrorHandler implements Plugin
{
    public function handleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        return $next($request)->then(function (ResponseInterface $response) {
            $status = $response->getStatusCode();

            if ($status >= 400) {
                if (!preg_match('/\bjson\b/i', $response->getHeaderLine('Content-Type'))) {
                    throw new TrueException($body, $status);
                }

                try {
                    $data = \GuzzleHttp\json_decode($body, true);
                } catch (\Throwable $e) {
                    $data = [];
                }

                throw new TrueException($data['message'] ?? $body, $status);
            }

            return $response;
        });
    }
}
