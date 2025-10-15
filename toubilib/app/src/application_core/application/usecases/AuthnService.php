<?php

namespace toubilib\core\application\usecases;
use toubilib\core\application\ports\api\dtos\ProfileDTO as ProfileDTO;
use toubilib\core\application\ports\api\dtos\CredentialsDTO as CredentialsDTO;
use toubilib\core\application\ports\AuthnServiceInterface as AuthnServiceInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\AuthnRepositoryInterface as AuthnRepositoryInterface;


class AuthnService implements AuthnServiceInterface{

    private AuthnRepositoryInterface $authnRepository;

    public function __construct(AuthnRepositoryInterface $authnRepository)
    {
        $this->authnRepository = $authnRepository;
    }

    public function byCredentials(CredentialsDTO $credentials) : ?ProfileDTO{
        $user = $this->authnRepository->findUserByEmail($credentials->__get('email'));
        if (!$user){
            return null;
        }
        if(!password_verify($credentials->__get('password'), $user->__get('password'))){
            return null;
        }
        return new ProfileDTO(
            $user->__get('id'),
            $user->__get('email'),
            $user->__get('role')
        );
    }

    public function findById(String $id) : ?ProfileDTO{
        $user = $this->authnRepository->findById($id);
        if(!$user){
            return null;
        }
        return new ProfileDTO(
            $user->__get('id'),
            $user->__get('email'),
            $user->__get('role')
        );
    }
}