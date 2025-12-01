<?php

namespace toubilib\core\application\ports\spi;

use PDO;
use toubilib\core\domain\entities\praticien\Praticien as Praticien;
use toubilib\core\application\ports\spi\repositoryInterfaces\PraticienRepositoryInterface as PraticienRepositoryInterface;

class PraticienRepository implements PraticienRepositoryInterface{
    
    private PDO $pdo;

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

    public function findPraticiens() : array{
        $stmt = $this->pdo->query("SELECT p.nom, p.prenom, p.ville, p.email, s.libelle AS specialite, p.telephone, st.adresse, ARRAY_REMOVE(ARRAY_AGG(DISTINCT mv.libelle), NULL) AS motifs, ARRAY_REMOVE(ARRAY_AGG(DISTINCT mp.libelle), NULL) AS moyens_paiement
        FROM praticien p JOIN specialite s ON p.specialite_id = s.id
        LEFT JOIN structure st ON p.structure_id = st.id
        LEFT JOIN praticien2motif pm ON p.id = pm.praticien_id
        LEFT JOIN motif_visite mv ON pm.motif_id = mv.id
        LEFT JOIN praticien2moyen pm2 ON p.id = pm2.praticien_id
        LEFT JOIN moyen_paiement mp ON pm2.moyen_id = mp.id
        GROUP BY p.nom, p.prenom, p.ville, p.email, s.libelle, p.telephone, st.adresse;");
        $praticiens = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function($praticien){
            $motifs = $praticien["motifs"];
            $motifsArray = $motifs ? explode(',', trim($motifs, '{}')) : [];
            $moyens = $praticien["moyens_paiement"];
            $moyensArray = $moyens ? explode(',', trim($moyens, '{}')) : [];

            return new Praticien(
                $praticien["nom"],
                $praticien["prenom"],
                $praticien["ville"],
                $praticien["email"],
                $praticien["specialite"],
                $praticien["telephone"],
                $praticien["adresse"],
                $motifsArray,
                $moyensArray
            );
        }, $praticiens);
    }
    
