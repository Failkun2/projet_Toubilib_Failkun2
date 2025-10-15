<?php

namespace toubilib\api\actions;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\api\actions\AbstractAction as AbstractAction;
use toubilib\core\application\ports\ServiceRendezVousInterface as ServiceRendezVousInterface;
use toubilib\core\application\exceptions\RendezVousInvalideException as RendezVousInvalideException;
use toubilib\core\application\exceptions\RendezVousIntrouvableException as RendezVousIntrouvableException;

class ConsulterAgendaAction extends AbstractAction{

    private ServiceRendezVousInterface $service;

    public function __construct(ServiceRendezVousInterface $service){
        $this->service = $service;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args) : ResponseInterface{
        $praticienId = $args['id'] ?? null;
        $query = $rq->getQueryParams();
        if(!$praticienId){
            return $this->jsonResponse($rs, ['erreur' => 'ID praticien manquant'], 400);
        }
        try{
            $debut = isset($query['debut']) ? new \DateTimeImmutable($query['debut']) : null;
            $fin = isset($query['fin']) ? new \DateTimeImmutable($query['fin']) : null;
        }
        $agenda = $this->service->consulterAgenda($praticienId, $debut, $fin);
        $body = [
            'praticienId' => $praticienId,
            'agenda' => $agenda,
            '_links' => [
                'self' => ['href' => "/praticiens/$praticienId/agenda"],
                'praticien' => ['href' => "/praticiens/$praticienId"],
                'rdvs' => ['href' => "/praticiens/$praticienId/rdvs"]
            ]
        ];
        $rs->getBody()->write(json_encode($body));
        return $rs->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}