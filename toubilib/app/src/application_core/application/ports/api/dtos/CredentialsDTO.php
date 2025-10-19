<?php

namespace toubilib\core\application\ports\api\dtos;

class CredentialsDTO implements \JsonSerializable{
    private String $email;
    private String $password;
    
    public function __construct(String $email, String $password){
        $this->email = $email;
        $this->password = $password;
    }

    public function __get($attribut){
        $res = "";
        switch($attribut){
            case "email":
                $res = $this->email;
                break;
            case "password":
                $res = $this->password;
                break;
            default:
                break;
        }
        return $res;
    }

    public function jsonSerialize() : array{
        return[
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}