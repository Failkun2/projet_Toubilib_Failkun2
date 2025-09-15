<?php

use Psr\Container\ContainerInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\PracticienRepositoryInterface as PracticienRepositoryInterface;
use toubilib\core\domain\entities\praticien\PracticienRepository as PracticienRepository;
use toubilib\core\domain\entities\ServicePracticienInterface as ServicePracticienInterface;
use toubilib\core\application\usecases\ServicePracticien as ServicePracticien;

return [
    \PDO::class => function(ContainerInterface $c){
        $config = parse_ini_file($c->get('toubiprat.db.conf'));
        $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']}";
        $user = $config['username'];
        $password = $config['password'];
        return new \PDO($dsn, $user, $password, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    },
    PracticienRepositoryInterface::class=> function (ContainerInterface $c) {
        return new PracticienRepository($c->get(\PDO::class));
    },
    ServicePracticienInterface::class=> function (ContainerInterface $c) {
        return new ServicePracticien($c->get(PracticienRepositoryInterface::class));
    },
];