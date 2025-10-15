<?php

namespace toubilib\core\domain\entities;

class User{
    private String $id;
    private String $email;
    private String $password;
    private int $role;

    public function __construct(String $id, String $email, String $password, int $role){
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
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
            case "password":
                $res = $this->password;
                break;
            case "role":
                $res = $this->role;
                break;
            default:
                break;
        }
        return $res;
    }
}