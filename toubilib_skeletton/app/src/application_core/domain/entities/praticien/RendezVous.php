<?php

namespace toubilib\core\domain\entities\praticien;


class RendezVous
{
 
    private \DateTimeImmutable $dateDebut;
    private \DateTimeImmutable $dateFin;

    public function __construct(\DateTimeImmutable $dateDebut, \DateTimeImmutable $dateFin){
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
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
            default:
                break;
        }
        return $res;
    }
}