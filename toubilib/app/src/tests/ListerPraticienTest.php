<?php

require_once __DIR__ . '\..\..\vendor\autoload.php';
use PHPUnit\Framework\TestCase;
use toubilib\core\application\ports\ServicePraticienInterface as ServicePraticienInterface;
use DI\Container;

class ListerPraticienTest extends TestCase{

    private ServicePraticienInterface $service;

    public function setUp():void{
        $container = new Container();
        $services = require __DIR__ . '\..\..\config\services.php';

        foreach($services as $id => $content){
            $container->set($id, $content);
        }

        $this->service = $container->get(ServicePraticienInterface::class);
    }

    public function test_listerPraticien(){
        $res = $this->service->listerPraticiens();
        $this->assertEquals(true, is_array($res));
        $this->assertEquals(true, count($res) > 0);

        $praticien = $res[0]->serialise_JSON();

        $this->assertEquals(true, isset($praticien['nom']));
        print($praticien['nom']);
        $this->assertEquals(true, isset($praticien['prenom']));
        print($praticien['prenom']);
        $this->assertEquals(true, isset($praticien['ville']));
        print($praticien['ville']);
        $this->assertEquals(true, isset($praticien['email']));
        print($praticien['email']);
        $this->assertEquals(true, isset($praticien['specialite']));
        print($praticien['specialite']);
    }
}