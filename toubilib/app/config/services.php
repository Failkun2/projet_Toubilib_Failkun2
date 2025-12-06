<?php

use Psr\Container\ContainerInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\PraticienRepositoryInterface as PraticienRepositoryInterface;
use toubilib\infra\repositories\PraticienRepository as PraticienRepository;
use toubilib\core\application\ports\spi\repositoryInterfaces\PatientRepositoryInterface as PatientRepositoryInterface;
use toubilib\infra\repositories\PatientRepository as PatientRepository;
use toubilib\core\application\ports\spi\repositoryInterfaces\RendezVousRepositoryInterface as RendezVousRepositoryInterface;
use toubilib\infra\repositories\RendezVousRepository as RendezVousRepository;
use toubilib\core\application\ports\spi\repositoryInterfaces\AuthnRepositoryInterface as AuthnRepositoryInterface;
use toubilib\infra\repositories\AuthnRepository as AuthnRepository;
use toubilib\core\application\ports\ServicePraticienInterface as ServicePraticienInterface;
use toubilib\core\application\usecases\ServicePraticien as ServicePraticien;
use toubilib\core\application\ports\ServiceRendezVousInterface as ServiceRendezVousInterface;
use toubilib\core\application\usecases\ServiceRendezVous as ServiceRendezVous;
use toubilib\core\application\ports\ConsulterPraticienServiceInterface as ConsulterPraticienServiceInterface;
use toubilib\core\application\usecases\ConsulterPraticienService as ConsulterPraticienService;
use toubilib\core\application\ports\ConsulterRendezVousServiceInterface as ConsulterRendezVousServiceInterface;
use toubilib\core\application\usecases\ConsulterRendezVousService as ConsulterRendezVousService;
use toubilib\core\application\ports\AuthnServiceInterface as AuthnServiceInterface;
use toubilib\core\application\usecases\AuthnService as AuthnService;
use toubilib\core\application\ports\ServicePatientInterface as ServicePatientInterface;
use toubilib\core\application\usecases\ServicePatient as ServicePatient;
use toubilib\api\middlewares\CreateRdvMiddleware as CreateRdvMiddleware;
use toubilib\api\middlewares\AuthnMiddleware as AuthnMiddleware;
use toubilib\api\middlewares\AuthzMiddleware as AuthzMiddleware;
use toubilib\api\middlewares\CreatePatientMiddleware as CreatePatientMiddleware;
use toubilib\api\provider\JWTAuthnProvider as JWTAuthnProvider;
use toubilib\core\application\ports\AuthzServiceInterface as AuthzServiceInterface;
use toubilib\core\application\usecases\AuthzService as AuthzService;


return [
    \PDO::class . '.praticien' => function(ContainerInterface $c){
        $chemin = __DIR__ . DIRECTORY_SEPARATOR . 'toubiprat.db.ini';
        $config = parse_ini_file($chemin);
        $dsn = "{$config['driver']}:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";
        $user = $config['user'];
        $password = $config['password'];
        return new \PDO($dsn, $user, $password, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    },
    \PDO::class . '.patient' => function(ContainerInterface $c){
        $chemin = __DIR__ . DIRECTORY_SEPARATOR . 'toubipat.db.ini';
        $config = parse_ini_file($chemin);
        $dsn = "{$config['driver']}:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";
        $user = $config['user'];
        $password = $config['password'];
        return new \PDO($dsn, $user, $password, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    },
    \PDO::class . '.rdv' => function(ContainerInterface $c){
        $chemin = __DIR__ . DIRECTORY_SEPARATOR . 'toubirdv.db.ini';
        $config = parse_ini_file($chemin);
        $dsn = "{$config['driver']}:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";
        $user = $config['user'];
        $password = $config['password'];
        return new \PDO($dsn, $user, $password, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    },
    \PDO::class . '.auth' => function(ContainerInterface $c){
        $chemin = __DIR__ . DIRECTORY_SEPARATOR . 'toubiauth.db.ini';
        $config = parse_ini_file($chemin);
        $dsn = "{$config['driver']}:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";
        $user = $config['user'];
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
    AuthnRepositoryInterface::class=> function (ContainerInterface $c) {
        return new AuthnRepository($c->get(\PDO::class . '.auth'));
    },
    ServicePraticienInterface::class=> function (ContainerInterface $c) {
        return new Servicepraticien($c->get(PraticienRepositoryInterface::class));
    },
    ConsulterPraticienServiceInterface::class=> function (ContainerInterface $c) {
        return new ConsulterPraticienService($c->get(PraticienRepositoryInterface::class));
    },
    ServiceRendezVousInterface::class=> function (ContainerInterface $c) {
        return new ServiceRendezVous(
            $c->get(RendezVousRepositoryInterface::class),
            $c->get(PraticienRepositoryInterface::class),
            $c->get(PatientRepositoryInterface::class)
        );
    },
    ConsulterRendezVousServiceInterface::class=> function (ContainerInterface $c) {
        return new ConsulterRendezVousService($c->get(RendezVousRepositoryInterface::class));
    },
    AuthnServiceInterface::class=> function (ContainerInterface $c) {
        return new AuthnService($c->get(AuthnRepositoryInterface::class));
    },
    AuthzServiceInterface::class=> function (ContainerInterface $c) {
        return new AuthzService();
    },
    ServicePatientInterface::class=> function (ContainerInterface $c) {
        return new ServicePatient(
            $c->get(AuthnRepositoryInterface::class),
            $c->get(PatientRepositoryInterface::class)
        );
    },
    CreateRdvMiddleware::class => function(ContainerInterface $c){
        return new CreateRdvMiddleware();
    },
    CreatePatientMiddleware::class => function(ContainerInterface $c){
        return new CreatePatientMiddleware();
    },
    JWTAuthnProvider::class => function(ContainerInterface $c){
        $secret = __DIR__ . DIRECTORY_SEPARATOR . 'secret.ini';
        return new JWTAuthnProvider($secret, $c->get(AuthnServiceInterface::class));
    },
    AuthnMiddleware::class => function(ContainerInterface $c){
        return new AuthnMiddleware($c->get(JWTAuthnProvider::class));
    },
    AuthzMiddleware::class => function(ContainerInterface $c){
        return new AuthzMiddleware($c->get(AuthzServiceInterface::class));
    },
    Cors::class => function() {
        return new \toubilib\api\middlewares\Cors();
    },
];