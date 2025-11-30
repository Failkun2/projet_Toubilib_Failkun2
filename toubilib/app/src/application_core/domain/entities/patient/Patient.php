<?php

namespace toubilib\core\domain\entities\patient;


class Patient
{
 
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

    public function __get($attribut){
        $res = "";
        switch($attribut){
            case "nom":
                $res = $this->nom;
                break;
            case "prenom":
                $res = $this->prenom;
                break;
            case "dateNaissance":
                $res = $this->ville;
                break;
            case "email":
                $res = $this->email;
                break;
            case "telephone":
                $res = $this->telephone;
                break;
            default:
                break;
        }
        return $res;
    }
}