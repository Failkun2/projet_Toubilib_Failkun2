<?php

namespace toubilib\core\application\ports;

interface ServicePraticienInterface{
    public function listerPraticiens() : array;
    public function filtrerParSpecialite(String $specialite) : array;
    public function filtrerParVille(String $ville) : array;
    public function filtrerParSpecialiteVille(String $specialite, String $ville) : array;
}