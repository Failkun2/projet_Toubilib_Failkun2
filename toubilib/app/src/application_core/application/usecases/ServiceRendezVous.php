<?php

namespace toubilib\core\application\usecases;

use toubilib\core\application\ports\ServiceRendezVousInterface as ServiceRendezVousInterface;
use toubilib\core\application\ports\api\dtos\RendezVousDTO as RendezVousDTO;
use toubilib\core\application\ports\api\dtos\InputRendezVousDTO as InputRendezVousDTO;
use toubilib\core\application\ports\api\dtos\PatientDTO as PatientDTO;
use toubilib\core\application\ports\api\dtos\PraticienDTO as PraticienDTO;
use toubilib\core\application\exceptions\ValidationException as ValidationException;
use Ramsey\Uuid\Uuid;
use toubilib\core\application\ports\spi\repositoryInterfaces\RendezVousRepositoryInterface as RendezVousRepositoryInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\PraticienRepositoryInterface as PraticienRepositoryInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\PatientRepositoryInterface as PatientRepositoryInterface;
use toubilib\core\application\exceptions\RendezVousInvalideException as RendezVousInvalideException;


class ServiceRendezVous implements ServiceRendezVousInterface
{
    private RendezVousRepositoryInterface $rdvRepository;
    private PraticienRepositoryInterface $praticienRepository;
    private PatientRepositoryInterface $patientRepository;


    public function __construct(RendezVousRepositoryInterface $rdvRepository, PraticienRepositoryInterface $praticienRepository, PatientRepositoryInterface $patientRepository)
    {
        $this->rdvRepository = $rdvRepository;
        $this->praticienRepository = $praticienRepository;
        $this->patientRepository = $patientRepository;
    }

    public function listerCrenaux(String $praticien_id, \DateTimeImmutable $debut, \DateTimeImmutable $fin): array {
    	$rendezVous = $this->rdvRepository->findRDVByPraticienPeriod($praticien_id, $debut, $fin);

        return array_map(function($rdv){
            return new RendezVousDTO(
                $rdv->__get("dateDebut"),
                $rdv->__get("dateFin"),
                $rdv->__get("duree"),
                $rdv->__get("statut"),
                $rdv->__get("motifVisite"),
                $rdv->__get("dateCreation")
            );
        }, $rendezVous);
    }

    public function creerRendezVous(InputRendezVousDTO $dto) : String{
        $errors = [];

        $praticien = $this->praticienRepository->findById($dto->__get('praticienId'));
        if(!$praticien){
            $errors['praticien'] = 'praticien introuvable';
        }

        $patient = $this->patientRepository->findById($dto->__get('patientId'));
        if(!$patient){
            $errors['patient'] = 'patient introuvable';
        }

        $motifs = $this->praticienRepository->findMotifsByPraticien($dto->__get('praticienId'));
        $libelles = array_column($motifs, 'libelle');
        if(!in_array($dto->__get('motifVisite'), $libelles, true)){
            $errors['motifVisite'] = 'motif introuvable';
        }

        $duree = $dto->__get('duree');
        $debut = $dto->__get('dateDebut');
        $fin = $debut->modify("+{$duree} minutes");
        $jour = (int)$debut->format('N');
        if($jour > 5){
            $errors['jourSemaine'] = 'Rendez Vous impossible le weekend';
        }

        $heureDebut = (int)$debut->format('H');
        $heureFin = (int)$fin->format('H');
        if($heureDebut < 8){
            $errors['horraireMatin'] = 'Ouvre à 8h';
        }
        if($heureFin > 19){
            $errors['HorraireSoir'] = 'Ferme à 19h';
        }

        $overlapping = $this->rdvRepository->countOverlapping($dto->__get('praticienId'), $debut, $fin);
        if($overlapping > 0){
            $errors['Disponibilite_rdv'] = 'Praticien en rdv';
        }

        $indisponibilite = $this->rdvRepository->verifierIndisponibilite($dto->__get('praticienId'), $debut, $fin);
        if($indisponibilite > 0){
            $errors['Disponibilite'] = 'Praticien indisponible';
        }

        var_dump($errors);
        if(!empty($errors)){
            throw new ValidationException($errors);
        }

        $id = Uuid::uuid4()->toString();

        $rdvData = [
            'id' => $id,
            'praticienId' => $dto->__get('praticienId'),
            'patientId' => $dto->__get('patientId'),
            'patientEmail' => $patient->__get('email'),
            'dateDebut' => $debut,
            'dateFin' => $fin,
            'duree' => $dto->__get('duree'),
            'statut' => 0,
            'motifVisite' => $dto->__get('motifVisite'),
            'dateCreation' => new \DateTimeImmutable()
        ];

        $this->rdvRepository->createRdv($rdvData);

        return $id;
    }

