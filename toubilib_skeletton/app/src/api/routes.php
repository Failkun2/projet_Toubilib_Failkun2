<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use toubilib\api\actions\ListerPraticiensAction as ListerPraticiensAction;
use toubilib\api\actions\PraticienByIdAction as PraticienByIdAction;
use toubilib\api\actions\PraticienRDVAction as PraticienRDVAction;
use toubilib\api\actions\CreateRendezVousAction as CreateRendezVousAction;
use toubilib\api\actions\RendezVousByIdAction as RendezVousByIdAction;
use toubilib\api\middlewares\CreateRdvMiddleware as CreateRdvMiddleware;


return function( \Slim\App $app):\Slim\App {



    $app->get('/', HomeAction::class);
    $app->get('/praticiens', ListerPraticiensAction::class);
    $app->get('/praticiens/{id}', PraticienByIdAction::class);
    $app->get('/praticiens/{id}/rdvs', PraticienRDVAction::class);
    $app->post('/praticiens/{id}/rdvs', CreateRendezVousAction::class)
    ->add(CreateRdvMiddleware::class);
    $app->get('/rdvs/{id}', RendezVousByIdAction::class);

    return $app;
};