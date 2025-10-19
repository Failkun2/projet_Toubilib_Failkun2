<?php

namespace toubilib\api\actions;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\api\actions\AbstractAction as AbstractAction;
use toubilib\core\application\ports\ServiceRendezVousInterface as ServiceRendezVousInterface;

class PraticienRDVAction extends AbstractAction{

    private ServiceRendezVousInterface $service;

    public function __construct(ServiceRendezVousInterface $service){
        $this->service = $service;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args) : ResponseInterface{
        $id = $args['id'] ?? null;
        $query = $rq->getQueryParams();
        if(!$id || !isset($query['debut']) || !isset($query['fin'])){
            $json = json_encode(['erreur' => 'parametre manquant'], JSON_PRETTY_PRINT);
            $rs->getBody()->write($json);
            return $rs->withHeader('Content-type', 'application/json')->withStatus(400);
        }
        try {
            $debut = new \DateTimeImmutable($query['debut']);
            $fin = new \DateTimeImmutable($query['fin']);
        } catch(\Exception $e){
            $json = json_encode(['erreur' => 'dates invalides'], JSON_PRETTY_PRINT);
            $rs->getBody()->write($json);
            return $rs->withHeader('Content-type', 'application/json')->withStatus(400);
        }
        $rdvs = $this->service->listerCrenaux($id, $debut, $fin);
        $body = [
            'rdvs' => $rdvs,
            '_links' => [
                'self' => ['href' => "/praticiens/{$id}/rdvs?debut={$debut->format('Y-m-d')}&fin={$fin->format('Y-m-d')}"],
                'agenda' => ['href' => "/praticiens/{$id}/agenda"],
                'praticien' => ['href' => "/praticiens/{$id}"]
            ]
        ];
        $json = json_encode($body, JSON_PRETTY_PRINT);
        $rs->getBody()->write($json);
        return $rs->withHeader('Content-type', 'application/json')->withStatus(200);
    }
}