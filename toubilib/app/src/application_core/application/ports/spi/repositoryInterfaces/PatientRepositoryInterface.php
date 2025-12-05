<?php

namespace toubilib\core\application\ports\spi\repositoryInterfaces;

use toubilib\core\domain\entities\patient\Patient as Patient;

interface PatientRepositoryInterface{
    
    public function findById(String $id) : Patient;

    public function creerPatient(string $id, array $data) : void;

}