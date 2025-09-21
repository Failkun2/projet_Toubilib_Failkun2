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
        return new praticienRepository($c->get(\PDO::class));
    },
    ServicePraticienInterface::class=> function (ContainerInterface $c) {
        return new Servicepraticien($c->get(praticienRepositoryInterface::class));
    },
    ConsulterPraticienServiceInterface::class=> function (ContainerInterface $c) {
        return new ConsulterPraticienService($c->get(ConsulterPraticienServiceInterface::class));
    },
];