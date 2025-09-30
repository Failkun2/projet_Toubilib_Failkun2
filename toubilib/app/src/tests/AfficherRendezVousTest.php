<?php

use PHPUnit\Framework\TestCase;
use toubilib\core\domain\entities\ConsulterRendezVousServiceInterface as ConsulterRendezVousServiceInterface;
use DI\Container;

class AfficherRendezVousTest extends TestCase{

    private ConsulterRendezVousServiceInterface $service;

    public function setUp():void{
        $container = new Container();
        $services = require __DIR__ . '\..\..\config\services.php';

        foreach($services as $id => $content){
            $container->set($id, $content);
        }

        $this->service = $container->get(ConsulterRendezVousServiceInterface::class);
    }

    public function test_afficherPraticien(){
        $res = $this->service->afficherRendezVous('2e1a7275-2593-3c04-9a4c-4e7cbada9541');
        $this->assertEquals(false, is_null($res));
        $rdv = $res->serialise_JSON();

        $this->assertEquals(true, isset($praticien['dateDebut']));
        print($praticien['dateDebut']);
        $this->assertEquals(true, isset($praticien['dateFin']));
        print($praticien['dateFin']);
        $this->assertEquals(true, isset($praticien['duree']));
        print($praticien['duree']);
        $this->assertEquals(true, isset($praticien['statut']));
        print($praticien['statut']);
        $this->assertEquals(true, isset($praticien['motifVisite']));
        print($praticien['motifVisite']);
        $this->assertEquals(true, isset($praticien['dateCreation']));
        print($praticien['dateCreation']);
    }
}