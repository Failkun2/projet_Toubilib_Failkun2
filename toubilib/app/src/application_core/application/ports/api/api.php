<?php

use toubilib\core\application\ports\ServicePraticienInterface as ServicePraticienInterface;
use toubilib\core\application\ports\ConsulterPraticienServiceInterface as ConsulterPraticienServiceInterface;
use toubilib\core\application\ports\ServiceRendezVousInterface as ServiceRendezVousInterface;
use toubilib\core\application\ports\ConsulterRendezVousServiceInterface as ConsulterRendezVousServiceInterface;
use toubilib\core\application\ports\AuthnServiceInterface as AuthnServiceInterface;
use toubilib\api\actions\ListerPraticiensAction as ListerPraticiensAction;
use toubilib\api\actions\PraticienByIdAction as PraticienByIdAction;
use toubilib\api\actions\PraticienRDVAction as PraticienRDVAction;
use toubilib\api\actions\RendezVousByIdAction as RendezVousByIdAction;
use toubilib\api\actions\CreateRendezVousAction as CreateRendezVousAction;
use toubilib\api\actions\AnnulerRendezVousAction as AnnulerRendezVousAction;
use toubilib\api\actions\ConsulterAgendaAction as ConsulterAgendaAction;
use toubilib\api\actions\SignInAction as SignInAction;
use toubilib\api\actions\RefreshAction as RefreshAction;
use Psr\Container\ContainerInterface;
use toubilib\api\provider\JWTAuthnProvider as JWTAuthnProvider;

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
    SignInAction::class=> function (ContainerInterface $c) {
        return new SignInAction($c->get(JWTAuthnProvider::class));
    },
    RefreshAction::class=> function (ContainerInterface $c) {
        return new RefreshAction($c->get(JWTAuthnProvider::class));
    },
];