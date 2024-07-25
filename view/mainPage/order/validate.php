<?php

header('Access-Control-Allow-Origin: http://localhost:63342/*');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

require_once('../../../sqlMain.php');
$sqlMain = new MainSql();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $request = file_get_contents('php://input');
    $request = json_decode($request, true);

    if($request['action'] === 'search_order'){
        $errors = [];

        if(empty($request['search_username'])){
            $errors[] = ['code' => 1, 'message' => 'You must fill username field!'];
        }
        $current_username = $sqlMain->getUsername();
        if(!empty($request['search_username']) && $request['search_username'] !== $current_username){
            $errors[] = ['code' => 2, 'message' => 'Username is wrong!'];
        }

        if(!empty($errors)){
            echo json_encode(['status' => false, 'errors' => $errors]);
            die();
        }
        else{
            if($res = $sqlMain->getOrdersFor($request['search_username'])){
                echo json_encode(['status' => true, 'data' => $res]);
                die();
            }
        }
    }

    if($request['action'] === 'create_order'){
        $errors = [];

        if(empty($request['create_username'])){
            $errors[] = ['code' => 1, 'message' => 'You must fill username field!'];
        }
        $current_username = $sqlMain->getUsername();
        if(!empty($request['create_username']) && $request['create_username'] !== $current_username){
            $errors[] = ['code' => 2, 'message' => 'This client is not exists!'];
        }
        if(empty($request['create_order_name'])){
            $errors[] = ['code' => 3, 'message' => 'You must fill order name field!'];
        }
        if(empty(htmlspecialchars($request['create_order_description']))){
            $errors[] = ['code' => 4, 'message' => 'You must fill order description field!'];
        }
        if(empty($request['create_perform_to'])){
            $errors[] = ['code' => 5, 'message' => 'You must fill order perform to, field!'];
        }

        if(!empty($errors)){
            echo json_encode(['status' => false, 'errors' => $errors]);
            die();
        }
        else{
            if($res = $sqlMain->addOrder($request)){
                echo json_encode(['status' => true]);
                die();
            }
        }
    }

    if($request['action'] === 'update_order'){
        $errors = [];
        if(empty($request['update_order_name'])){
            $errors[] = ['code' => 1, 'message' => 'You must fill order name field!'];
        }
        if(empty(htmlspecialchars($request['update_order_description']))){
            $errors[] = ['code' => 2, 'message' => 'You must fill order description field!'];
        }
        if(empty($request['update_perform_to'])){
            $errors[] = ['code' => 3, 'message' => 'You must fill order perform to, field!'];
        }

        if(!empty($errors)){
            echo json_encode(['status' => false, 'errors' => $errors]);
            die();
        }
        else{
            if($res = $sqlMain->updateOrder($request)){
                echo json_encode(['status' => true, 'data' => $res]);
                die();
            }
        }
    }

    if($request['action'] === 'delete'){
        if($res = $sqlMain->deleteOrder($request['id'])){
            echo json_encode(['status' => true]);
        }
    }
}
