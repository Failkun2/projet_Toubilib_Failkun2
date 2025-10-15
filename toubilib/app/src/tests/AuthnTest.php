<?php

use PHPUnit\Framework\TestCase;
use toubilib\api\provider\JWTAuthnProvider as JWTAuthnProvider;
use toubilib\core\application\ports\api\dtos\CredentialsDTO as CredentialsDTO;
use toubilib\core\application\ports\api\dtos\ProfileDTO as ProfileDTO;
use DI\Container;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthnTest extends TestCase{

    private JWTAuthnProvider $provider;

    public function setUp():void{
        $container = new Container();
        $services = require __DIR__ . '\..\..\config\services.php';

        foreach($services as $id => $content){
            $container->set($id, $content);
        }

        $this->provider = $container->get(JWTAuthnProvider::class);
    }

    public function testSignInOK() : void{
        $credentials = new CredentialsDTO('Denis.Teixeira@hotmail.fr', 'Denis.Teixeira');
        $authn = $this->provider->signin($credentials);
        $profil = $authn->__get('profil');
        $this->assertNotNull($authn, 'Profil doit exister');
        $this->assertInstanceOf(ProfileDTO::class, $authn->__get('profil'));
        $this->assertNotEmpty($authn->__get('accessToken'));
        $this->assertNotEmpty($authn->__get('refreshToken'));
        $fichier = parse_ini_file(__DIR__ . '\..\..\config\secret.ini');
        $secret = $fichier['secret'];
        $decoded = JWT::decode($authn->__get('accessToken'), new Key($secret, 'HS512'));
        $this->assertEquals($profil->__get('id'), $decoded->upr->id);
        $this->assertEquals($profil->__get('email'), $decoded->upr->email);
        $this->assertEquals($profil->__get('role'), $decoded->upr->role);
    }

    public function testSignInKO() : void{
        $credentials = new CredentialsDTO('Denis.Teixeira@hotmail.fr', 'paslemdp');
        $authn = $this->provider->signin($credentials);

        $this->assertNull($authn, 'Profil ne doit pas exister');
    }

    public function testRefreshToken() : void{
        $credentials = new CredentialsDTO('Denis.Teixeira@hotmail.fr', 'Denis.Teixeira');
        $authn = $this->provider->signin($credentials);
        $this->assertNotNull($authn, 'Profil doit exister');
        
        sleep(1);
        $refreshed = $this->provider->refresh($authn->__get('refreshToken'));
        $this->assertNotNull($refreshed);
        $this->assertNotSame($authn->__get('accessToken'), $refreshed->__get('accessToken'));
    }

    public function testVerifierToken() : void{
        $credentials = new CredentialsDTO('Denis.Teixeira@hotmail.fr', 'Denis.Teixeira');
        $authn = $this->provider->signin($credentials);

        $profil = $this->provider->verifierToken($authn->__get('accessToken'));
        $this->assertInstanceOf(ProfileDTO::class, $profil);
        $this->assertEquals('Denis.Teixeira@hotmail.fr', $profil->__get('email'));
        $this->assertEquals(1, $profil->__get('role'));
    }
}