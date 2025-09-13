<?php

namespace toubilib\core\application\ports\api\dtos;

use toubilib\core\domain\entities\praticien\ServicePracticien as ServicePracticien;

class PracticienDTO{
    private string $nom;
    private string $prenom;
    private string $ville;
    private string $email;
    private string $specialite;

    public function __construct(string $nom, string $prenom, string $ville, string $email, string $specialite){
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->ville = $ville;
        $this->email = $email;
        $this->specialite = $specialite;
    }
}