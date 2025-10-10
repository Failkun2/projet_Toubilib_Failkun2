<?php

use PHPUnit\Framework\TestCase;
use toubilib\core\domain\entities\ServiceRendezVousInterface as ServiceRendezVousInterface;
use DI\Container;

class AfficherAgendaTest extends TestCase{

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
        $agenda = $this->service->consulterAgenda('4305f5e9-be5a-4ccf-8792-7e07d7017363', new \DateTimeImmutable('2025-12-01 8:00:00'), new \DateTimeImmutable('2025-12-03 10:30:00'));
        $this->assertEquals(true, is_array($agenda));

        foreach($agenda as $rdv){
            $this->AssertArrayHasKey('patient', $rdv);
            $this->AssertArrayHasKey('motif_visite', $rdv);
        }
    }
}