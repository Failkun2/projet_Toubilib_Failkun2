<?php

namespace toubilib\core\application\ports\api\dtos;
use toubilib\core\application\ports\api\dtos\ProfileDTO as ProfileDTO;

class AuthnDTO implements \JsonSerializable{
    private ProfileDTO $profil;
    private String $accessToken;
    private String $refreshToken;
    
    public function __construct(ProfileDTO $profil, String $accessToken, String $refreshToken){
        $this->profil = $profil;
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
    }

    public function __get($attribut){
        $res = "";
        switch($attribut){
            case "profil":
                $res = $this->profil;
                break;
            case "accessToken":
                $res = $this->accessToken;
                break;
            case "refreshToken":
                $res = $this->refreshToken;
                break;
            default:
                break;
        }
        return $res;
    }

    public function jsonSerialize() : array{
        return[
            'profil' => $this->profil->jsonSerialize(),
            'accessToken' => $this->accessToken,
            'refreshToken' => $this->refreshToken,
        ];
    }
}