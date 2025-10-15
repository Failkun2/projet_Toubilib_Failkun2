<?php

namespace toubilib\core\application\ports\spi;

use PDO;
use toubilib\core\domain\entities\User as User;
use toubilib\core\application\ports\spi\repositoryInterfaces\AuthnRepositoryInterface as AuthnRepositoryInterface;

class AuthnRepository implements AuthnRepositoryInterface{
        
    private PDO $pdo;

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

    public function findUserByEmail(String $email) : ?User{
        $stmt = $this->pdo->prepare("SELECT id, email, password, role 
        FROM users
        WHERE email = :email");
        $stmt->execute(['email' => $email,]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$user){
            return null;
        }
        return new User(
            $user["id"],
            $user["email"],
            $user["password"],
            (int)$user["role"]
        );
    }

    public function findById(String $id) : ?User{
        $stmt = $this->pdo->prepare("SELECT id, email, password, role 
        FROM users
        WHERE id = :id");
        $stmt->execute(['id' => $id,]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$user){
            return null;
        }
        return new User(
            $user["id"],
            $user["email"],
            $user["password"],
            (int)$user["role"]
        );
    }
}