<?php
require_once "includes/dbConnexion.php";
class UserModel{
    public static function inscription($firstName, $lastName, $email, $password){
        // etablir la connxion avec la base de donnee
        $dbConnection = DbConnexion::dbLog();
        // preparer la requete
        $request = $dbConnection->prepare("INSERT INTO users (firstname, lastname, email, password) VALUES (:prenom, :nom, :mail, :mdp)");
        $request->bindParam('prenom', $firstName);
        $request->bindParam('nom', $lastName);
        $request->bindParam('mail', $email);
        $request->bindParam('mdp', $password);

        try{
            $request->execute();
            return true;
        }catch(PDOException $e){
            return $e;
        }
    }

    // verification des infos d'authentification
    public static function connexion($email){
        // etablir la connxion avec la base de donnee
        $dbConnection = DbConnexion::dbLog();
        // preparer la requete pour savoir si l'email existe dans la table users
        $request = $dbConnection->prepare("SELECT * FROM users WHERE email = :mail");
        $request->bindParam(':mail', $email);
        // executer la requete
        try{
            $request->execute();
            $user = $request->fetch(PDO::FETCH_ASSOC);
            return $user;
        }catch(PDOException $e){
            return $e;
        }
    }
}