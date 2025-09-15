<?php

namespace toubilib\core\application\usecases;




class ServicePraticien implements ServicePraticienInterface
{
    private PraticienRepositoryInterface $praticienRepository;

    public function __construct(PraticienRepositoryInterface $praticienRepository)
    {
        $this->praticienRepository = $praticienRepository;
    }

    public function listerPraticiens(): array {
    	$practiciens = $this->practicienRepository->findPracticiens();

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