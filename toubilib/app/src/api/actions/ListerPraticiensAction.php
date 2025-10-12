<?php

namespace toubilib\api\actions;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\api\actions\AbstractAction as AbstractAction;
use toubilib\core\domain\entities\praticien\ServicePraticienInterface as ServicePraticienInterface;

class ListerPraticiensAction extends AbstractAction{

    private ServicePraticienInterface $service;

    public function __construct(ServicePraticienInterface $service){
        $this->service = $service;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args) : ResponseInterface{
        $praticiens = $this->service->listerPraticiens();
        $body = [
            'praticiens' => $praticiens,
            '_links' => [
                'self' => ['href' => '/praticiens'],
                'creer' => ['href' => '/rdvs', 'method' => 'POST'] 
            ]
        ];
        $json = json_encode($body, JSON_PRETTY_PRINT);
        $rs->getBody()->write($json);
        return $rs->withHeader('Content-type', 'application/json')->withStatus(200);
    }
}