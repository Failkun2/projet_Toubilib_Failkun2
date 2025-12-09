<?php

namespace toubilib\api\actions;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\api\actions\AbstractAction as AbstractAction;
use toubilib\core\application\ports\ServiceRendezVousInterface as ServiceRendezVousInterface;
use toubilib\core\application\exceptions\RendezVousInvalideException as RendezVousInvalideException;
use toubilib\core\application\exceptions\RendezVousIntrouvableException as RendezVousIntrouvableException;

class ConsulterHistoriqueAction extends AbstractAction{

    private ServiceRendezVousInterface $service;

    public function __construct(ServiceRendezVousInterface $service){
        $this->service = $service;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args) : ResponseInterface{
        $patientId = $args['id'] ?? null;
        if(!$patientId){
            $rs->getBody()->write(json_encode(['erreur' => 'ID patient manquant'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            return $rs->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $historique = $this->service->consulterHistorique($patientId);
        $body = [
            'patientId' => $patientId,
            'historique' => $historique,
            '_links' => [
                'self' => ['href' => "/patients/$patientId/historique"],
            ]
        ];
        $rs->getBody()->write(json_encode($body, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        return $rs->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}