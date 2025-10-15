<?php

use PHPUnit\Framework\TestCase;
use toubilib\core\application\ports\AuthzServiceInterface as AuthzServiceInterface;
use toubilib\core\application\ports\api\dtos\ProfileDTO as ProfileDTO;
use DI\Container;

class AuthzTest extends TestCase{

    private AuthzServiceInterface $service;

    public function setUp():void{
        $container = new Container();
        $services = require __DIR__ . '\..\..\config\services.php';

        foreach($services as $id => $content){
            $container->set($id, $content);
        }

        $this->service = $container->get(AuthzServiceInterface::class);
    }

    public function testAuthzPatientOK() : void{
        $profil = new ProfileDTO('d975aca7-50c5-3d16-b211-cf7d302cba50', 'Denis.Teixeira@hotmail.fr', 1);

        $this->assertTrue($this->service->authzCreerRendezVous($profil));
        $this->assertTrue($this->service->authzConsulterRendezVous($profil));
        $this->assertTrue($this->service->authzAnnulerRendezVous($profil));
    }

    public function testAuthzPatientKO() : void{
        $profil = new ProfileDTO('d975aca7-50c5-3d16-b211-cf7d302cba50', 'Denis.Teixeira@hotmail.fr', 1);
        $this->assertFalse($this->service->authzConsulterAgenda($profil, '8ae1400f-d46d-3b50-b356-269f776be532'));
    }

    public function testAuthzPraticienOK() : void{
        $profil = new ProfileDTO('4305f5e9-be5a-4ccf-8792-7e07d7017363', 'radio.plus@sante.fr', 10);
        $this->assertTrue($this->service->authzConsulterAgenda($profil, '4305f5e9-be5a-4ccf-8792-7e07d7017363'));
        $this->assertTrue($this->service->authzConsulterRendezVous($profil));
        $this->assertTrue($this->service->authzAnnulerRendezVous($profil));
    }

    public function testAuthzPraticienKO() : void{
        $profil = new ProfileDTO('4305f5e9-be5a-4ccf-8792-7e07d7017363', 'radio.plus@sante.fr', 10);
        $this->assertFalse($this->service->authzConsulterAgenda($profil, '8ae1400f-d46d-3b50-b356-269f776be532'));
        $this->assertFalse($this->service->authzCreerRendezVous($profil));
    }
}