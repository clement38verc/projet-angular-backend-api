<?php
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;
require_once "models/TaskModel.php";

class TaskController{
    // pour ajouter une tache
    public static function addTask($taskName, $description,$key, $token){
        // decoder le token
        try {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));

            if(TaskModel::saveTask($taskName, $description,$decoded->userId)){
                echo json_encode([
                    "status"  => 201,
                    "message" => "tache crée...",
                ]);
            }else{
                echo json_encode([
                    "status"  => 500,
                    "message" => "erreur serveur...",
                ]);
            }
        } catch (\Firebase\JWT\ExpiredException $e) {
            echo json_encode([
                "message" => "Token expire ou invalide"
            ]);
        } 
    }

    // pour supprimer une tache
    public static function remove($id){
        if(TaskModel::remove($id)){
            echo json_encode([
                "status"  => 200,
                "message" => "Tache supprimee...",
            ]);
        }else{
            echo json_encode([
                "status"  => 500,
                "message" => "erreur serveur...",
            ]);
        }
    }

    // pour mettre une tache comme terminee
    public static function endTask($id){
        if(TaskModel::endTask($id)){
            echo json_encode([
                "status"  => 200,
                "message" => "Tache terminee...",
            ]);
        }else{
            echo json_encode([
                "status"  => 500,
                "message" => "erreur serveur...",
            ]);
        }
    }

    // obtenir la liste des tache d'un utilisateur
    public static function getUserTask($key, $token){
        $decoded = JWT::decode($token, new Key($key, 'HS256')); 

        // decoder le token
        try {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));

            $taskList = TaskModel::getUserTask($decoded->userId);

            echo json_encode([
                "status"  => 200,
                "data" => $taskList,
            ]);

        } catch (\Firebase\JWT\ExpiredException $e) {
            echo json_encode([
                "message" => "Token expire ou invalide"
            ]);
        } 

        
    }

    public static function update($taskName, $description,$key, $token, $id){
        // decoder le token
        try {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));

            if(TaskModel::update($taskName, $description,$id)){
                echo json_encode([
                    "status"  => 201,
                    "message" => "tache modifiée...",
                ]);
            }else{
                echo json_encode([
                    "status"  => 500,
                    "message" => "erreur serveur...",
                ]);
            }
        } catch (\Firebase\JWT\ExpiredException $e) {
            echo json_encode([
                "message" => "Token expire ou invalide"
            ]);
        } 
    }
}