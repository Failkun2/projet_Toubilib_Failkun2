<?php

namespace toubilib\api\middlewares;

use Psr\Http\Server\MiddlewareInterface;
use Slim\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\core\application\ports\api\dtos\ProfileDTO as ProfileDTO;
use toubilib\core\application\ports\AuthzServiceInterface as AuthzServiceInterface;

class AuthzMiddleware implements MiddlewareInterface{
    private AuthzServiceInterface $authz;

    public function __construct(AuthzServiceInterface $authz){
        $this->authz = $authz;
    }

    public function process(ServerRequestInterface $rq, RequestHandlerInterface $rh) : Response{
        $route = $rq->getAttribute('route');
        $profil = $rq->getAttribute('profil');

        if(!$profil instanceof ProfileDTO){
            return $this->forbidden('Profil manquant ou invalide');
        }

        $routeName = $route ? $route->getPattern() : "";

        switch(true){
            case str_starts_with($routeName, '/praticiens') && str_contains($routeName, '/agenda'):
                $id = $route->getArgument('id');
                if(!$this->authz->authzConsulterAgenda($profil, $id)){
                    return $this->forbidden('Que le praticien peut consulté son agenda');
                }
                break;
            case str_starts_with($routeName, '/praticiens') && str_contains($routeName, '/rdvs'):
                if(!$this->authz->authzCreerRendezVous($profil)){
                    return $this->forbidden('Que un patient peut creer un rendez vous');
                }
                break;
            case str_starts_with($routeName, '/rdvs') && str_contains($routeName, '/annuler'):
                if(!$this->authz->authzCreerRendezVous($profil)){
                    return $this->forbidden('Que un patient ou un praticien peuvent annuler un rendez vous');
                }
                break;
            case str_starts_with($routeName, '/rdvs') && str_contains($routeName, '/honorer'):
                $id = $route->getArgument('id');
                if(!$this->authz->authzHonorerRendezVous($profil, $id)){
                    return $this->forbidden('Que le praticien peut honorer le rendez vous');
                }
                break;
            case str_starts_with($routeName, '/rdvs') && str_contains($routeName, '/nonHonorer'):
                $id = $route->getArgument('id');
                if(!$this->authz->authzNonHonorerRendezVous($profil, $id)){
                    return $this->forbidden('Que le praticien peut ne pas honorer le rendez vous');
                }
                break;
            case str_starts_with($routeName, '/rdvs') && str_contains($routeName, '/indisponibilites'):
                $id = $route->getArgument('id');
                if(!$this->authz->authzCreerIndisponibilite($profil, $id)){
                    return $this->forbidden('Que le praticien peut gérer ces disponibilités');
                }
                break;
            case str_starts_with($routeName, '/rdvs/'):
                if(!$this->authz->authzConsulterRendezVous($profil)){
                    return $this->forbidden('Que un patient ou un praticien peuvent consulter un rendez vous');
                }
                break;
            case str_starts_with($routeName, '/patients') && str_contains($routeName, '/historique'):
                $id = $route->getArgument('id');
                if(!$this->authz->authzConsulterHistorique($profil, $id)){
                    return $this->forbidden('Que le patient peut consulté son historique');
                }
                break;
            default:
                return $this->forbidden('Aucune ou mauvaise route sélectionner');
                break; 
                          
        }

        return $rh->handle($rq);
    }

    private function forbidden(string $message) : ResponseInterface{
        $rs = new Response();
        $rs->getBody()->write(json_encode(['erreur' => $message]));
        return $rs->withStatus(403)->withHeader('Content-type', 'application/json');
    }
}