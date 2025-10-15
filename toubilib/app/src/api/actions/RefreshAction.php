<?php

namespace toubilib\api\actions;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\api\actions\AbstractAction as AbstractAction;
use toubilib\api\providers\JWTAuthnProvider as JWTAuthnProvider;

Class RefreshAction extends AbstractAction{
    
    private JWTAuthnProvider $authnProvider;

    public function __construct(JWTAuthnProvider $authnProvider){
        $this->authnProvider = $authnProvider
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args) : ResponseInterface{
        $data = json_decode($rq->getBody()->getContents(), true);
        $token = $data['refreshToken'] ?? null;
        if(!$token){
            return new Response(400, ['Content-type' => 'application/json'], json_encode(['erreur' => 'refresh token manquant']));
        }

        $authn = $this->authnProvider->refresh($token);

        if(!$authn){
            return new Response(403, ['Content-type' => 'application/json'], json_encode(['erreur' => 'token invalide ou expiré']));            
        }

        $body = [
            'profile' => [
                'id' => $authn->__get('profil')->__get('id'),
                'email' => $authn->__get('profil')->__get('email')
                'role' => $authn->__get('profil')->__get('role')
            ],
            'accessToken' => $authn->__get('accessToken'),
            'refreshToken' => $authn->__get('refreshToken'),
            '_links' => [
                'self' => ['href' => "/auth/refresh", 'method' => 'POST'],
                'profile' => ['href' => "/users/{$authn->__get('profil')->__get('id')}", 'method' => 'GET'],
                'rdv' => ['href' => "/auth/logout", 'method' => 'POST']
            ]
        ];
        $json = json_encode($body, JSON_PRETTY_PRINT);
        $rs->getBody()->write($json);
        return $rs->withHeader('Content-type', 'application/json')->withStatus(200);
    }
}