<?php

namespace toubilib\api\provider;

use toubilib\core\application\ports\spi\repositoryInterfaces\AuthnRepositoryInterface as AuthnRepositoryInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use toubilib\core\application\ports\api\dtos\ProfileDTO as ProfileDTO;
use toubilib\core\application\ports\api\dtos\AuthnDTO as AuthnDTO;
use toubilib\core\application\ports\api\dtos\CredentialsDTO as CredentialsDTO;
use toubilib\core\application\ports\AuthnServiceInterface as AuthnServiceInterface;



class JWTAuthnProvider{
    private String $secret;
    private String $issuer;
    private AuthnServiceInterface $service;

    public function __construct(string $file, AuthnServiceInterface $service){
        $config = parse_ini_file($file);
        $this->secret = $config['secret'];
        $this->issuer = $config['issuer'];
        $this->service = $service;
    }

    public function signIn(CredentialsDTO $credentials) : ?AuthnDTO{
        $profil = $this->service->byCredentials($credentials);
        if(!$profil){
            return null;
        }
        $payload = [
            'iss' => $this->issuer,
            'sub' => $profil->__get('id'),
            'iat' => time(),
            'exp' => time() + 900,
            'upr' => [
                'id' => $profil->__get('id'),
                'email' => $profil->__get('email'),
                'role' => $profil->__get('role')
            ]
        ];

        $accessToken = JWT::encode($payload, $this->secret, 'HS512');

        $refreshPayload = [
            'iss' => $this->issuer,
            'sub' => $profil->__get('id'),
            'iat' => time(),
            'exp' => time() + 3600,
            'refresh' => true
        ];

        $refreshToken = JWT::encode($refreshPayload, $this->secret, 'HS512');
        return new AuthnDTO($profil, $accessToken, $refreshToken);
    }

    public function refresh(String $refreshToken) : ?AuthnDTO{
        try{
            $decoded = JWT::decode($refreshToken, new Key($this->secret, 'HS512'));
            if(!isset($decoded->refresh)){
                return null;
            }

            $profil = $this->service->findById($decoded->sub);
            if(!$profil){
                return null;
            }

            $payload = [
                'iss' => $this->issuer,
                'sub' => $profil->__get('id'),
                'iat' => time(),
                'exp' => time() + 900,
                'upr' => [
                    'id' => $profil->__get('id'),
                    'email' => $profil->__get('email'),
                    'role' => $profil->__get('role')
                ]
            ];

            $newAccessToken = JWT::encode($payload, $this->secret, 'HS512');
            return new AuthnDTO(
                $profil, 
                $newAccessToken, 
                $refreshToken
            );
        } catch(\Throwable $e){
            return null;
        }
    }

    public function verifierToken(String $token) : ?ProfileDTO{
        try{
            $decoded = JWT::decode($token, new Key($this->secret, 'HS512'));
            if(!isset($decoded->upr)){
                return null;
            }
            return new ProfileDTO($decoded->upr->id, $decoded->upr->email, $decoded->upr->role);
        } catch(\Throwable $e){
            return null;
        }
    }
}