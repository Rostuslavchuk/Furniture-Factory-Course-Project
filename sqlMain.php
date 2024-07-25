<?php


class MainSql{
    private $connection = null;
    private $username = 'root';
    private $server = '127.0.0.1';
    private $password = '';
    private $db_name = 'furniture_factory';

    public function __construct()
    {
        $this->connection = new mysqli($this->server, $this->username, $this->password, $this->db_name);
        $this->selectDatabase();
    }
    private function selectDatabase()
    {
        mysqli_select_db($this->connection, $this->db_name);
    }

    // register
    public function checkUsernameClients($username){
        $checkUsername = $this->connection->prepare('SELECT * FROM clients where username = ?');
        $checkUsername->bind_param('s', $username);

        if($checkUsername->execute() !== TRUE){
            http_response_code(500);
            die('Error while check username!');
        }
        else{
            $result = $checkUsername->get_result();
            $num_row = $result->num_rows;
            $checkUsername->close();

            return $num_row > 0;
        }
    }
    public function checkUsernameAdmin($username){
        $checkAdmin = $this->connection->prepare('SELECT * FROM admins where username = ?');
        $checkAdmin->bind_param('s', $username);

        if($checkAdmin->execute() !== TRUE){
            http_response_code(500);
            die('Error while check username!');
        }
        else{
            $result = $checkAdmin->get_result();
            $num_row = $result->num_rows;
            $checkAdmin->close();

            return $num_row > 0;
        }
    }
    public function addClient($request){
        $username = $request['username'];
        $firstName = $request['firstname'];
        $lastname = $request['lastname'];
        $email = $request['email'];
        $password = $request['password'];

        $addNewClient = $this->connection->prepare('INSERT INTO clients (`username`, `first_name`, 
        `last_name`, `email`, `password`) VALUES (?, ?, ?, ?, PASSWORD(?))');
        $addNewClient->bind_param('sssss', $username, $firstName, $lastname, $email, $password);

        if($addNewClient->execute() !== TRUE){
            http_response_code(500);
            die('Error while add client!');
        }
        else{
            $addNewClient->close();
            return true;
        }
    }

    // login check password
    public function checkPasswordAdmin($username, $password){
        $checkAdmin = $this->connection->prepare('SELECT * FROM admins where username = ? and password = PASSWORD(?)');
        $checkAdmin->bind_param('ss', $username, $password);

        if($checkAdmin->execute() !== TRUE){
            http_response_code(500);
            die('Error while check username and password!');
        }
        else{
            $result = $checkAdmin->get_result();
            $num_row = $result->num_rows;
            $checkAdmin->close();

            return $num_row === 1;
        }
    }
    public function checkPasswordClient($username, $password){
        $checkClient = $this->connection->prepare('SELECT * FROM clients where username = ? and password = PASSWORD(?)');
        $checkClient->bind_param('ss', $username, $password);

        if($checkClient->execute() !== TRUE){
            http_response_code(500);
            die('Error while check username and password!');
        }
        else{
            $result = $checkClient->get_result();
            $num_row = $result->num_rows;
            $checkClient->close();

            session_start();
            $_SESSION['username'] = $username;
            session_write_close();

            return $num_row === 1;
        }
    }

    public function getUsername()
    {
        session_start();
        $user = $_SESSION['username'];
        session_write_close();
        return $user;
    }


    // get data
    public function getData($table){
        $getData = $this->connection->prepare("SELECT * FROM $table");

        $res_array = [];

        if($getData->execute() !== TRUE){
            http_response_code(500);
            die('Error while select data from quality control table!');
        }
        else{
            $result = $getData->get_result();
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $res_array[] = $row;
                }
            }
            $getData->close();

