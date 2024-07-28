<?php

header('Access-Control-Allow-Origin: http://localhost:63342/*');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once('../../sqlMain.php');

$sqlMain = new MainSql();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $request = $_POST;

    $errors = [];

    if(empty($request['username'])){
        $errors[] = ['code' => 1, 'message' => "You don't fill username field!"];
    }


    if (!empty($request['username'])) {
        $isUsernameExists = $sqlMain->checkUsernameClients($request['username']) || $sqlMain->checkUsernameAdmin($request['username']);
        if ($isUsernameExists) {
            $errors[] = ['code' => 2, 'message' => "This username already exists!"];
        }
    }


    if(empty($request['firstname'])){
        $errors[] = ['code' => 3, 'message' => "You don't fill firstname field!"];
    }
    if(empty($request['lastname'])){
        $errors[] = ['code' => 4, 'message' => "You don't fill secondname field!"];
    }


    if(empty($request['email'])){
        $errors[] = ['code' => 5, 'message' => "You don't fill email field!"];
    }
    if(!empty($request['email']) && !filter_var($request['email'], FILTER_VALIDATE_EMAIL)){
        $errors[] = ['code' => 6, 'message' => "Email is not valid!"];
    }


    if(empty($request['password'])){
        $errors[] = ['code' => 7, 'message' => "You don't fill password field!"];
    }
    if(empty($request['re-password'])){
        $errors[] = ['code' => 8, 'message' => "You don't fill re-password field!"];
    }
    if(!empty($request['password']) && !empty($request['re-password'])){
        if($request['password']  !== $request['re-password']){
            $errors[] = ['code' => 8, 'message' => "Passwords is not equal!"];
        }
    }

    if(!empty($errors)){
        echo json_encode(['status' => false, 'errors' => $errors]);
        die();
    }
    else{
        if($res = $sqlMain->addClient($request)){
            echo json_encode(['status' => true, 'url' => "https://funrniturefactory.userbliss.org/auth/login/login.php"]);
        }
    }
}

