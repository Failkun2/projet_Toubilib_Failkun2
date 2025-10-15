<?php

use PHPUnit\Framework\TestCase;
use toubilib\core\application\ports\ServiceRendezVousInterface as ServiceRendezVousInterface;
use DI\Container;

class ListerRendezVousTest extends TestCase{

    private ServiceRendezVousInterface $service;

    public function setUp():void{
        $container = new Container();
        $services = require __DIR__ . '\..\..\config\services.php';

        foreach($services as $id => $content){
            $container->set($id, $content);
        }

        $this->service = $container->get(ServiceRendezVousInterface::class);
    }

    public function test_listerCrenaux(){
        $res = $this->service->listerCrenaux('4305f5e9-be5a-4ccf-8792-7e07d7017363', new \DateTimeImmutable('2025-12-01 16:00:00'), new \DateTimeImmutable('2025-12-03 10:30:00'));
        $this->assertEquals(true, is_array($res));
        $this->assertEquals(true, count($res) > 0);

        $rdv = $res[0]->serialise_JSON();

        $this->assertEquals(true, isset($rdv['dateDebut']));
        //print_r($praticien['dateDebut']);
        $this->assertEquals(true, isset($rdv['dateFin']));
        //print_r($rdv['dateFin']);
    }
}