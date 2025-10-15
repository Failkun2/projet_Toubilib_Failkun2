<?php

namespace toubilib\core\application\usecases;

use toubilib\core\application\ports\ConsulterPraticienServiceInterface as ConsulterPraticienServiceInterface;
use toubilib\core\application\ports\api\dtos\PraticienDTO as PraticienDTO;
use toubilib\core\application\ports\spi\repositoryInterfaces\PraticienRepositoryInterface as PraticienRepositoryInterface;


class ConsulterPraticienService implements ConsulterPraticienServiceInterface
{
    private PraticienRepositoryInterface $praticienRepository;

    public function __construct(PraticienRepositoryInterface $praticienRepository)
    {
        $this->praticienRepository = $praticienRepository;
    }

    public function afficherPraticien(String $id): PraticienDTO {
    	$praticien = $this->praticienRepository->findById($id);

        return new PraticienDTO(
            $praticien->__get("nom"),
            $praticien->__get("prenom"),
            $praticien->__get("ville"),
            $praticien->__get("email"),
            $praticien->__get("specialite"),
            $praticien->__get("telephone"),
            $praticien->__get("adresse"),
            $praticien->__get("motifs"),
            $praticien->__get("moyensPaiement")
        );
    }
}