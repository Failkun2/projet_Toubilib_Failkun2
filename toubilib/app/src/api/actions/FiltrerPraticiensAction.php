<?php

namespace toubilib\api\actions;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\api\actions\AbstractAction as AbstractAction;
use toubilib\core\application\ports\ServicePraticienInterface as ServicePraticienInterface;

class FiltrerPraticiensAction extends AbstractAction{

    private ServicePraticienInterface $service;

    public function __construct(ServicePraticienInterface $service){
        $this->service = $service;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args) : ResponseInterface{
        $query = $rq->getQueryParams();
        $specialite = $query['specialite'] ?? null;
        $ville = $query['ville'] ?? null;

        if($ville === null && $specialite === null){
            $json = json_encode(['erreur' => 'filtres manquants'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $rs->getBody()->write($json);
            return $rs->withHeader('Content-type', 'application/json')->withStatus(400);
        }

        if($ville === null){
            $praticiens = $this->service->filtrerParSpecialite($specialite);
        } elseif ($specialite === null){
            $praticiens = $this->service->filtrerParVille($ville);
        } else {
            $praticiens = $this->service->filtrerParSpecialiteVille($specialite, $ville);
        }
        
        $body = [
            'praticiens' => $praticiens,
            '_links' => [
                'self' => ['href' => '/praticiens/filtrer'],
                'liste complete' => ['href' => '/praticiens'],
            ]
        ];
        $json = json_encode($body, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $rs->getBody()->write($json);
        return $rs->withHeader('Content-type', 'application/json')->withStatus(200);
    }
}