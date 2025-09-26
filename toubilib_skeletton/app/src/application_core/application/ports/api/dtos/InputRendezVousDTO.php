<?php

namespace toubilib\core\application\ports\api\dtos;

class InputRendezVousDTO{
    private String $praticienId;
    private String $patientId;
    private \DateTimeImmutable $dateDebut;
    private int $duree;
    private String $motifVisite;

    public function __construct(String $praticienId, String $patientId, DateTimeImmutable $dateDebut, int $duree, String $motifVisite){
        $this->praticienId = $praticienId;
        $this->patientId = $patientId;
        $this->dateDebut = $dateDebut;
        $this->duree = $duree;
        $this->motifVisite = $motifVisite;
    }

    public function __get($attribut){
        $res = "";
        switch($attribut){
            case "dateDebut":
                $res = $this->dateDebut;
                break;
            case "praticienId":
                $res = $this->praticienId;
                break;
            case "duree":
                $res = $this->duree;
                break;
            case "patientId":
                $res = $this->patientId;
                break;
            case "motifVisite":
                $res = $this->motifVisite;
                break;
            default:
                break;
        }
        return $res;
    }
}