            return $res_array;
        }
    }

    // orders, orders actions
    // check username in client i use current checkUsernameClients
    public function getOrdersFor($username){
        $getData = $this->connection->prepare('SELECT * FROM orders where username = ?');
        $getData->bind_param('s', $username);

        $res_array = [];

        if($getData->execute() !== TRUE){
            http_response_code(500);
            die('Error while select data from order for current user!');
        }
        else{
            $result = $getData->get_result();
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $res_array[] = $row;
                }
            }
            else{
                return ['empty_message' => "This user don't have orders!"];
            }

            $getData->close();
            return $res_array;
        }
    }
    public function addOrder($request){
        $username = $request['create_username'];
        $order_name = $request['create_order_name'];
        $order_description = htmlspecialchars($request['create_order_description']);
        $perform_to = $request['create_perform_to'];

        $add_order = $this->connection->prepare('INSERT INTO `orders` 
        (`username`, `order_name`, `order_description`, `perform_to`) VALUES 
        (?, ?, ?, ?)');
        $add_order->bind_param('ssss', $username, $order_name, $order_description, $perform_to);

        if($add_order->execute() !== TRUE){
            http_response_code(500);
            die('Error while add to order!');
        }
        $add_order->close();
        return true;
    }
    public function updateOrder($request)
    {
        $id = (int)$request['id'];
        $order_name = $request['update_order_name'];
        $order_description = htmlspecialchars($request['update_order_description']);
        $perform_to = $request['update_perform_to'];

        $update_order = $this->connection->prepare('UPDATE orders SET order_name = ?, order_description = ?,
        perform_to = ? where id = ?');
        $update_order->bind_param('sssi', $order_name, $order_description, $perform_to, $id);

        if($update_order->execute() !== TRUE){
            http_response_code(500);
            die('Error while update orders!');
        }
        else{
            $update_order->close();

            $selectUpdatedOrder = $this->connection->prepare('SELECT * FROM orders where id = ?');
            $selectUpdatedOrder->bind_param('i', $id);

            if($selectUpdatedOrder->execute() !== TRUE){
                http_response_code(500);
                die('Error while select current order!');
            }
            else{
                $result = $selectUpdatedOrder->get_result();
                if($result->num_rows > 0){
                    $row = $result->fetch_assoc();
                    $selectUpdatedOrder->close();
                    return $row;
                }
            }
        }
    }
    function deleteOrder($id){
        $id = (int)$id;

        $deleteOrder = $this->connection->prepare('DELETE FROM orders where id = ?');
        $deleteOrder->bind_param('i', $id);

        if($deleteOrder->execute() !== TRUE){
            http_response_code(500);
            die('Error while delete order form orders!');
        }
        else{
            $deleteOrder->close();
            return true;
        }
    }



    // actions on tables
    public function selectFactoryId(){
        $getFactoryIdent = $this->connection->prepare('SELECT id FROM factory;');
        if($getFactoryIdent->execute() !== TRUE){
            http_response_code(500);
            die('Error while select factory id');
        }
        else{
            $result = $getFactoryIdent->get_result();
            if($result->num_rows > 0){
                $data = $result->fetch_assoc();
                $getFactoryIdent->close();
                return (int)$data['id'];
            }
        }
    }
    public function getRow($table, $id){
        $id = (int)$id;
        $get = $this->connection->prepare("SELECT * FROM $table where id = ?");
        $get->bind_param('i', $id);

        if($get->execute() !== TRUE){
            http_response_code(500);
            die('Error while after action');
        }
        else{
            $result = $get->get_result();

            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                $get->close();
                return $row;
            }
            else{
                return null;
            }
        }
    }
    // search
    public function search($value, $table)
    {
        $value = '%'. $value . '%';
        $search = $this->connection->prepare("SELECT id FROM $table where employee_name LIKE ?");
        $search->bind_param('s', $value);

        $res_array = [];

        if($search->execute() !== TRUE){
            http_response_code(500);
            die('Error while search!');
        }
        else{
            $result = $search->get_result();
            if($result->num_rows > 0){
                while ($row = $result->fetch_assoc()){
                    $res_array[] = $row;
                }
            }
            $search->close();

            return $res_array;
        }
    }
    public function delete($id, $table){
        $id = (int)$id;
        $delete = $this->connection->prepare("DELETE FROM $table where id = ?");
        $delete->bind_param('i', $id);

        if($delete->execute() !== TRUE){
            http_response_code(500);
            die('Error while delete form table!');
        }
        else{
            $delete->close();

            return true;
        }
    }

    // design department
    public function addDesign($request, $table){

        $factory_id = $this->selectFactoryId();

        $employee_name = $request['employee_name'];
        $position = $request['position'];
        $project_name = $request['project_name'];
        $project_description = htmlspecialchars($request['project_description']);
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];
        $salary = (int)$request['salary'];

        $add = $this->connection->prepare("INSERT INTO $table (`factory_id`, 
        `employee_name`, `position`, `project_name`, `project_description`,
        `start_date`, `end_date`, `salary`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $add->bind_param('issssssi', $factory_id,
        $employee_name, $position, $project_name, $project_description, $start_date,
        $end_date, $salary);

        if($add->execute() !== TRUE){
            http_response_code(500);
            die('Error while add!');
        }
        else{
            $row_id = $add->insert_id;
            $add->close();
            return $this->getRow($table, $row_id);
        }
    }
    public function updateDesign($request, $table){
        $id_update = (int)$request['update_id'];

        $employee_name = $request['employee_name'];
        $position = $request['position'];
        $project_name = $request['project_name'];
        $project_description = htmlspecialchars($request['project_description']);
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];
        $salary = (int)$request['salary'];

        $update = $this->connection->prepare("UPDATE $table SET
        employee_name = ? , position = ?, project_name = ?, project_description = ?,
        start_date = ?, end_date = ?, salary = ? where id = ?");
        $update->bind_param('ssssssii',
             $employee_name, $position, $project_name, $project_description, $start_date,
            $end_date, $salary, $id_update);

        if($update->execute() !== TRUE){
            http_response_code(500);
            die('Error while update!');
        }
        else{
            $update->close();
            return $this->getRow($table, $id_update);
        }
    }

    // hr department
    function addHr($request, $table)
    {
        $factory_id = $this->selectFactoryId();
        $employee_name = $request['employee_name'];
        $position = $request['position'];
        $hire_date = $request['hire_date'];
        $department = $request['department'];
        $performance_review = htmlspecialchars($request['performance_review']);
        $salary = (int)$request['salary'];

        $add = $this->connection->prepare("INSERT INTO $table (`factory_id`, 
        `employee_name`, `position`, `hire_date`, `department`,
        `performance_review`, `salary`) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $add->bind_param('isssssi', $factory_id,
        $employee_name, $position, $hire_date, $department, $performance_review, $salary);

        if($add->execute() !== TRUE){
            http_response_code(500);
            die('Error while add!');
        }
        else{
            $row_id = $add->insert_id;
            $add->close();
            return $this->getRow($table, $row_id);
        }
    }
    function updateHr($request, $table)
    {
        $id_update = (int)$request['update_id'];

        $employee_name = $request['employee_name'];
        $position = $request['position'];
        $hire_date = $request['hire_date'];
        $department = $request['department'];
        $performance_review = htmlspecialchars($request['performance_review']);
        $salary = (int)$request['salary'];

        $update = $this->connection->prepare("UPDATE $table SET
        employee_name = ?, position = ?, hire_date = ?, department = ?,
        performance_review = ?, salary = ? where id = ?");

        $update->bind_param('sssssii',
        $employee_name, $position, $hire_date, $department, $performance_review, $salary, $id_update);

        if($update->execute() !== TRUE){
            http_response_code(500);
            die('Error while update!');
        }
        else{
            $update->close();
            return $this->getRow($table, $id_update);
        }
    }

    // it department
    function addIt($request, $table)
    {
        $factory_id = $this->selectFactoryId();
        $employee_name = $request['employee_name'];
        $position = $request['position'];
        $system_managed = $request['system_managed'];
        $issue_reported = htmlspecialchars($request['issue_reported']);
        $resolution = htmlspecialchars($request['resolution']);
        $date_reported = $request['date_reported'];
        $date_resolved = $request['date_resolved'];
        $salary = (int)$request['salary'];

        $add = $this->connection->prepare("INSERT INTO $table (`factory_id`, 
        `employee_name`, `position`, `system_managed`, `issue_reported`,
        `resolution`, `date_reported`, `date_resolved`, `salary`) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $add->bind_param('isssssssi', $factory_id,
        $employee_name, $position, $system_managed, $issue_reported,
        $resolution, $date_reported, $date_resolved, $salary);

        if($add->execute() !== TRUE){
            http_response_code(500);
            die('Error while add!');
        }
        else{
            $row_id = $add->insert_id;
            $add->close();
            return $this->getRow($table, $row_id);
        }
    }
    function updateIt($request, $table)
    {
        $id_update = (int)$request['update_id'];

        $employee_name = $request['employee_name'];
        $position = $request['position'];
        $system_managed = $request['system_managed'];
        $issue_reported = htmlspecialchars($request['issue_reported']);
        $resolution = htmlspecialchars($request['resolution']);
        $date_reported = $request['date_reported'];
        $date_resolved = $request['date_resolved'];
        $salary = (int)$request['salary'];

        $update = $this->connection->prepare("UPDATE $table SET
        employee_name = ?, position = ?, system_managed = ?, issue_reported = ?,
        resolution = ?, date_reported = ?, date_resolved = ?, salary = ? where id = ?");
        $update->bind_param('sssssssii',
            $employee_name, $position, $system_managed, $issue_reported,
            $resolution, $date_reported, $date_resolved, $salary, $id_update);

        if($update->execute() !== TRUE){
            http_response_code(500);
            die('Error while update!');
        }
        else{
            $update->close();
            return $this->getRow($table, $id_update);
        }
    }


    // production
    function addProduction($request, $table)
    {
        $factory_id = $this->selectFactoryId();

        $employee_name = $request['employee_name'];
        $position = $request['position'];
        $section = $request['section'];
        $task_description = htmlspecialchars($request['task_description']);
        $shift_start = $request['shift_start'];
        $shift_end = $request['shift_end'];
        $salary = (int)$request['salary'];

        $add = $this->connection->prepare("INSERT INTO $table (`factory_id`, 
        `employee_name`, `position`, `section`, `task_description`,
        `shift_start`, `shift_end`, `salary`) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $add->bind_param('issssssi', $factory_id,
            $employee_name, $position, $section, $task_description,
            $shift_start, $shift_end, $salary);

        if($add->execute() !== TRUE){
            http_response_code(500);
            die('Error while add!');
        }
        else{
            $row_id = $add->insert_id;
            $add->close();
            return $this->getRow($table, $row_id);
        }
    }
    function updateProduction($request, $table)
    {
        $id_update = (int)$request['update_id'];

        $employee_name = $request['employee_name'];
        $position = $request['position'];
        $section = $request['section'];
        $task_description = htmlspecialchars($request['task_description']);
        $shift_start = $request['shift_start'];
        $shift_end = $request['shift_end'];
        $salary = (int)$request['salary'];

        $update = $this->connection->prepare("UPDATE $table SET 
        employee_name = ?, position = ?, section = ?, task_description = ?,
        shift_start = ?, shift_end = ?, salary = ? where id = ?");
        $update->bind_param('ssssssii',
            $employee_name, $position, $section, $task_description,
            $shift_start, $shift_end, $salary, $id_update);

        if($update->execute() !== TRUE){
            http_response_code(500);
            die('Error while add!');
        }
        else{
            $update->close();
            return $this->getRow($table, $id_update);
        }
    }

    // quality control
    function addQualityControl($request, $table)
    {
        $factory_id = $this->selectFactoryId();

        $employee_name = $request['employee_name'];
        $position = $request['position'];
        $inspection_area = $request['inspection_area'];
        $inspection_date = $request['inspection_date'];
        $issues_found = htmlspecialchars($request['issues_found']);
        $corrective_action = htmlspecialchars($request['corrective_action']);
        $salary = (int)$request['salary'];

        $add = $this->connection->prepare("INSERT INTO $table (`factory_id`, 
        `employee_name`, `position`, `inspection_area`, `inspection_date`,
        `issues_found`, `corrective_action`, `salary`) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $add->bind_param('issssssi', $factory_id,
            $employee_name, $position, $inspection_area, $inspection_date,
            $issues_found, $corrective_action, $salary);

        if($add->execute() !== TRUE){
            http_response_code(500);
            die('Error while add!');
        }
        else{
            $row_id = $add->insert_id;
            $add->close();
            return $this->getRow($table, $row_id);
        }
    }
    function updateQualityControl($request, $table)
    {
        $id_update = (int)$request['update_id'];

        $employee_name = $request['employee_name'];
        $position = $request['position'];
        $inspection_area = $request['inspection_area'];
        $inspection_date = $request['inspection_date'];
        $issues_found = $request['issues_found'];
        $corrective_action = htmlspecialchars($request['corrective_action']);
        $salary = (int)$request['salary'];

        $update = $this->connection->prepare("UPDATE $table SET 
        employee_name = ?, position = ?, inspection_area = ?, inspection_date = ?,
        issues_found = ?, corrective_action = ?, salary = ? where id = ?");
        $update->bind_param('ssssssii',
            $employee_name, $position, $inspection_area, $inspection_date,
            $issues_found, $corrective_action, $salary, $id_update);

        if($update->execute() !== TRUE){
            http_response_code(500);
            die('Error while update!');
        }
        else{
            $update->close();
            return $this->getRow($table, $id_update);
        }
    }

    // sum workers
    public function getSumWorkers() {
        $sql = '
        SELECT COUNT(*) as count FROM it_department
        UNION ALL
        SELECT COUNT(*) as count FROM hr_department
        UNION ALL
        SELECT COUNT(*) as count FROM production
        UNION ALL
        SELECT COUNT(*) as count FROM quality_control
        UNION ALL
        SELECT COUNT(*) as count FROM design_development';

        $statement = $this->connection->prepare($sql);
        if($statement->execute() !== TRUE){
            http_response_code(500);
            die('Error while get sum workers!');
        }

        $totalCount = 0;

        $results = $statement->get_result();
        if($results->num_rows > 0){
            while($row = $results->fetch_assoc()){
                $totalCount += (int)$row['count'];
            }
        }

        return $totalCount;
    }

    // check access to actions
    public function checkAccessToAction($username)
    {
        $checkAccess = $this->connection->prepare('SELECT * FROM admins where username = ?');
        $checkAccess->bind_param('s', $username);

        if($checkAccess->execute() !== TRUE){
            http_response_code(500);
            die('Error while check access!');
        }
        else{
            $result = $checkAccess->get_result();
            if($result->num_rows > 0){
                return true;
            }
            return false;
        }
    }
}