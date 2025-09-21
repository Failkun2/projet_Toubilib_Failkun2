<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


return function( \Slim\App $app):\Slim\App {



    $app->get('/', HomeAction::class);
    $app->get('/praticiens', ListerPraticiensAction::class);
    $app->get('/praticiens/{id}', PraticienByIdAction::class);
  

    return $app;
};