<?php

namespace toubilib\core\application\ports\spi\repositoryInterfaces;

use toubilib\core\domain\entities\praticien\Patient as Patient;

interface PatientRepositoryInterface{
    
    public function findById(int $id) : Patient;

}