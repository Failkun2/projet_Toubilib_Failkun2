<?php

namespace toubilib\core\application\ports\api\dtos;


class PatientDTO implements \JsonSerializable{
 
    private string $nom;
    private string $prenom;
    private string $date_naissance;
    private string $email;
    private string $telephone;

    public function __construct(string $nom, string $prenom, string $date_naissance, string $email, string $telephone){
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->date_naissance = $date_naissance;
        $this->email = $email;
        $this->telephone = $telephone;
    }

    public function jsonSerialize() : array{
        return[
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'date_naissance' => $this->ville,
            'email' => $this->email,
            'telephone' => $this->telephone,
        ];
    }
}