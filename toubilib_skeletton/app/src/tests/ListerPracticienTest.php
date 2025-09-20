<?php

use PHPUnit\Framework\TestCase;
use toubilib\core\domain\entities\ServicePracticienInterface as ServicePracticienInterface;
use DI\Container;

class ListerPracticienTest extends TestCase{

    private ServicePracticienInterface $service;

    public function setUp():void{
        $container = new Container();
        $services = require __DIR__ . '\..\..\config\services.php';

        foreach($services as $id => $content){
            $container->set($id, $content);
        }

        $this->service = $container->get(ServicePracticienInterface::class);
    }

    public function test_listerPracticien(){
        $res = $this->service->listerPracticiens();
        $this->assertEquals(true, is_array($res));
        $this->assertEquals(true, count($res) > 0);

        $practicien = $res[0]->serialise_JSON();

        $this->assertEquals(true, isset($practicien['nom']));
        print($practicien['nom']);
        $this->assertEquals(true, isset($practicien['prenom']));
        print($practicien['prenom']);
        $this->assertEquals(true, isset($practicien['ville']));
        print($practicien['ville']);
        $this->assertEquals(true, isset($practicien['email']));
        print($practicien['email']);
        $this->assertEquals(true, isset($practicien['specialite']));
        print($practicien['specialite']);
    }
}