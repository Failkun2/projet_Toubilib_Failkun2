<?php

namespace toubilib\api\actions;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\api\actions\AbstractAction as AbstractAction;
use toubilib\core\application\ports\ConsulterRendezVousServiceInterface as ConsulterRendezVousServiceInterface;

class RendezVousByIdAction extends AbstractAction{

    private ConsulterRendezVousServiceInterface $service;

    public function __construct(ConsulterRendezVousServiceInterface $service){
        $this->service = $service;
    }

    public function __invoke(ServerRequestInterface $rq, Response $rs, array $args) : Response{
        $id = $args['id'] ?? null;
        if(!$id){
            $json = json_encode(['erreur' => 'id de rdv manquant'], JSON_PRETTY_PRINT);
            $rs->getBody()->write($json);
            return $rs->withHeader('Content-type', 'application/json')->withStatus(400);
        }
        try{
            $rdv = $this->service->afficherRendezVous($id);
            $body = [
                'rdv' => $rdv,
                '_links' => [
                    'self' => ['href' => "/rdvs/{$id}"],
                    'annuler' => ['href' => "/rdvs/{$id}/annuler", 'method' => 'PATCH'],
                    'praticien' => ['href' => "/praticiens/{$rdv['idPraticien']}"]
                ]
            ];
            $json = json_encode($body, JSON_PRETTY_PRINT);
            $rs->getBody()->write($json);
            return $rs->withHeader('Content-type', 'application/json')->withStatus(200);
        } catch(\Throwable $e){
            $json = json_encode(['erreur' => $e->getMessage()], JSON_PRETTY_PRINT);
            $rs->getBody()->write($json);
            return $rs->withHeader('Content-type', 'application/json')->withStatus(404);
        }
    }
}