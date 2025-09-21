<?php

namespace toubilib\core\application\ports\spi\repositoryInterfaces;

interface PraticienRepositoryInterface{
    
    public function findPraticiens() : array;

    public function findById(int $id) : Praticien;
}