<?php

namespace toubilib\core\application\usecases;

use toubilib\core\domain\entities\ServicePraticienInterface as ServicePraticienInterface;
use toubilib\core\application\ports\api\dtos\PraticienDTO as PraticienDTO;
use toubilib\core\application\ports\spi\repositoryInterfaces\PraticienRepositoryInterface as PraticienRepositoryInterface;


class ServicePraticien implements ServicePraticienInterface
{
    private PraticienRepositoryInterface $praticienRepository;

    public function __construct(PraticienRepositoryInterface $praticienRepository)
    {
        $this->praticienRepository = $praticienRepository;
    }

    public function listerPraticiens(): array {
    	$praticiens = $this->praticienRepository->findPraticiens();

        return array_map(function($praticien){
            return new PraticienDTO(
                $praticien->__get("nom"),
                $praticien->__get("prenom"),
                $praticien->__get("ville"),
                $praticien->__get("email"),
                $praticien->__get("specialite")
            );
        }, $praticiens);
    }
}