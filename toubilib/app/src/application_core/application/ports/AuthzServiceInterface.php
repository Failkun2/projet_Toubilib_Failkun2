<?php

namespace toubilib\core\application\ports;
use toubilib\core\application\ports\api\dtos\ProfileDTO as ProfileDTO;

interface AuthzServiceInterface{
    public function authzConsulterAgenda(ProfileDTO $profil, String $praticienId) : bool;

    public function authzCreerRendezVous(ProfileDTO $profil) : bool;

    public function authzConsulterRendezVous(ProfileDTO $profil) : bool;

    public function authzAnnulerRendezVous(ProfileDTO $profil) : bool;

    public function authzHonorerRendezVous(ProfileDTO $profil, String $praticienId) : bool;

    public function authzNonHonorerRendezVous(ProfileDTO $profil, String $praticienId) : bool;

}