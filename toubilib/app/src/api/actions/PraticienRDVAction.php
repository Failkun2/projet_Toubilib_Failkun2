<?php

namespace toubilib\api\actions;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\api\actions\AbstractAction as AbstractAction;
use toubilib\core\domain\entities\praticien\ServiceRendezVousInterface as ServiceRendezVousInterface;

class PraticienRDVAction extends AbstractAction{

    private ConsulterPraticienServiceInterface $service;

    public function __construct(ConsulterPraticienServiceInterface $service){
        $this->service = $service;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args) : ResponseInterface{
        $id = $args['id'] ?? null;
        $query = $rq->getQueryParams();
        if(!$id || !isset($query['debut']) || !isset($query['fin'])){
            return new Response(400, [], json_encode(['erreur' => 'parametre manquant']));
        }
        try {
            $debut = new \DateTimeImmutable($query['debut']);
            $fin = new \DateTimeImmutable($query['fin']);
        } catch(\Exception $e){
            return new Response(400, [], json_encode(['erreur' => 'dates invalides']));
        }
        $rdvs = $this->service->listerCrenaux($id, $debut, $fin);
        $json = json_encode($rdvs->toArray(), JSON_PRETTY_PRINT);
        $rs->getBody()->write($json);
        return $rs->withHeader('Content-type', 'application/json')->withStatus(200);
    }
}