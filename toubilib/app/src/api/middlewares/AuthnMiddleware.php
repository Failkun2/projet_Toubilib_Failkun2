<?php

namespace toubilib\api\middlewares;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\api\provider\JWTAuthnProvider as JWTAuthnProvider;
use Slim\Psr7\Response;

class AuthnMiddleware implements MiddlewareInterface{

    private JWTAuthnProvider $authnProvider;

    public function __construct(JWTAuthnProvider $authnProvider){
        $this->authnProvider = $authnProvider;
    }
    
    public function process(ServerRequestInterface $rq, RequestHandlerInterface $rh) : Response{
        $header = $rq->getHeaderLine('Authorization');
        if(!$header || !str_starts_with($header, 'Bearer ')){
            $rs = new Response();
            $json = json_encode(['erreur' => 'Token manquant'], JSON_PRETTY_PRINT);
            $rs->getBody()->write($json);
            return $rs->withHeader('Content-type', 'application/json')->withStatus(401);
        }

        $token = substr($header, 7);
        $profil = $this->authnProvider->verifierToken($token);
        if(!$profil){
            $rs = new Response();
            $json = json_encode(['erreur' => 'Token invalide'], JSON_PRETTY_PRINT);
            $rs->getBody()->write($json);
            return $rs->withHeader('Content-type', 'application/json')->withStatus(403);
        }

        return $rh->handle($rq->withAttribute('profile', $profil));
    }
}