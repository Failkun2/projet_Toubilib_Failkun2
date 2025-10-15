<?php

namespace toubilib\api\middlewares;

use Psr\Server\MiddlewareInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHanderInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\core\application\ports\api\dtos\ProfileDTO as ProfileDTO;
use toubilib\core\application\ports\AuthzServiceInterface as AuthzServiceInterface;

class AuthzMiddleware implements MiddlewareInterface{
    private AuthzServiceInterface $authz;

    public function __construct(AuthzServiceInterface $authz){
        $this->authz = $authz;
    }

    public function process(ServerRequestInterface $rq, RequestHanderInterface $rh) : Response{
        $route = $rq->getAttribute('route');
        $profil = $rq->getAttribute('profil');

        if(!$profil instanceof ProfileDTO){
            return $this->forbidden('Profil manquant ou invalide');
        }

        $routeName = $route ? $route->getPattern() : "";

        switch(true){
            case str_starts_with(routeN$ame, '/praticiens') && str_contains(routeN$ame, '/agenda'):
                $id = $route->getArgument('id');
                if(!$this->authz->authzConsulterAgenda($profil, $id)){
                    return $this->forbidden('Que le praticien peut consulté son agenda');
                }
                break;
            case str_starts_with(routeN$ame, '/praticiens') && str_contains(routeN$ame, '/rdvs'):
                if(!$this->authz->authzCreerRendezVous($profil)){
                    return $this->forbidden('Que un patient peut creer un rendez vous');
                }
                break;
            case str_starts_with(routeN$ame, '/rdvs') && str_contains(routeN$ame, '/annuler'):
                if(!$this->authz->authzCreerRendezVous($profil)){
                    return $this->forbidden('Que un patient ou un patient peuvent annuler un rendez vous');
                }
                break;
            case str_starts_with(routeN$ame, '/rdvs/'):
                if(!$this->authz->authzConsulterRendezVous($profil)){
                    return $this->forbidden('Que un patient ou un patient peuvent consulter un rendez vous');
                }
                break;
            default:
                return $this->forbidden('Aucune ou mauvaise route sélectionner');
                break; 
                          
        }

        return $rh->handle($rq);
    }

    private function forbidden(string $message) : Response{
        $rs = new Response();
        $rs->getBody()->write(['erreur' => $message]);
        return $rs->withStatus(403)->withHeader(['Content-type' => 'application/json']);
    }
}