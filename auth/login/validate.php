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
        $usernameExists = $sqlMain->checkUsernameAdmin($request['username']) ||
            $sqlMain->checkUsernameClients($request['username']);

        if (!$usernameExists) {
            $errors[] = ['code' => 2, 'message' => 'Username does not exist!'];
        }
        else{
            if (!empty($request['password'])) {
                $passwordExists = $sqlMain->checkPasswordClient($request['username'], $request['password']) ||
                    $sqlMain->checkPasswordAdmin($request['username'], $request['password']);

                if (!$passwordExists) {
                    $errors[] = ['code' => 4, 'message' => 'Password is incorrect!'];
                }
            }
        }
    }

    if(empty($request['password'])){
        $errors[] = ['code' => 3, 'message' => "You don't fill password field!"];
    }


    if(!empty($errors)){
        echo json_encode(['status' => false, 'errors' => $errors]);
        die();
    }
    else{
        if($res = $sqlMain->checkPasswordAdmin($request['username'], $request['password'])){
            echo json_encode(['status' => true, 'url' => "https://funrniturefactory.userbliss.org/view/mainPage/menu/index.php?admin={$request['username']}"]);
        }
        if($res = $sqlMain->checkPasswordClient($request['username'], $request['password'])){
            echo json_encode(['status' => true, 'url' => "https://funrniturefactory.userbliss.org/view/mainPage/menu/index.php?client={$request['username']}"]);
        }
    }
}