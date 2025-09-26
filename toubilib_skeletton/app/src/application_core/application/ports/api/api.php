<?php

use toubilib\core\domain\entities\ServicePraticienInterface as ServicePraticienInterface;
use toubilib\core\domain\entities\ConsulterPraticienServiceInterface as ConsulterPraticienServiceInterface;
use toubilib\core\domain\entities\ServiceRendezVousInterface as ServiceRendezVousInterface;
use toubilib\core\domain\entities\ConsulterRendezVousServiceInterface as ConsulterRendezVousServiceInterface;
use toubilib\api\actions\ListerPraticiensAction as ListerPraticiensAction;
use toubilib\api\actions\PraticienByIdAction as PraticienByIdAction;
use toubilib\api\actions\PraticienRDVAction as PraticienRDVAction;
use toubilib\api\actions\RendezVousByIdAction as RendezVousByIdAction;
use toubilib\api\actions\CreateRendezVousAction as CreateRendezVousAction;
use toubilib\api\middlewares\AnnulerRendezVousAction as AnnulerRendezVousAction;
use toubilib\api\middlewares\ConsulterAgendaAction as ConsulterAgendaAction;
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
    CreateRendezVousAction::class=> function (ContainerInterface $c) {
        return new CreateRendezVousAction($c->get(ServiceRendezVousInterface::class));
    },
    AnnulerRendezVousAction::class=> function (ContainerInterface $c) {
        return new AnnulerRendezVousAction($c->get(ServiceRendezVousInterface::class));
    },
    ConsulterAgendaAction::class=> function (ContainerInterface $c) {
        return new ConsulterAgendaAction($c->get(ServiceRendezVousInterface::class));
    },
];