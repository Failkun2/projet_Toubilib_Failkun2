<?php

namespace toubilib\core\domain\entities\praticien;


class Praticien
{
 
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

    public function __get($attribut){
        $res = "";
        switch($attribut){
            case "nom":
                $res = $this->nom;
                break;
            case "prenom":
                $res = $this->prenom;
                break;
            case "ville":
                $res = $this->ville;
                break;
            case "email":
                $res = $this->email;
                break;
            case "specialite":
                $res = $this->specialite;
                break;
            default:
                break;
        }
        return $res;
    }
}