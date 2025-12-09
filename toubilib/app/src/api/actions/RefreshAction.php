<?php

namespace toubilib\api\actions;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\api\actions\AbstractAction as AbstractAction;
use toubilib\api\provider\JWTAuthnProvider as JWTAuthnProvider;

Class RefreshAction extends AbstractAction{
    
    private JWTAuthnProvider $authnProvider;

    public function __construct(JWTAuthnProvider $authnProvider){
        $this->authnProvider = $authnProvider;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args) : ResponseInterface{
        $data = json_decode($rq->getBody()->getContents(), true);
        $token = $data['refreshToken'] ?? null;
        if(!$token){
            $rs->getBody()->write(json_encode(['erreur' => 'refresh token manquant'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            return $rs->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $authn = $this->authnProvider->refresh($token);

        if(!$authn){
            $rs->getBody()->write(json_encode(['erreur' => 'token invalide ou expiré'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            return $rs->withHeader('Content-Type', 'application/json')->withStatus(403);
        }

        $body = [
            'profile' => [
                'id' => $authn->__get('profil')->__get('id'),
                'email' => $authn->__get('profil')->__get('email'),
                'role' => $authn->__get('profil')->__get('role')
            ],
            'accessToken' => $authn->__get('accessToken'),
            'refreshToken' => $authn->__get('refreshToken'),
            '_links' => [
                'self' => ['href' => "/auth/refresh", 'method' => 'POST'],
            ]
        ];
        $json = json_encode($body, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $rs->getBody()->write($json);
        return $rs->withHeader('Content-type', 'application/json')->withStatus(200);
    }
}