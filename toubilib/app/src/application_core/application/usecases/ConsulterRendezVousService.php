<?php

namespace toubilib\core\application\usecases;

use toubilib\core\domain\entities\ConsulterRendezVousServiceInterface as ConsulterRendezVousServiceInterface;
use toubilib\core\application\ports\api\dtos\RendezVousDTO as RendezVousDTO;
use toubilib\core\application\ports\spi\repositoryInterfaces\RendezVousRepositoryInterface as RendezVousRepositoryInterface;


class ConsulterRendezVousService implements ConsulterRendezVousServiceInterface
{
    private RendezVousRepositoryInterface $rdvRepository;

    public function __construct(RendezVousRepositoryInterface $rdvRepository)
    {
        $this->rdvRepository = $rdvRepository;
    }

    public function afficherRendezVous(String $id) : RendezVousDTO {
    	$rdv = $this->rdvRepository->findById($id);

        return new RendezVousDTO(
            $rdv->__get("dateDebut"),
            $rdv->__get("dateFin"),
            $rdv->__get("duree"),
            $rdv->__get("statut"),
            $rdv->__get("motifVisite"),
            $rdv->__get("dateCreation")
        );
    }
}