<?php

namespace toubilib\api\actions;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\api\actions\AbstractAction as AbstractAction;
use toubilib\core\application\ports\ServiceRendezVousInterface as ServiceRendezVousInterface;
use toubilib\core\application\exceptions\ValidationException as ValidationException;

class CreateRendezVousAction extends AbstractAction{

    private ServiceRendezVousInterface $service;

    public function __construct(ServiceRendezVousInterface $service){
        $this->service = $service;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args) : ResponseInterface{
        $dto = $rq->getAttribute('inputRdv');
        if(!$dto){
            $rs->getBody()->write(json_encode(['erreur' => 'données manquantes'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            return $rs->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
        try{
            $newId = $this->service->creerRendezVous($dto);
        }catch(ValidationException $ve){
            $rs->getBody()->write(json_encode(['erreur' => $ve->getErrors()], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            return $rs->withStatus(422)->withHeader('Content-Type', 'application/json');
        }catch(\Throwable $t){
            $rs->getBody()->write(json_encode(['erreur' => $t->getMessage()], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            return $rs->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
        
        $payload = [
            'id' => $newId,
            'message' => 'Rendez-vous créer',
            '_links' => [
                'self' => ['href' => "/rdvs/$newId"],
                'annuler' => ['href' => "/rdvs/$newId/annuler", 'method' => 'PATCH'],
                'praticiens' => ['href' => '/praticiens']
            ]
        ];
        $location = '/rdvs/' . $newId;
        $rs->getBody()->write(json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        return $rs->withStatus(201)->withHeader('Content-Type', 'application/json')->withHeader('Location', $location);
    }
}