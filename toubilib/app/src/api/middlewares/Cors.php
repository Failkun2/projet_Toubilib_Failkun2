<?php

namespace toubilib\api\middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteContext;

class Cors {
    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $requestHeaders = $request->getHeaderLine('Access-Control-Request-Headers');

        $origin = $request->hasHeader('Origin') ? $request->getHeaderLine('Origin') : '*';

        $response = $handler->handle($request);

        return $response
            ->withHeader('Access-Control-Allow-Origin', $origin)
            ->withHeader('Access-Control-Allow-Headers', $requestHeaders)
            ->withHeader('Access-Control-Allow-Credentials', 'true');
    }

}