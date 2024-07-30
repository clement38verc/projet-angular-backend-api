<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

use \Firebase\JWT\JWT;

require_once "controllers/UserController.php";
require_once "controllers/TaskController.php";

require_once "models/UserModel.php";
require 'vendor/autoload.php';

require_once "includes/fonction.php";

$key = "ma cle secrete";

$url = isset($_GET['url']) ? $_GET['url'] : "home";

switch($url){
    case "register":
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            $data = json_decode(file_get_contents("php://input"), true);
            $nom      = $data['nom'];
            $prenom   = $data['prenom'];
            $email    = $data['email'];
            $password = $data['password'];
            $user = new UserController($nom, $prenom, $email, $password);
            $user->register();
        }
        break;
    case "login":
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            $data = json_decode(file_get_contents("php://input"), true);
            $email    = $data['email'];
            $password = $data['password'];
            UserController::login($email, $password, $key);
        }
        break;
    case "add_task":
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            $token = getBearerToken();
            $data = json_decode(file_get_contents("php://input"), true);
            $taskName    = $data['task_name'];
            $description = $data['description'];
            TaskController::addTask($taskName,$description, $key, $token);
        }
        break;
    case "tasks":
        $token = getBearerToken();
        TaskController::getUserTask($key, $token);
        break;
    case "remove_task":
        TaskController::remove($_GET['id']);
        break;
    case "complete_task":
        TaskController::endTask($_GET['id']);
        break;
    case "update_task":
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            $token = getBearerToken();
            $data = json_decode(file_get_contents("php://input"), true);
            $taskName    = $data['nom'];
            $description = $data['description'];
            // $id          = $data['id'];
            TaskController::update($taskName, $description, $key, $token, $_GET['id']);
        }
        break;
    default:
        echo json_encode([
            "status" => 404,
            "message" => "reesource non trouvee"
        ]);
}