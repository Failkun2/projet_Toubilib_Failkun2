<?php

use PHPUnit\Framework\TestCase;
use toubilib\core\application\ports\ConsulterPraticienServiceInterface as ConsulterPraticienServiceInterface;
use DI\Container;

class AfficherPraticienTest extends TestCase{

    private ConsulterPraticienServiceInterface $service;

    public function setUp():void{
        $container = new Container();
        $services = require __DIR__ . '\..\..\config\services.php';

        foreach($services as $id => $content){
            $container->set($id, $content);
        }

        $this->service = $container->get(ConsulterPraticienServiceInterface::class);
    }

    public function test_afficherPraticien(){
        $res = $this->service->afficherPraticien('4305f5e9-be5a-4ccf-8792-7e07d7017363');
        $this->assertEquals(false, is_null($res));
        $praticien = $res->serialise_JSON();

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
        $this->assertEquals(true, isset($praticien['telephone']));
        print($praticien['telephone']);
        $this->assertEquals(true, isset($praticien['adresse']));
        print($praticien['adresse']);
        $this->assertEquals(true, isset($praticien['motifs']));
        print_r($praticien['motifs']);
        $this->assertEquals(true, isset($praticien['moyensPaiement']));
        print_r($praticien['moyensPaiement']);
    }
}