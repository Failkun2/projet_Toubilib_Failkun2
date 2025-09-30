<?php

namespace toubilib\core\domain\entities\praticien;


class Praticien
{
 
    private string $nom;
    private string $prenom;
    private string $ville;
    private string $email;
    private string $specialite;
    private string $telephone;
    private string $adresse;
    private array $motifs;
    private array $moyensPaiement;

    public function __construct(string $nom, string $prenom, string $ville, string $email, string $specialite, string $telephone = "", string $adresse = "", array $motifs = [], array $moyensPaiement = []){
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->ville = $ville;
        $this->email = $email;
        $this->specialite = $specialite;
        $this->telephone = $telephone;
        $this->adresse = $adresse;
        $this->motifs = $motifs;
        $this->moyensPaiement = $moyensPaiement;
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
            case "telephone":
                $res = $this->telephone;
                break;
            case "adresse":
                $res = $this->adresse;
                break;
            case "motifs":
                $res = $this->motifs;
                break;
            case "moyensPaiement":
                $res = $this->moyensPaiement;
                break;
            default:
                break;
        }
        return $res;
    }
}