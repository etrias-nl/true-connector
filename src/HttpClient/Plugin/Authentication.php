<?php

declare(strict_types=1);

namespace Etrias\TrueConnector\HttpClient\Plugin;

use Http\Client\Common\Plugin;
use Http\Promise\Promise;
use kamermans\OAuth2\OAuth2Handler;
use Psr\Http\Message\RequestInterface;

class Authentication extends OAuth2Handler implements Plugin
{
    public function handleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        /** @var RequestInterface $request */
        $request = $this->signRequest($request);

        return $next($request);
    }
}
