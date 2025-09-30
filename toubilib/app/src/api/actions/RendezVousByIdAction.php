<?php

namespace toubilib\api\actions;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\api\actions\AbstractAction as AbstractAction;
use toubilib\core\domain\entities\praticien\ConsulterRendezVousServiceInterface as ConsulterRendezVousServiceInterface;

class RendezVousByIdAction extends AbstractAction{

    private ConsulterRendezVousServiceInterface $service;

    public function __construct(ConsulterRendezVousServiceInterface $service){
        $this->service = $service;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args) : ResponseInterface{
        $id = $args['id'] ?? null;
        if(!$id){
            return new Response(400, [], json_encode(['erreur' => 'id de rdv manquant']));
        }
        $rdv = $this->service->afficherRendezVous($id);
        $json = json_encode($rdv->toArray(), JSON_PRETTY_PRINT);
        $rs->getBody()->write($json);
        return $rs->withHeader('Content-type', 'application/json')->withStatus(200);
    }
}