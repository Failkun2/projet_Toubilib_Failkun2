<?php

use Psr\Container\ContainerInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\praticienRepositoryInterface as praticienRepositoryInterface;
use toubilib\core\domain\entities\praticien\praticienRepository as praticienRepository;
use toubilib\core\domain\entities\ServicepraticienInterface as ServicepraticienInterface;
use toubilib\core\application\usecases\Servicepraticien as Servicepraticien;

return [
    \PDO::class => function(ContainerInterface $c){
        $config = parse_ini_file($c->get('toubiprat.db.conf'));
        $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']}";
        $user = $config['username'];
        $password = $config['password'];
        return new \PDO($dsn, $user, $password, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    },
    PraticienRepositoryInterface::class=> function (ContainerInterface $c) {
        return new PraticienRepository($c->get(\PDO::class));
    },
    RendezVousRepositoryInterface::class=> function (ContainerInterface $c) {
        return new RendezVousRepository($c->get(\PDO::class));
    },
    ServicePraticienInterface::class=> function (ContainerInterface $c) {
        return new Servicepraticien($c->get(PraticienRepositoryInterface::class));
    },
    ConsulterPraticienServiceInterface::class=> function (ContainerInterface $c) {
        return new ConsulterPraticienService($c->get(ConsulterPraticienServiceInterface::class));
    },
    ServiceRendezVousInterface::class=> function (ContainerInterface $c) {
        return new ServiceRendezVous($c->get(RendezVousRepositoryInterface::class));
    },
    ConsulterRendezVousServiceInterface::class=> function (ContainerInterface $c) {
        return new ConsulterRendezVousService($c->get(ConsulterRendezVousServiceInterface::class));
    },
];