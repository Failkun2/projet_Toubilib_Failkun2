<?php

namespace toubilib\api\actions;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\api\actions\AbstractAction as AbstractAction;
use toubilib\core\application\ports\ServiceRendezVousInterface as ServiceRendezVousInterface;

class IndisponibiliteAction extends AbstractAction{

    private ServiceRendezVousInterface $service;

    public function __construct(ServiceRendezVousInterface $service){
        $this->service = $service;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args) : ResponseInterface{
        $praticienId = $args['id'] ?? null;
        $query = $rq->getQueryParams();
        if(!$praticienId || !isset($query['debut']) || !isset($query['fin'])){
            $json = json_encode(['erreur' => 'parametre manquant'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $rs->getBody()->write($json);
            return $rs->withHeader('Content-type', 'application/json')->withStatus(400);
        }
        try {
            $debut = new \DateTimeImmutable($query['debut']);
            $fin = new \DateTimeImmutable($query['fin']);
        } catch(\Exception $e){
            $json = json_encode(['erreur' => 'dates invalides'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $rs->getBody()->write($json);
            return $rs->withHeader('Content-type', 'application/json')->withStatus(400);
        }
        $newId = $this->service->creerIndisponibilite($praticienId, $debut, $fin);
        $payload = [
            'id' => $newId,
            'message' => 'Indisponibilité créer'
        ];
        $rs->getBody()->write(json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        return $rs->withStatus(201)->withHeader('Content-Type', 'application/json');
    }
}