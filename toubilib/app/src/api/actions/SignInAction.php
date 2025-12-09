<?php

namespace toubilib\api\actions;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\api\actions\AbstractAction as AbstractAction;
use toubilib\api\provider\JWTAuthnProvider as JWTAuthnProvider;
use toubilib\core\application\ports\api\dtos\CredentialsDTO as CredentialsDTO;

Class SignInAction extends AbstractAction{
    
    private JWTAuthnProvider $authnProvider;

    public function __construct(JWTAuthnProvider $authnProvider){
        $this->authnProvider = $authnProvider;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args) : ResponseInterface{
        $data = $rq->getParsedBody();
        if(empty($data['email']) || empty($data['password'])){
            $rs->getBody()->write(json_encode(['erreur' => 'email ou mot de passe manquant'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            return $rs->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $dto = new CredentialsDTO($data['email'], $data['password']);
        $authn = $this->authnProvider->signIn($dto);

        if(!$authn){
            $rs->getBody()->write(json_encode(['erreur' => 'identifiants invalides'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            return $rs->withHeader('Content-Type', 'application/json')->withStatus(401);
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
                'self' => ['href' => "/auth/signin", 'method' => 'POST'],
                'refresh' => ['href' => "/auth/refresh", 'method' => 'POST'],
            ]
        ];
        $json = json_encode($body, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $rs->getBody()->write($json);
        return $rs->withHeader('Content-type', 'application/json')->withStatus(200);
    }
}