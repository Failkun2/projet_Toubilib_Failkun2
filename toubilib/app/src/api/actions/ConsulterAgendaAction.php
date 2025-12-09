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
            $rs->getBody()->write(json_encode(['erreur' => 'ID praticien manquant'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            return $rs->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
        try{
            $debut = new \DateTimeImmutable($query['debut']);
            $fin = new \DateTimeImmutable($query['fin']);
        } catch(\Exception $e){
            $json = json_encode(['erreur' => 'dates invalides'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $rs->getBody()->write($json);
            return $rs->withHeader('Content-type', 'application/json')->withStatus(400);
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
        $rs->getBody()->write(json_encode($body, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        return $rs->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}