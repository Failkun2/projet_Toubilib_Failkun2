<?php

namespace toubilib\api\middlewares;

use Psr\Server\MiddlewareInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHanderInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\core\application\ports\api\dtos\InputRendezVousDTO as InputRendezVousDTO;


class CreateRdvMiddleware extends MiddlewareInterface{
    public function process(ServerRequestInterface $rq, RequestHanderInterface $rh) : ResponseInterface{
        $contentType = $rq->getHeaderLine('Content-Type');
        $body = (String)$rq->getBody();

        $data = [];
        if(str_contains($contentType, 'application/json')){
            $data = json_decode($body, true);
            if($data === null){
                return new Response(400, ['Content-Type' => 'application/json'], json_encode(['erreur' => 'JSON invalide']));
            }
        } else {
            $data = $rq->getParsedBody() ?? [];
        }

        $erreurs = [];

        $required = ['praticienId' => 'string', 'patientId' => 'string', 'dateDebut' => 'datetime', 'dateFin' => 'datetime', 'duree' => 'int', 'motifVisite' => 'string'];
        foreach($required as $r => $type){
            if(!isset($data[$r])){
                $erreurs[$r] = 'Champ requis';
            }
            $valeur = $data[$r];

            switch($type){
                case 'string':
                    if (trim((String)$valeur) === ''){
                        $erreurs[$r] = 'Contenu manquant';
                    }
                    break;
                case 'int':
                    if (!is_numeric($valeur)){
                        $erreurs[$r] = 'Pas un Entier';
                    }
                    break;
                case 'datetime':
                    if(!$valeur instanceof \DateTimeImmutable){
                        try{
                            new \DateTimeImmutable($valeur);
                        } catch(\Exception $e){
                            $erreurs[$r] = 'Date Invalide';
                        }
                    }
                    break;
                default:
                    break;
            }
        }
        
        if(!empty($erreurs)){
            return new Response(422, ['Content-Type' => 'application/json'], json_encode(['erreurs' => $erreurs]));
        }

        $dto = new InputRendezVousDTO(
            (String)$data['praticienId'],
            (String)$data['patientId'],
            new \DateTimeImmutable($data['dateDebut']),
            (int)$data['duree'],
            (String)$data['motifVisite']
        );
        $rq = $rq->withAttribut('inputRdv', $dto);
        return $rh->handle($rq);
    }
}