<?php

use PHPUnit\Framework\TestCase;
use toubilib\core\domain\entities\ServiceRendezVousInterface as ServiceRendezVousInterface;
use toubilib\core\domain\entities\ConsulterRendezVousServiceInterface as ConsulterRendezVousServiceInterface;
use DI\Container;
use toubilib\core\application\ports\api\dtos\InputRendezVousDTO as InputRendezVousDTO;

class AnnulerRendezVousTest extends TestCase{

    private ServiceRendezVousInterface $service;
    private ConsulterRendezVousServiceInterface $service2;

    public function setUp():void{
        $container = new Container();
        $services = require __DIR__ . '\..\..\config\services.php';

        foreach($services as $id => $content){
            $container->set($id, $content);
        }

        $this->service = $container->get(ServiceRendezVousInterface::class);
        $this->service2 = $container->get(ConsulterRendezVousServiceInterface::class);
    }

    public function test_CreerRendezVous(){
        var_dump(get_class($this->service));
        $dto = new InputRendezVousDTO(
            '4305f5e9-be5a-4ccf-8792-7e07d7017363',
            'd975aca7-50c5-3d16-b211-cf7d302cba50',
            new \DateTimeImmutable('tomorrow 11:00'),
            30,
            'scanner'
        );
        $rdvId = $this->service->creerRendezVous($dto);
        var_dump($rdvId);
        var_dump(gettype($rdvId));
        $this->assertNotNull($rdvId);   
        $this->service->annulerRendezVous($rdvId);
        $rdvAnnuler = $this->service2->afficherRendezVous($rdvId);
        $rdv = $rdvAnnuler->serialise_JSON();
        $this->assertSame(2, $rdv['statut']);
        print($rdv['statut']);
    }
}