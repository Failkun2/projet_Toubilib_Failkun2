<?php

namespace toubilib\core\domain\entities\praticien;


class ServicePracticien implements ServicePracticienInterface{
    function listerPracticiens(array $practiciens){
        foreach($practiciens as $key => $practicien){
            echo "$key - ";
        }
    }
}