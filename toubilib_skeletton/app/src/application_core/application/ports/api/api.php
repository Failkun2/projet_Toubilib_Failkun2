<?php

use toubilib\core\domain\entities\praticien\ServicePracticienInterface as ServicePracticienInterface;
use toubilib\api\actions\ListerPracticiensAction as ListerPracticiensAction;
use Psr\Container\ContainerInterface;

return [
    ListerPracticiensAction::class=> function (ContainerInterface $c) {
        return new ListerPracticiensAction($c->get(ServicePracticienInterface::class));
    },
];