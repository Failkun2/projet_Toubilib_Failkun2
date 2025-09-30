<?php

use Psr\Container\ContainerInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\PraticienRepositoryInterface as PraticienRepositoryInterface;
use toubilib\core\domain\entities\praticien\PraticienRepository as PraticienRepository;
use toubilib\core\application\ports\spi\repositoryInterfaces\PatientRepositoryInterface as PatientRepositoryInterface;
use toubilib\core\domain\entities\praticien\PatientRepository as PatientRepository;
use toubilib\core\application\ports\spi\repositoryInterfaces\RendezVousRepositoryInterface as RendezVousRepositoryInterface;
use toubilib\core\domain\entities\praticien\RendezVousRepository as RendezVousRepository;
use toubilib\core\domain\entities\ServicePraticienInterface as ServicePraticienInterface;
use toubilib\core\application\usecases\ServicePraticien as ServicePraticien;
use toubilib\core\domain\entities\ServiceRendezVousInterface as ServiceRendezVousInterface;
use toubilib\core\application\usecases\ServiceRendezVous as ServiceRendezVous;
use toubilib\core\domain\entities\ConsulterPraticienServiceInterface as ConsulterPraticienServiceInterface;
use toubilib\core\application\usecases\ConsulterPraticienService as ConsulterPraticienService;
use toubilib\core\domain\entities\ConsulterRendezVousServiceInterface as ConsulterRendezVousServiceInterface;
use toubilib\core\application\usecases\ConsulterRendezVousService as ConsulterRendezVousService;
use toubilib\api\middlewares\CreateRdvMiddleware as CreateRdvMiddleware;
use toubilib\api\actions\CreateRendezVousAction as CreateRendezVousAction;
use toubilib\api\middlewares\AnnulerRendezVousAction as AnnulerRendezVousAction;
use toubilib\api\middlewares\ConsulterAgendaAction as ConsulterAgendaAction;


return [
    \PDO::class . '.praticien' => function(ContainerInterface $c){
        $config = parse_ini_file($c->get('toubiprat.db.conf'));
        $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']}";
        $user = $config['username'];
        $password = $config['password'];
        return new \PDO($dsn, $user, $password, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    },
    \PDO::class . '.patient' => function(ContainerInterface $c){
        $config = parse_ini_file($c->get('toubipat.db.conf'));
        $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']}";
        $user = $config['username'];
        $password = $config['password'];
        return new \PDO($dsn, $user, $password, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    },
    \PDO::class . '.rdv' => function(ContainerInterface $c){
        $config = parse_ini_file($c->get('toubirdv.db.conf'));
        $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']}";
        $user = $config['username'];
        $password = $config['password'];
        return new \PDO($dsn, $user, $password, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    },
    PraticienRepositoryInterface::class=> function (ContainerInterface $c) {
        return new PraticienRepository($c->get(\PDO::class . '.praticien'));
    },
    PatientRepositoryInterface::class=> function (ContainerInterface $c) {
        return new PatientRepository($c->get(\PDO::class . '.patient'));
    },
    RendezVousRepositoryInterface::class=> function (ContainerInterface $c) {
        return new RendezVousRepository($c->get(\PDO::class . '.rdv'));
    },
    ServicePraticienInterface::class=> function (ContainerInterface $c) {
        return new Servicepraticien($c->get(PraticienRepositoryInterface::class));
    },
    ConsulterPraticienServiceInterface::class=> function (ContainerInterface $c) {
        return new ConsulterPraticienService($c->get(ConsulterPraticienServiceInterface::class));
    },
    ServiceRendezVousInterface::class=> function (ContainerInterface $c) {
        return new ServiceRendezVous(
            $c->get(RendezVousRepositoryInterface::class),
            $c->get(PraticienRepositoryInterface::class),
            $c->get(PatientRepositoryInterface::class)
        );
    },
    ConsulterRendezVousServiceInterface::class=> function (ContainerInterface $c) {
        return new ConsulterRendezVousService($c->get(ConsulterRendezVousServiceInterface::class));
    },
    CreateRdvMiddleware::class => function(ContainerInterface $c){
        return new CreateRdvMiddleware();
    },
    CreateRendezVousAction::class => function(ContainerInterface $c){
        return new CreateRendezVousAction($c->get(ServiceRendezVousInterface::class));
    },
    AnnulerRendezVousAction::class => function(ContainerInterface $c){
        return new AnnulerRendezVousAction($c->get(ServiceRendezVousInterface::class));
    },
    ConsulterAgendaAction::class => function(ContainerInterface $c){
        return new ConsulterAgendaAction($c->get(ServiceRendezVousInterface::class));
    }
];