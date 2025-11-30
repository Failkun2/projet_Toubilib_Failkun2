<?php

namespace toubilib\core\application\ports\api\dtos;


class PatientDTO implements \JsonSerializable{
 
    private string $nom;
    private string $prenom;
    private string $dateNaissance;
    private string $email;
    private string $telephone;

    public function __construct(string $nom, string $prenom, string $dateNaissance, string $email, string $telephone){
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->dateNaissance = $dateNaissance;
        $this->email = $email;
        $this->telephone = $telephone;
    }

    public function jsonSerialize() : array{
        return[
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'dateNaissance' => $this->dateNaissance,
            'email' => $this->email,
            'telephone' => $this->telephone,
        ];
    }
}