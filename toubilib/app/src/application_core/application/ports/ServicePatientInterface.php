<?php

namespace toubilib\core\application\ports;

use toubilib\core\application\ports\api\dtos\InputPatientDTO as InputPatientDTO;

interface ServicePatientInterface{

    public function creerPatient(InputPatientDTO $dto) : String;

}