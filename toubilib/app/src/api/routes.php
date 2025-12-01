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
use toubilib\api\actions\FiltrerPraticiensAction as FiltrerPraticiensAction;
use toubilib\api\middlewares\AuthnMiddleware as AuthnMiddleware;
use toubilib\api\middlewares\AuthzMiddleware as AuthzMiddleware;


return function( \Slim\App $app):\Slim\App {

    $app->add(Cors::class);

    //$app->get('/', HomeAction::class);
    $app->get('/praticiens', ListerPraticiensAction::class); //tester
    //http://localhost:6080/praticiens/4305f5e9-be5a-4ccf-8792-7e07d7017363/rdvs?debut=2025-12-01&fin=2025-12-10
    $app->get('/praticiens/{id}/rdvs', PraticienRDVAction::class); //tester
    $app->get('/rdvs/{id}', RendezVousByIdAction::class); //tester
    $app->post('/auth/signin', SignInAction::class); //tester
    $app->post('/auth/refresh', RefreshAction::class); //tester
    $app->get('/praticiens/filtrer', FiltrerPraticiensAction::class); //tester

    $app->group('', function(\Slim\Routing\RouteCollectorProxy $group){
      //http://localhost:6080/praticiens/4305f5e9-be5a-4ccf-8792-7e07d7017363/agenda?debut=2025-12-01&fin=2025-12-10
        $group->get('/praticiens/{id}/agenda', ConsulterAgendaAction::class); //tester
        $group->post('/praticiens/{id}/rdvs', CreateRendezVousAction::class)
        ->add(CreateRdvMiddleware::class); //tester
        $group->get('/praticiens/{id}', PraticienByIdAction::class); //tester
        $group->patch('/rdvs/{id}/annuler', AnnulerRendezVousAction::class); //tester
    })
    ->add(AuthnMiddleware::class)
    ->add(AuthzMiddleware::class);
    
    return $app;
};