    public function annulerRendezVous(String $idRdv) : void{
        $rdv = $this->rdvRepository->findById($idRdv);
        if(!$rdv){
            throw new RendezVousInvalideException("Rendez Vous innexistant");
        }
        $rdv->annulerRendezVous();
        $this->rdvRepository->updateStatut($idRdv, $rdv);
    }

    public function consulterAgenda(String $praticienId, ?\DateTimeImmutable $debut = null, ?\DateTimeImmutable $fin = null) : array{
        if(!$debut){
            $debut = new \DateTimeImmutable('today 08:00:00');
        }
        if(!$fin){
            $fin = new \DateTimeImmutable('today 19:00:00');
        }
        $rdvs = $this->rdvRepository->findAgendaByPraticien($praticienId, $debut, $fin);
        $result = [];
        foreach($rdvs as $rdv){
            $patient = $this->patientRepository->findById($rdv['patient_id']);
            $praticien = $this->praticienRepository->findById($rdv['praticien_id']);
            $patientDto = new PatientDTO(
                $patient->__get('nom'),
                $patient->__get('prenom'),
                $patient->__get('dateNaissance'),
                $patient->__get('email'),
                $patient->__get('telephone')
            );
            $praticienDto = new PraticienDTO(
                $praticien->__get('nom'),
                $praticien->__get('prenom'),
                $praticien->__get('ville'),
                $praticien->__get('email'),
                $praticien->__get('specialite'),
                $praticien->__get('telephone'),
                $praticien->__get('adresse'),
                $praticien->__get('motifs'),
                $praticien->__get('moyensPaiement')
            );
            $result[] = [
                'id' => $rdv['id'],
                'dateDebut' => $rdv['date_heure_debut'],
                'dateFin' => $rdv['date_heure_fin'],
                'duree' => $rdv['duree'],
                'statut' => $rdv['status'],
                'motif_visite' => $rdv['motif_visite'],
                'patient' => $patientDto,
                'praticien' => $praticienDto
            ];
        }
        return $result;
    }

    public function honorerRendezVous(String $idRdv) : void{
        $rdv = $this->rdvRepository->findById($idRdv);
        if(!$rdv){
            throw new RendezVousInvalideException("Rendez Vous innexistant");
        }
        $rdv->honorerRendezVous();
        $this->rdvRepository->updateStatut($idRdv, $rdv);
    }

    public function nonHonorerRendezVous(String $idRdv) : void{
        $rdv = $this->rdvRepository->findById($idRdv);
        if(!$rdv){
            throw new RendezVousInvalideException("Rendez Vous innexistant");
        }
        $rdv->nonHonorerRendezVous();
        $this->rdvRepository->updateStatut($idRdv, $rdv);
    }

    public function consulterHistorique(String $patientId) : array{
        $rdvs = $this->rdvRepository->findHistoriqueByPatient($patientId);
        $result = [];
        foreach($rdvs as $rdv){
            $patient = $this->patientRepository->findById($rdv['patient_id']);
            $praticien = $this->praticienRepository->findById($rdv['praticien_id']);
            $patientDto = new PatientDTO(
                $patient->__get('nom'),
                $patient->__get('prenom'),
                $patient->__get('dateNaissance'),
                $patient->__get('email'),
                $patient->__get('telephone')
            );
            $praticienDto = new PraticienDTO(
                $praticien->__get('nom'),
                $praticien->__get('prenom'),
                $praticien->__get('ville'),
                $praticien->__get('email'),
                $praticien->__get('specialite'),
                $praticien->__get('telephone'),
                $praticien->__get('adresse'),
                $praticien->__get('motifs'),
                $praticien->__get('moyensPaiement')
            );
            $result[] = [
                'id' => $rdv['id'],
                'dateDebut' => $rdv['date_heure_debut'],
                'dateFin' => $rdv['date_heure_fin'],
                'duree' => $rdv['duree'],
                'statut' => $rdv['status'],
                'motif_visite' => $rdv['motif_visite'],
                'patient' => $patientDto,
                'praticien' => $praticienDto
            ];
        }
        return $result;
    }

    public function creerIndisponibilite(String $praticienId, \DateTimeImmutable $debut, \DateTimeImmutable $fin) : String{


        $id = Uuid::uuid4()->toString();
        
        $this->rdvRepository->addIndisponibilite($id, $praticienId, $debut, $fin);

        return $id;
    }
}