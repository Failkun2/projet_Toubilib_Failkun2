<?php

namespace toubilib\core\application\ports\api\dtos;

class PraticienDTO{
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

    public function Serialise_JSON() : array{
        return[
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'ville' => $this->ville,
            'email' => $this->email,
            'specialite' => $this->specialite,
            'telephone' => $this->telephone,
            'adresse' => $this->adresse,
            'motifs' => $this->motifs,
            'moyensPaiement' => $this->moyensPaiement,
        ];
    }
}