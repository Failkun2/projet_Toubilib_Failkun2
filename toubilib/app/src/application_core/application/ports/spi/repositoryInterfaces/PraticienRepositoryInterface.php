<?php

namespace toubilib\core\application\ports\spi\repositoryInterfaces;

use toubilib\core\domain\entities\praticien\Praticien as Praticien;

interface PraticienRepositoryInterface{
    
    public function findPraticiens() : array;

    public function findById(String $id) : Praticien;

    public function findMotifsByPraticien(String $praticienId) : array;
}