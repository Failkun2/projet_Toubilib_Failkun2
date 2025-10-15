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
use toubilib\api\middlewares\Cors as Cors;
use toubilib\api\actions\AnnulerRendezVousAction as AnnulerRendezVousAction;
use toubilib\api\actions\ConsulterAgendaAction as ConsulterAgendaAction;
use toubilib\api\actions\SignInAction as SignInAction;
use toubilib\api\actions\RefreshAction as RefreshAction;


return function( \Slim\App $app):\Slim\App {

    $app->add(Cors::class);

    $app->get('/', HomeAction::class);
    $app->get('/praticiens', ListerPraticiensAction::class);
    $app->get('/praticiens/{id}/rdvs', PraticienRDVAction::class);
    $app->get('/rdvs/{id}', RendezVousByIdAction::class);
    $app->post('/auth/signin', SignInAction::class);
    $app->post('/auth/refresh', RefreshAction::class);

    $app->group('', function(\Slim\Routing\RouteCollectorProxy $group){
        $app->get('/praticiens/{id}/agenda', ConsulterAgendaAction::class);
        $app->post('/praticiens/{id}/rdvs', CreateRendezVousAction::class)
        ->add(CreateRdvMiddleware::class);
        $app->get('/praticiens/{id}', PraticienByIdAction::class);
        $app->patch('/rdvs/{id}/annuler', AnnulerRendezVousAction::class);
    })
    ->add(AuthnMiddleware::class)
    ->add(AuthzMiddleware::class);
    return $app;
};