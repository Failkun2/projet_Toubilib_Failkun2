<?php

use PHPUnit\Framework\TestCase;
use toubilib\core\domain\entities\ServiceRendezVousInterface as ServiceRendezVousInterface;
use DI\Container;
use toubilib\core\application\ports\api\dtos\InputRendezVousDTO as InputRendezVousDTO;

class CreerRendezVousTest extends TestCase{

    private ServiceRendezVousInterface $service;

    public function setUp():void{
        $container = new Container();
        $services = require __DIR__ . '\..\..\config\services.php';

        foreach($services as $id => $content){
            $container->set($id, $content);
        }

        $this->service = $container->get(ServiceRendezVousInterface::class);
    }

    public function test_CreerRendezVous(){
        $dto = new InputRendezVousDTO(
            '4305f5e9-be5a-4ccf-8792-7e07d7017363',
            'd975aca7-50c5-3d16-b211-cf7d302cba50',
            new \DateTimeImmutable('tomorrow 10:00'),
            30,
            'scanner'
        );
        $rdvId = $this->service->creerRendezVous($dto);
        $this->assertNotNull($rdvId);   
        print($rdvId);
    }
}