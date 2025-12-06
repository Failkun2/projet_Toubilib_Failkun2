<?php

namespace toubilib\infra\repositories;

use PDO;
use toubilib\core\domain\entities\patient\Patient as Patient;
use toubilib\core\application\ports\spi\repositoryInterfaces\PatientRepositoryInterface as PatientRepositoryInterface;

class PatientRepository implements PatientRepositoryInterface{
    
    private PDO $pdo;

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }
    
    public function findById(String $id) : Patient{
        $stmt = $this->pdo->prepare("SELECT p.nom, p.prenom, p.date_naissance, p.email, p.telephone
        FROM patient p 
        WHERE p.id = :id
        GROUP BY p.nom, p.prenom, p.ville, p.email, p.date_naissance, p.telephone;");
        $stmt->execute(['id' => $id]);
        $patient = $stmt->fetch(PDO::FETCH_ASSOC);
        return new Patient(
            $patient["nom"],
            $patient["prenom"],
            $patient["date_naissance"],
            $patient["email"],
            $patient["telephone"]
        );
    }

    public function creerPatient(string $id, array $data) : void{
        $stmt = $this->pdo->prepare("INSERT INTO patient (id, nom, prenom, date_naissance, email, telephone)
        VALUES(:id, :nom, :prenom, :dateNaissance, :email, :telephone);");
        $stmt->execute([
            'id' => $id, 
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'dateNaissance' => $data['dateNaissance'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
        ]);
    }

}