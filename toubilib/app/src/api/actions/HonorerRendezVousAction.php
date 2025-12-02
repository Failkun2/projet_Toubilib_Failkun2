<?php

namespace toubilib\api\actions;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\api\actions\AbstractAction as AbstractAction;
use toubilib\core\application\ports\ServiceRendezVousInterface as ServiceRendezVousInterface;
use toubilib\core\application\exceptions\RendezVousInvalideException as RendezVousInvalideException;
use toubilib\core\application\exceptions\RendezVousIntrouvableException as RendezVousIntrouvableException;

class HonorerRendezVousAction extends AbstractAction{

    private ServiceRendezVousInterface $service;

    public function __construct(ServiceRendezVousInterface $service){
        $this->service = $service;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args) : ResponseInterface{
        $id = $args['id'] ?? null;
        if(!$id){
            return new Response(400, [], json_encode(['erreur' => 'id de rdv manquant']));
        }
        try{
            $this->service->honorerRendezVous($id);
            $rs->getBody()->write(json_encode([
                'message' => 'Rendez Vous honorer', 
                '_links' => [
                    'self' => ['href' => "/rdvs/$id"],
                    'praticiens' => ['href' => '/praticiens']
                ]]));
            return $rs->withStatus(200)->withHeader('Content-Type', 'application/json');
        } catch(RendezVousIntrouvableException $e){
            $rs->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
            return $rs->withStatus(404)->withHeader('Content-Type', 'application/json');
        } catch(RendezVousInvalideException $e){
            $rs->getBody()->write(json_encode(['erreur' => $e->getMessage()]));
            return $rs->withStatus(409)->withHeader('Content-Type', 'application/json');
        } catch(\Throwable $t){
            $rs->getBody()->write(json_encode(['erreur' => $t->getMessage()]));
            return $rs->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }
}