    public function findById(String $id) : Praticien{
        $stmt = $this->pdo->prepare("SELECT p.nom, p.prenom, p.ville, p.email, s.libelle AS specialite, p.telephone, st.adresse, ARRAY_REMOVE(ARRAY_AGG(DISTINCT mv.libelle), NULL) AS motifs, ARRAY_REMOVE(ARRAY_AGG(DISTINCT mp.libelle), NULL) AS moyens_paiement
        FROM praticien p JOIN specialite s ON p.specialite_id = s.id
        LEFT JOIN structure st ON p.structure_id = st.id
        LEFT JOIN praticien2motif pm ON p.id = pm.praticien_id
        LEFT JOIN motif_visite mv ON pm.motif_id = mv.id
        LEFT JOIN praticien2moyen pm2 ON p.id = pm2.praticien_id
        LEFT JOIN moyen_paiement mp ON pm2.moyen_id = mp.id
        WHERE p.id = :id
        GROUP BY p.nom, p.prenom, p.ville, p.email, s.libelle, p.telephone, st.adresse;");
        $stmt->execute(['id' => $id]);
        $praticien = $stmt->fetch(PDO::FETCH_ASSOC);
        $motifs = $praticien["motifs"];
        $motifsArray = $motifs ? explode(',', trim($motifs, '{}')) : [];
        $moyens = $praticien["moyens_paiement"];
        $moyensArray = $moyens ? explode(',', trim($moyens, '{}')) : [];

        return new Praticien(
            $praticien["nom"],
            $praticien["prenom"],
            $praticien["ville"],
            $praticien["email"],
            $praticien["specialite"],
            $praticien["telephone"],
            $praticien["adresse"],
            $motifsArray,
            $moyensArray
        );
    }

    public function findMotifsByPraticien(String $praticienId) : array{
        $stmt = $this->pdo->prepare("SELECT m.id, m.libelle
        FROM motif_visite m JOIN praticien2motif pm ON m.id = pm.motif_id
        WHERE pm.praticien_id = :praticien_id;");
        $stmt->execute(['praticien_id' => $praticienId]);
        $praticiens = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($praticien) => [
            'id' => (int)$praticien['id'],
            'libelle' => (String)$praticien['libelle'],
        ], $praticiens);
    }

    public function findBySpecialite(String $specialite) : array{
        $stmt = $this->pdo->prepare("SELECT p.nom, p.prenom, p.ville, p.email, s.libelle AS specialite, p.telephone, st.adresse, ARRAY_REMOVE(ARRAY_AGG(DISTINCT mv.libelle), NULL) AS motifs, ARRAY_REMOVE(ARRAY_AGG(DISTINCT mp.libelle), NULL) AS moyens_paiement
        FROM praticien p JOIN specialite s ON p.specialite_id = s.id
        LEFT JOIN structure st ON p.structure_id = st.id
        LEFT JOIN praticien2motif pm ON p.id = pm.praticien_id
        LEFT JOIN motif_visite mv ON pm.motif_id = mv.id
        LEFT JOIN praticien2moyen pm2 ON p.id = pm2.praticien_id
        LEFT JOIN moyen_paiement mp ON pm2.moyen_id = mp.id
        WHERE s.libelle = :specialite
        GROUP BY p.nom, p.prenom, p.ville, p.email, s.libelle, p.telephone, st.adresse;");
        $stmt->execute(['specialite' => $specialite]);
        $praticiens = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(function($praticien){
            $motifs = $praticien["motifs"];
            $motifsArray = $motifs ? explode(',', trim($motifs, '{}')) : [];
            $moyens = $praticien["moyens_paiement"];
            $moyensArray = $moyens ? explode(',', trim($moyens, '{}')) : [];

            return new Praticien(
                $praticien["nom"],
                $praticien["prenom"],
                $praticien["ville"],
                $praticien["email"],
                $praticien["specialite"],
                $praticien["telephone"],
                $praticien["adresse"],
                $motifsArray,
                $moyensArray
            );
        }, $praticiens);
    }

    public function findByVille(String $ville) : array{
        $stmt = $this->pdo->prepare("SELECT p.nom, p.prenom, p.ville, p.email, s.libelle AS specialite, p.telephone, st.adresse, ARRAY_REMOVE(ARRAY_AGG(DISTINCT mv.libelle), NULL) AS motifs, ARRAY_REMOVE(ARRAY_AGG(DISTINCT mp.libelle), NULL) AS moyens_paiement
        FROM praticien p JOIN specialite s ON p.specialite_id = s.id
        LEFT JOIN structure st ON p.structure_id = st.id
        LEFT JOIN praticien2motif pm ON p.id = pm.praticien_id
        LEFT JOIN motif_visite mv ON pm.motif_id = mv.id
        LEFT JOIN praticien2moyen pm2 ON p.id = pm2.praticien_id
        LEFT JOIN moyen_paiement mp ON pm2.moyen_id = mp.id
        WHERE p.ville = :ville
        GROUP BY p.nom, p.prenom, p.ville, p.email, s.libelle, p.telephone, st.adresse;");
        $stmt->execute(['ville' => $ville]);
        $praticiens = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(function($praticien){
            $motifs = $praticien["motifs"];
            $motifsArray = $motifs ? explode(',', trim($motifs, '{}')) : [];
            $moyens = $praticien["moyens_paiement"];
            $moyensArray = $moyens ? explode(',', trim($moyens, '{}')) : [];

            return new Praticien(
                $praticien["nom"],
                $praticien["prenom"],
                $praticien["ville"],
                $praticien["email"],
                $praticien["specialite"],
                $praticien["telephone"],
                $praticien["adresse"],
                $motifsArray,
                $moyensArray
            );
        }, $praticiens);
    }

    public function findBySpecialiteVille(String $specialite, String $ville) : array{
        $stmt = $this->pdo->prepare("SELECT p.nom, p.prenom, p.ville, p.email, s.libelle AS specialite, p.telephone, st.adresse, ARRAY_REMOVE(ARRAY_AGG(DISTINCT mv.libelle), NULL) AS motifs, ARRAY_REMOVE(ARRAY_AGG(DISTINCT mp.libelle), NULL) AS moyens_paiement
        FROM praticien p JOIN specialite s ON p.specialite_id = s.id
        LEFT JOIN structure st ON p.structure_id = st.id
        LEFT JOIN praticien2motif pm ON p.id = pm.praticien_id
        LEFT JOIN motif_visite mv ON pm.motif_id = mv.id
        LEFT JOIN praticien2moyen pm2 ON p.id = pm2.praticien_id
        LEFT JOIN moyen_paiement mp ON pm2.moyen_id = mp.id
        WHERE s.libelle = :specialite
        AND p.ville = :ville
        GROUP BY p.nom, p.prenom, p.ville, p.email, s.libelle, p.telephone, st.adresse;");
        $stmt->execute(['specialite' => $specialite, 'ville' => $ville]);
        $praticiens = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(function($praticien){
            $motifs = $praticien["motifs"];
            $motifsArray = $motifs ? explode(',', trim($motifs, '{}')) : [];
            $moyens = $praticien["moyens_paiement"];
            $moyensArray = $moyens ? explode(',', trim($moyens, '{}')) : [];

            return new Praticien(
                $praticien["nom"],
                $praticien["prenom"],
                $praticien["ville"],
                $praticien["email"],
                $praticien["specialite"],
                $praticien["telephone"],
                $praticien["adresse"],
                $motifsArray,
                $moyensArray
            );
        }, $praticiens);
    }

}