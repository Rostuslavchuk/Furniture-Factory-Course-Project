<?php

header('Access-Control-Allow-Origin: http://localhost:63342/*');
header('Access-Control-Allow-Methods: POST');

require_once('../../../../sqlMain.php');
$sqlMain = new MainSql();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $request = $_POST;

    if(isset($request['action'])){

        if($request['action'] === 'access'){
            $username = $sqlMain->getUsername();
            if($sqlMain->checkUsernameAdmin($username)){
                echo json_encode(['status' => true]);
            }
            else{
                echo json_encode(['status' => false]);
            }
            die();
        }


        if($request['action'] === 'search'){
            $value = trim($request['value']);

            if($arrayIds = $sqlMain->search($value, "hr_department")){
                echo json_encode(['status' => true, 'array' => $arrayIds]);
            }
            else{
                echo json_encode(['status' => true, 'array' => $arrayIds]);
            }
            die();
        }

        if($request['action'] === 'update' || $request['action'] === 'add'){
            $errors = [];

            if(empty($request['employee_name'])){
                $errors[] = ['code' => 1, 'message' => "You don't fill employee name field!"];
            }
            if(empty($request['position'])){
                $errors[] = ['code' => 2, 'message' => "You don't fill position field!"];
            }
            if(empty($request['hire_date'])){
                $errors[] = ['code' => 3, 'message' => "You don't fill hire date field!"];
            }
            if(empty($request['department'])){
                $errors[] = ['code' => 4, 'message' => "You don't fill department field!"];
            }
            if(empty(htmlspecialchars($request['performance_review']))){
                $errors[] = ['code' => 5, 'message' => "You don't fill performance review field!"];
            }
            if(empty($request['salary'])){
                $errors[] = ['code' => 6, 'message' => "You don't fill salary field!"];
            }
            if(empty($_FILES['image_employee']) && $request['action'] === 'add'){
                $errors[] = ['code' => 7, 'message' => "You don't upload employee image!"];
            }

            if(!empty($errors)){
                echo json_encode(['status' => false, 'errors' => $errors]);
                die();
            }
            else{
                if($request['action'] === 'add'){
                    if($data = $sqlMain->addHr($request, 'hr_department')){
                        $id_added = (int)$data['id'];
                        $tmpName = $_FILES['image_employee']['tmp_name'];
                        $path = "./hr/HR" . $id_added . ".jpg";
                        move_uploaded_file($tmpName, $path);

                        echo json_encode(['status' => true, 'data' => $data]);
                    }
                }
                if($request['action'] === 'update'){
                    if($data = $sqlMain->updateHr($request, 'hr_department')){
                        if(isset($_FILES['image_employee'])){
                            $id_updated = (int)$data['id'];
                            $tmpName = $_FILES['image_employee']['tmp_name'];
                            $path = './hr/HR' . $id_updated . '.jpg';

                            if(file_exists($path)){
                                move_uploaded_file($tmpName, $path);
                            }
                        }
                        echo json_encode(['status' => true, 'data' => $data]);
                    }
                }
                die();
            }
        }

        if($request['action'] === 'delete'){
            $id = (int)$request['delete_id'];

            if($deleteRes = $sqlMain->delete($id, 'hr_department')){

                $path = './hr/HR' . $id . '.jpg';
                if(file_exists($path)){
                    unlink($path);
                }

                echo json_encode(['status' => true, 'delete_id' => $id]);
                die();
            }
        }
    }
}
