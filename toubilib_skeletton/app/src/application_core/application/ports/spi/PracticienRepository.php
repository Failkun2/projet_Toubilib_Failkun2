<?php

namespace toubilib\core\application\ports\spi;

use PDO;
use toubilib\core\domain\entities\praticien\Practicien as Practicien;
use toubilib\core\application\ports\spi\repositoryInterfaces\PracticienRepositoryInterface as PracticienRepositoryInterface;

class PracticienRepository implements PracticienRepositoryInterface{
    
    private PDO $pdo;

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

    public function findPracticiens() : array{
        $stmt = $this->pdo->query("SELECT nom, prenom, ville, email, specialite_id FROM practicien;");
        $practiciens = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function($practicien){
            return new PracticienDTO(
                $practicien["nom"],
                $practicien["prenom"],
                $practicien["ville"],
                $practicien["email"],
                $practicien["specialite_id"]
            );
        }, $practiciens);
    }
}