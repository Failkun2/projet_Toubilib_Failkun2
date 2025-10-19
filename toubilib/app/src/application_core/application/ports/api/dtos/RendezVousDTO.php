<?php

namespace toubilib\core\application\ports\api\dtos;

class RendezVousDTO implements \JsonSerializable{
    private \DateTimeImmutable $dateDebut;
    private \DateTimeImmutable $dateFin;
    private int $duree;
    private int $statut;
    private String $motifVisite;
    private \DateTimeImmutable $dateCreation;

    public function __construct(\DateTimeImmutable $dateDebut, \DateTimeImmutable $dateFin, int $duree = 0, int $statut = 0, String $motifVisite = "", ?\DateTimeImmutable $dateCreation = null){
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
        $this->duree = $duree;
        $this->statut = $statut;
        $this->motifVisite = $motifVisite;
        $this->dateCreation = $dateCreation ?? new \DateTimeImmutable();
    }

    public function jsonSerialize() : array{
        return[
            'dateDebut' => $this->dateDebut,
            'dateFin' => $this->dateFin,
            'duree' => $this->duree,
            'statut' => $this->statut,
            'motifVisite' => $this->motifVisite,
            'dateCreation' => $this->dateCreation,
        ];
    }
}