<?php

namespace toubilib\core\application\usecases;
use toubilib\core\application\ports\api\dtos\ProfileDTO as ProfileDTO;
use toubilib\core\application\ports\AuthzServiceInterface as AuthzServiceInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\RendezVousRepositoryInterface as RendezVousRepositoryInterface;

class AuthzService implements AuthzServiceInterface{

    private RendezVousRepositoryInterface $rdvRepository;

    public function __construct(RendezVousRepositoryInterface $rdvRepository)
    {
        $this->rdvRepository = $rdvRepository;
    }


    public function authzConsulterAgenda(ProfileDTO $profil, String $praticienId) : bool{
        return $profil->__get('role') === 10 && $profil->__get('id') === $praticienId;
    }

    public function authzCreerRendezVous(ProfileDTO $profil) : bool{
        return $profil->__get('role') === 1;
    }

    public function authzConsulterRendezVous(ProfileDTO $profil) : bool{
        return $profil->__get('role') === 1 || $profil->__get('role') === 10;
    }

    public function authzAnnulerRendezVous(ProfileDTO $profil) : bool{
        return $profil->__get('role') === 1 || $profil->__get('role') === 10;
    }

    public function authzHonorerRendezVous(ProfileDTO $profil, String $rdvId) : bool{
        if($profil->__get('role') !== 10){
            return false;
        }

        $rdv = $this->rdvRepository->findById($rdvId);
        if(!$rdv){
            return false;
        }

        return $profil->__get('id') === $rdv->__get('idPraticien');
    }

    public function authzNonHonorerRendezVous(ProfileDTO $profil, String $rdvId) : bool{
        if($profil->__get('role') !== 10){
            return false;
        }

        $rdv = $this->rdvRepository->findById($rdvId);
        if(!$rdv){
            return false;
        }

        return $profil->__get('id') === $rdv->__get('idPraticien');
    }

    public function authzConsulterHistorique(ProfileDTO $profil, String $patientId) : bool{
        return $profil->__get('role') === 1 && $profil->__get('id') === $patientId;
    }

    public function authzCreerIndisponibilite(ProfileDTO $profil, String $praticienId) : bool{
        return $profil->__get('role') === 10 && $profil->__get('id') === $praticienId;
    }
}