<?php

namespace toubilib\core\application\ports\spi\repositoryInterfaces;

interface PracticienRepositoryInterface{
    
    public function findPracticiens() : array;
}