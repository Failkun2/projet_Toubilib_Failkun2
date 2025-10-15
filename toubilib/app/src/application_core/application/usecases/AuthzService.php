<?php

namespace toubilib\core\application\usecases;
use toubilib\core\application\ports\api\dtos\ProfileDTO as ProfileDTO;
use toubilib\core\application\ports\AuthzServiceInterface as AuthzServiceInterface;

class AuthzService implements AuthzServiceInterface{
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
}