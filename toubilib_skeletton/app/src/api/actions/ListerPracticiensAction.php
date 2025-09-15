<?php

namespace toubilib\api\actions;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\api\actions\AbstractAction as AbstractAction;
use toubilib\core\domain\entities\praticien\ServicePracticienInterface as ServicePracticienInterface;

class ListerPracticiensAction extends AbstractAction{

    private ServicePracticienInterface $service;

    public function __construct(ServicePracticienInterface $service){
        $this->service = $service;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args) : ResponseInterface{
        $practiciens = $this->service->listerPracticiens();
        $json = json_encode($practiciens, JSON_PRETTY_PRINT);
        $rs->getBody()->write($json);
        return $rs->withHeader('Content-type', 'application/json')->withStatus(200);
    }
}