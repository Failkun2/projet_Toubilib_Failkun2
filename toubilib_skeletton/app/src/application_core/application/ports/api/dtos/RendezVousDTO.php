<?php

namespace toubilib\core\application\ports\api\dtos;

class RendezVousDTO{
    private \DateTimeImmutable $dateDebut;
    private \DateTimeImmutable $dateFin;

    public function __construct(\DateTimeImmutable $dateDebut, \DateTimeImmutable $dateFin){
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
    }

    public function Serialise_JSON() : array{
        return[
            'dateDebut' => $this->dateDebut,
            'dateFin' => $this->dateFin,
        ];
    }
}