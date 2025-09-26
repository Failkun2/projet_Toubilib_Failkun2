<?php

namespace toubilib\core\domain\entities\patient;


class Patient
{
 
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

    public function __get($attribut){
        $res = "";
        switch($attribut){
            case "nom":
                $res = $this->nom;
                break;
            case "prenom":
                $res = $this->prenom;
                break;
            case "date_naissance":
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