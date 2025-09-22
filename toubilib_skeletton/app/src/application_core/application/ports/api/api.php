<?php

use toubilib\core\domain\entities\praticien\ServicePraticienInterface as ServicePraticienInterface;
use toubilib\api\actions\ListerPraticiensAction as ListerPraticiensAction;
use Psr\Container\ContainerInterface;

return [
    ListerPraticiensAction::class=> function (ContainerInterface $c) {
        return new ListerPraticiensAction($c->get(ServicePraticienInterface::class));
    },
    PraticienByIdAction::class=> function (ContainerInterface $c) {
        return new PraticienByIdAction($c->get(ConsulterPraticienServiceInterface::class));
    },
    PraticienRDVAction::class=> function (ContainerInterface $c) {
        return new PraticienRDVAction($c->get(ServiceRendezVousInterface::class));
    },
    RendezVousByIdAction::class=> function (ContainerInterface $c) {
        return new RendezVousByIdAction($c->get(ConsulterRendezVousServiceInterface::class));
    },
];