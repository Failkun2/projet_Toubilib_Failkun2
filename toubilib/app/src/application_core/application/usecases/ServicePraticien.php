<?php

namespace toubilib\core\application\usecases;

use toubilib\core\application\ports\ServicePraticienInterface as ServicePraticienInterface;
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
                $praticien->__get("specialite"),
                $praticien->__get("telephone"),
                $praticien->__get("adresse"),
                $praticien->__get("motifs"),
                $praticien->__get("moyensPaiement")
            );
        }, $praticiens);
    }

    public function filtrerParSpecialite(String $specialite) : array{
        $praticiens = $this->praticienRepository->findBySpecialite($specialite);

        return array_map(function($praticien){
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
        }, $praticiens);
    }

    public function filtrerParVille(String $ville) : array{
        $praticiens = $this->praticienRepository->findByVille($ville);

        return array_map(function($praticien){
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
        }, $praticiens);
    }

    public function filtrerParSpecialiteVille(String $specialite, String $ville) : array{
        $praticiens = $this->praticienRepository->findBySpecialiteVille($specialite, $ville);

        return array_map(function($praticien){
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
        }, $praticiens);
    }
}