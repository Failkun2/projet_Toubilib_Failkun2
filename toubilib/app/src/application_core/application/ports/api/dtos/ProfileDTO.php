<?php

namespace toubilib\core\application\ports\api\dtos;

class ProfileDTO implements \JsonSerializable{
    private String $id;
    private String $email;
    private int $role;
    
    public function __construct(String $id, String $email, int $role){
        $this->id = $id;
        $this->email = $email;
        $this->role = $role;
    }

    public function __get($attribut){
        $res = "";
        switch($attribut){
            case "id":
                $res = $this->id;
                break;
            case "email":
                $res = $this->email;
                break;
            case "role":
                $res = $this->role;
                break;
            default:
                break;
        }
        return $res;
    }

    public function jsonSerialize() : array{
        return[
            'id' => $this->id,
            'email' => $this->email,
            'role' => $this->role,
        ];
    }
}