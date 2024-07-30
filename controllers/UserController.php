<?php
use \Firebase\JWT\JWT;

class UserController{
    // attributs 
    private $nom;
    private $prenom;
    private $email;
    private $password;

    // constructeur
    public function __construct($lastName, $firstName, $mail, $mdp){
        $this->nom      = $lastName;
        $this->prenom   = $firstName;
        $this->email    = $mail;
        $this->password = password_hash($mdp, PASSWORD_DEFAULT);
    }

    // fonction pour s'inscrire
    public function register(){
        if(UserModel::inscription($this->prenom, $this->nom, $this->email, $this->password)){
            echo json_encode([
                "status"  => 201,
                "message" => "utilisateur cree!",
            ]);
        }else{
            echo json_encode([
                "status"  => 500,
                "message" => "erreur serveur!",
            ]);
        }
    }

    // methode pour connexter un user
    public static function login($email, $password, $key){
        $user = UserModel::connexion($email);
        if(empty($user)){ // l'email n'existe pas
            echo json_encode([
                "status"  => 404,
                "message" => "cet utilisateur n'existe pas"
            ]);
        }else{ // l'eamil existe
            if(password_verify($password, $user['password'])){ 
                unset($user['password']);
                $payload = array(
                    "iss" => "http://example.org",
                    "aud" => "http://example.com",
                    "iat" => time(),
                    "nbf" => time(),
                    "exp" => time() + 3600,
                    "userId" => $user['id']
                );
                // generation du token
                $jwt = JWT::encode($payload, $key, 'HS256');
                // retourner les info
                echo json_encode([
                    "status"  => 200,
                    "message" => "Bienvenue",
                    "data"    => $user,
                    "token"   => $jwt
                ]);
            }else{
                echo json_encode([
                    "status"  => 404,
                    "message" => "cet utilisateur n'existe pas"
                ]);
            }
        }
    }
}