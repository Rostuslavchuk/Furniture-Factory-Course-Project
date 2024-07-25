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

            if($arrayIds = $sqlMain->search($value, "design_development")){
                echo json_encode(['status' => true, 'array' => $arrayIds]);
            }
            else{
                echo json_encode(['status' => true, 'array' => $arrayIds]);
            }
            die();
        }

        if($request['action'] === 'add' || $request['action'] === 'update'){
            $errors = [];

            if(empty($request['employee_name'])){
                $errors[] = ['code' => 1, 'message' => "You don't fill employee name field!"];
            }
            if(empty($request['position'])){
                $errors[] = ['code' => 2, 'message' => "You don't fill position field!"];
            }
            if(empty($request['project_name'])){
                $errors[] = ['code' => 3, 'message' => "You don't fill project name field!"];
            }
            if(empty(htmlspecialchars($request['project_description']))){
                $errors[] = ['code' => 4, 'message' => "You don't fill project description field!"];
            }
            if(empty($request['start_date'])){
                $errors[] = ['code' => 5, 'message' => "You don't fill start date field!"];
            }
            if(empty($request['end_date'])){
                $errors[] = ['code' => 6, 'message' => "You don't fill end date field!"];
            }
            if(empty($request['salary'])){
                $errors[] = ['code' => 7, 'message' => "You don't fill salary field!"];
            }
            if(empty($_FILES['image_employee']) && $request['action'] === 'add'){
                $errors[] = ['code' => 8, 'message' => "You don't upload employee image!"];
            }

            if(!empty($errors)){
                echo json_encode(['status' => false, 'errors' => $errors]);
                die();
            }
            else{

                if($request['action'] === 'add'){
                    if($data = $sqlMain->addDesign($request, 'design_development')){
                        $idAdded = (int)$data['id'];

                        $tmpName = $_FILES['image_employee']['tmp_name'];
                        $path = "./dd/DD" . $idAdded . ".jpg";
                        move_uploaded_file($tmpName, $path);

                        echo json_encode(['status' => true, 'data' => $data]);
                    }
                }

                if($request['action'] === 'update'){
                    if($data = $sqlMain->updateDesign($request, 'design_development')){

                        if(isset($_FILES['image_employee'])){
                            $idUpdated = (int)$data['id'];
                            $tmpName = $_FILES['image_employee']['tmp_name'];
                            if($tmpName){
                                $path = "./dd/DD" . $idUpdated . ".jpg";
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

            if($deleteRes = $sqlMain->delete($request['delete_id'], 'design_development')){

                $id = (int)$request['delete_id'];

                $path = "./dd/DD" . $id . ".jpg";
                if(file_exists($path)){
                    unlink($path);
                }

                echo json_encode(['status' => true, 'delete_id' => $id]);

                die();
            }
        }
    }
}
