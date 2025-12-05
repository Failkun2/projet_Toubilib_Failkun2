<?php

namespace toubilib\api\actions;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\api\actions\AbstractAction as AbstractAction;
use toubilib\core\application\ports\ServicePatientInterface as ServicePatientInterface;
use toubilib\core\application\ports\api\dtos\CredentialsDTO as CredentialsDTO;
use toubilib\core\application\exceptions\ValidationException as ValidationException;

Class SignUpAction extends AbstractAction{
    
    private ServicePatientInterface $service;

    public function __construct(ServicePatientInterface $service){
        $this->service = $service;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args) : ResponseInterface{

        $dto = $rq->getAttribute('inputPatient');
        if(!$dto){
            $rs->getBody()->write(json_encode(['erreur' => 'données manquantes']));
            return $rs->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
        try{
            $newId = $this->service->creerPatient($dto);
        }catch(ValidationException $ve){
            $rs->getBody()->write(json_encode(['erreur' => $ve->getErrors()]));
            return $rs->withStatus(422)->withHeader('Content-Type', 'application/json');
        }catch(\Throwable $t){
            $rs->getBody()->write(json_encode(['erreur' => $t->getMessage()]));
            return $rs->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
        
        $payload = [
            'id' => $newId,
            'message' => 'Patient créer'
        ];
        $location = '/rdvs/' . $newId;
        $rs->getBody()->write(json_encode($payload));
        return $rs->withStatus(201)->withHeader('Content-Type', 'application/json')->withHeader('Location', $location);
    }
}