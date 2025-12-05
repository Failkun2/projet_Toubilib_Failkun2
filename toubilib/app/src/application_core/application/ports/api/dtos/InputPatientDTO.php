<?php

namespace toubilib\core\application\ports\api\dtos;


class InputPatientDTO implements \JsonSerializable{
 
    private String $password;
    private string $nom;
    private string $prenom;
    private string $dateNaissance;
    private string $email;
    private string $telephone;

    public function __construct(array $data){
        $this->password = $data['password'];
        $this->nom = $data['nom'];
        $this->prenom = $data['prenom'];
        $this->dateNaissance = $data['dateNaissance'];
        $this->email = $data['email'];
        $this->telephone = $data['telephone'];
    }

    public function __get($attribut){
        $res = "";
        switch($attribut){
            case "password":
                $res = $this->password;
                break;
            case "nom":
                $res = $this->nom;
                break;
            case "prenom":
                $res = $this->prenom;
                break;
            case "dateNaissance":
                $res = $this->dateNaissance;
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

    public function jsonSerialize() : array{
        return[
            'password' => $this->password,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'dateNaissance' => $this->dateNaissance,
            'email' => $this->email,
            'telephone' => $this->telephone,
        ];
    }
}