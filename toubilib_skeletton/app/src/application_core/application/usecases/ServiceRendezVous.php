<?php

namespace toubilib\core\application\usecases;

use toubilib\core\domain\entities\ServiceRendezVousInterface as ServiceRendezVousInterface;
use toubilib\core\application\ports\api\dtos\RendezVousDTO as RendezVousDTO;
use toubilib\core\application\ports\api\dtos\InputRendezVousDTO as InputRendezVousDTO;
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

    public function listerCrenaux(int $praticien_id, \DateTimeImmutable $debut, \DateTimeImmutable $fin): array {
    	$rendezVous = $this->rdvRepository->findRDVByPraticienPeriod($praticien_id, $debut, $fin);

        return array_map(function($rdv){
            return new RendezVousDTO(
                $rdv->__get("dateDebut"),
                $rdv->__get("dateFin")
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
        if(!in_array($dto->__get('motifVisite'), $motifs, true)){
            $errors['motifVisite'] = 'motif introuvable';
        }

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
            $errors['Disponibilite'] = 'Praticien indisponible';
        }

        if(!empty($errors)){
            throw new ValidationException($errors);
        }

        $id = Uuid::uuid4()->toString();

        $rdvData = [
            'id' => $id,
            'praticienId' => __get('praticienId'),
            'patientId' => __get('patientId'),
            'patientEmail' => $patient->__get('email'),
            'dateDebut' => $debut,
            'dateFin' => $fin,
            'duree' => __get('duree'),
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
}