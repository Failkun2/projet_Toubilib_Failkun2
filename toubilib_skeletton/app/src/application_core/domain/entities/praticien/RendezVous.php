<?php

namespace toubilib\core\domain\entities\praticien;


class RendezVous
{
 
    private \DateTimeImmutable $dateDebut;
    private \DateTimeImmutable $dateFin;
    private int $duree;
    private int $statut;
    private String $motifVisite;
    private \DateTimeImmutable $dateCreation;

    public function __construct(\DateTimeImmutable $dateDebut, \DateTimeImmutable $dateFin, int $duree = 0, int $statut = 0, String $motifVisite = "", \DateTimeImmutable $dateCreation = null){
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
        $this->duree = $duree;
        $this->statut = $statut;
        $this->motifVisite = $motifVisite;
        $this->dateCreation = $dateCreation;
    }

    public function __get($attribut){
        $res = "";
        switch($attribut){
            case "dateDebut":
                $res = $this->dateDebut;
                break;
            case "dateFin":
                $res = $this->dateFin;
                break;
            case "duree":
                $res = $this->duree;
                break;
            case "statut":
                $res = $this->statut;
                break;
            case "motifVisite":
                $res = $this->motifVisite;
                break;
            case "dateCreation":
                $res = $this->dateCreation;
                break;
            default:
                break;
        }
        return $res;
    }
}