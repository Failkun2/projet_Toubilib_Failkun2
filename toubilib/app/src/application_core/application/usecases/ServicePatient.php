<?php

namespace toubilib\core\application\usecases;

use toubilib\core\application\ports\ServicePatientInterface as ServicePatientInterface;
use toubilib\core\application\ports\api\dtos\InputPatientDTO as InputPatientDTO;
use toubilib\core\application\ports\spi\repositoryInterfaces\PatientRepositoryInterface as PatientRepositoryInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\AuthnRepositoryInterface as AuthnRepositoryInterface;
use Ramsey\Uuid\Uuid;
use toubilib\core\application\exceptions\ValidationException as ValidationException;


class ServicePatient implements ServicePatientInterface{
    
    private AuthnRepositoryInterface $authnRepository;
    private PatientRepositoryInterface $patientRepository;


    public function __construct(AuthnRepositoryInterface $authnRepository, PatientRepositoryInterface $patientRepository)
    {
        $this->authnRepository = $authnRepository;
        $this->patientRepository = $patientRepository;
    }

    public function creerPatient(InputPatientDTO $dto) : String{
        $errors = [];
        if($this->authnRepository->userExiste($dto->__get('email'))){
            $errors['patient'] = "compte existant";
        }
        $data = $dto->jsonSerialize();
        if(!empty($errors)){
            $errors['data'] = "pas de données";
        }

        if(!empty($errors)){
            throw new ValidationException($errors);
        }
        $id = Uuid::uuid4()->toString();

        $this->authnRepository->creerUser($id, $data);
        $this->patientRepository->creerPatient($id, $data);
        
        return $id;
    }

}