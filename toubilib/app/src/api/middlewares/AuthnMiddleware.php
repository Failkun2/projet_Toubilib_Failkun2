<?php

namespace toubilib\api\middlewares;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\api\provider\JWTAuthnProvider as JWTAuthnProvider;

class AuthnMiddleware implements MiddlewareInterface{

    private JWTAuthnProvider $authnProvider;

    public function __construct(JWTAuthnProvider $authnProvider){
        $this->authnProvider = $authnProvider;
    }
    
    public function __invoke(ServerRequestInterface $rq, RequestHandlerInterface $rh) : Response{
        $header = $rq->getHeaderLine('Authorization');
        if(!$header || !str_starts_with($header, 'Bearer ')){
            return new Response(401, ['Content-type' => 'application/json'], json_encode(['erreur' => 'Token manquant']));
        }

        $token = substr($header, 7);
        $profil = $this->authnProvider->verifierToken($token);
        if(!$profil){
            return new Response(403, ['Content-type' => 'application/json'], json_encode(['erreur' => 'Token invalide']));
        }

        return $rh->handle($rq->withAttribute('profile', $profil));
    }
}