<?php

namespace toubilib\api\actions;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\api\actions\AbstractAction as AbstractAction;
use toubilib\core\application\ports\ConsulterPraticienServiceInterface as ConsulterPraticienServiceInterface;

class PraticienByIdAction extends AbstractAction{

    private ConsulterPraticienServiceInterface $service;

    public function __construct(ConsulterPraticienServiceInterface $service){
        $this->service = $service;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args) : ResponseInterface{
        $id = $args['id'] ?? null;
        if(!$id){
            return new Response(400, [], json_encode(['erreur' => 'id de praticien manquant']));
        }
        $pratitien = $this->service->afficherPraticien($id);
        $body = [
            'praticien' => $pratitien->toArray(),
            '_links' => [
                'self' => ['href' => "/praticiens/{$id}"],
                'rdvs' => ['href' => "/praticiens/{$id}/rdvs"],
                'agenda' => ['href' => "/praticiens/{$id}/agenda"],
                'collection' => ['href' => '/praticiens']
            ] 
        ];
        $json = json_encode($body, JSON_PRETTY_PRINT);
        $rs->getBody()->write($json);
        return $rs->withHeader('Content-type', 'application/json')->withStatus(200);
    }
}