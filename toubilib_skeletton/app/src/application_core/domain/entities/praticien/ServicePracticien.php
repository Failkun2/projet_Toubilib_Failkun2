<?php

namespace toubilib\core\domain\entities\praticien;

use toubilib\core\domain\entities\praticien\ServicePracticienInterface as ServicePracticienInterface;
use toubilib\core\application\ports\api\dtos\PracticienDTO as PracticienDTO;
use toubilib\core\application\ports\spi\repositoryInterfaces\PracticienRepositoryInterface as PracticienRepository;

class ServicePracticien implements ServicePracticienInterface{
    
    private PracticienRepository $repo;
    
    public function __construct(PracticienRepository $repo){
        $this->repo = $repo;
    }

    public function listerPracticiens() : array{
        $practiciens = $this->repo->findPracticiens();

        return array_map(function($practicien){
            return new PracticienDTO(
                $practicien->__get("nom"),
                $practicien->__get("prenom"),
                $practicien->__get("ville"),
                $practicien->__get("email"),
                $practicien->__get("specialite")
            );
        }, $practiciens);
    }
}