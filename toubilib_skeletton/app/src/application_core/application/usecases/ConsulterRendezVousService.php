<?php

namespace toubilib\core\application\usecases;

use toubilib\core\domain\entities\ConsulterRendezVousServiceInterface as ConsulterRendezVousServiceInterface;
use toubilib\core\application\ports\api\dtos\RendezVousDTO as RendezVousDTO;

class ConsulterRendezVousService implements ConsulterRendezVousServiceInterface
{
    private RendezVousRepositoryInterface $rdvRepository;

    public function __construct(RendezVousRepositoryInterface $rdvRepository)
    {
        $this->rdvRepository = $rdvRepository;
    }

    public function afficherPraticien(int $id): PraticienDTO {
    	$rdv = $this->rdvRepository->findById($id);

        return new PraticienDTO(
            $rdv->__get("dateDebut"),
            $rdv->__get("dateFin"),
            $rdv->__get("duree"),
            $rdv->__get("statut"),
            $rdv->__get("motifVisite"),
            $rdv->__get("dateCreation")
        );
    }
}