<?php

namespace toubilib\api\actions;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\api\actions\AbstractAction as AbstractAction;
use toubilib\core\domain\entities\praticien\ServiceRendezVousInterface as ServiceRendezVousInterface;
use toubilib\core\application\exceptions\ValidationException as ValidationException;

class CreateRendezVousAction extends AbstractAction{

    private ServiceRendezVousInterface $service;

    public function __construct(ServiceRendezVousInterface $service){
        $this->service = $service;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args) : ResponseInterface{
        $dto = $rq->getAttribute('inputRdv');
        if(!$dto){
            return new Response(400, [], json_encode(['erreur' => 'données manquantes']));
        }
        try{
            $newId = $this->service->creerRendezVous($dto);
        }catch(ValidationException $ve){
            return new Response(422, ['Content-Type' => 'application/json'], json_encode(['erreur' => $ve->getErrors()]));
        }catch(\Throwable $t){
            return new Response(500, ['Content-Type' => 'application/json'], json_encode(['erreur' => 'erreur serveur']));
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

        return new Response(201, ['Content-Type' => 'application/json', 'Location' => $location], json_encode($payload));
    }
}