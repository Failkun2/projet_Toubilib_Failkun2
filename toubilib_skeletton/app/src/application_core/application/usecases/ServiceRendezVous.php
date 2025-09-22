<?php

namespace toubilib\core\application\usecases;

use toubilib\core\domain\entities\ServiceRendezVousInterface as ServiceRendezVousInterface;
use toubilib\core\application\ports\api\dtos\RendezVousDTO as RendezVousDTO;

class ServiceRendezVous implements ServiceRendezVousInterface
{
    private PraticienRepositoryInterface $praticienRepository;

    public function __construct(PraticienRepositoryInterface $praticienRepository)
    {
        $this->praticienRepository = $praticienRepository;
    }

    public function listerCrenaux(int $praticien_id, \DateTimeImmutable $debut, \DateTimeImmutable $fin): array {
    	$rendezVous = $this->praticienRepository->findRDVByPraticienPeriod($praticien_id, $debut, $fin);

        return array_map(function($rdv){
            return new RendezVousDTO(
                $rdv->__get("dateDebut"),
                $rdv->__get("dateFin")
            );
        }, $rendezVous);
    }
}