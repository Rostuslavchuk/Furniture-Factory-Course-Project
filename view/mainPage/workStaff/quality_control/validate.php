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

            if($arrayIds = $sqlMain->search($value, "quality_control")){
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
            if(empty($request['inspection_area'])){
                $errors[] = ['code' => 3, 'message' => "You don't fill inspection area field!"];
            }
            if(empty($request['inspection_date'])){
                $errors[] = ['code' => 4, 'message' => "You don't fill inspection date field!"];
            }
            if(empty(htmlspecialchars($request['issues_found']))){
                $errors[] = ['code' => 5, 'message' => "You don't fill issues found field!"];
            }
            if(empty(htmlspecialchars($request['corrective_action']))){
                $errors[] = ['code' => 6, 'message' => "You don't fill corrective action field!"];
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
                    if($data = $sqlMain->addQualityControl($request, 'quality_control')){
                        $id_added = (int)$data['id'];
                        $tmpName = $_FILES['image_employee']['tmp_name'];
                        $path = "./quality_control/QC" . $id_added . ".jpg";
                        move_uploaded_file($tmpName, $path);

                        echo json_encode(['status' => true, 'data' => $data]);
                    }
                }
                if($request['action'] === 'update'){
                    if($data = $sqlMain->updateQualityControl($request, 'quality_control')){
                        if(isset($_FILES['image_employee'])){
                            $id_updated = (int)$data['id'];
                            $tmpName = $_FILES['image_employee']['tmp_name'];
                            $path = './quality_control/QC' . $id_updated . '.jpg';

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

            if($deleteRes = $sqlMain->delete($id, 'quality_control')){

                $path = './quality_control/QC' . $id . '.jpg';
                if(file_exists($path)){
                    unlink($path);
                }

                echo json_encode(['status' => true, 'delete_id' => $id]);
                die();
            }
        }
    }
}
