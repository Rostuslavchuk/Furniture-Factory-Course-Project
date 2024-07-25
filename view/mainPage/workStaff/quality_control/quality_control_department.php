<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <?php require_once('../../../../bootstrap/style.php'); ?>
    <?php require_once('../../../../bootstrap/fontawesome.php'); ?>
    <link rel="stylesheet" href="style.css">
    <title>Quality Control Department</title>
</head>
<body>

<?php
require_once('../../../../sqlMain.php');
$sqlMain = new MainSql();
?>

<div class="container-md my-2 py-2">

    <div class="" style="position: relative;">
        <div id="alert_success" class="position-fixed d-flex flex-row bottom-0 left-0 py-3" style="transform: translateY(150%); transition: transform .5s ease-in-out;">
                <span class="d-flex align-items-center justify-content-center card bg-success rounded rounded-end-0 border-end-0 px-3 py-3 text-custom">
                    <i class="fa-solid fa-check"></i>
                </span>
            <span id="success_message" class="card rounded rounded-start-0 d-flex align-items-center p-3 text-custom"></span>
        </div>
    </div>

    <div class="" style="position: relative;">
        <div id="alert_warning_add" class="position-fixed d-flex flex-row bottom-0 left-0 py-3" style="transform: translateY(150%); transition: transform .5s ease-in-out;">
                <span class="d-flex align-items-center justify-content-center card bg-warning rounded rounded-end-0 border-end-0 px-3 py-3 text-custom">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </span>
            <span class="card rounded rounded-start-0 d-flex align-items-center p-3 text-custom">You don't have access to add!</span>
        </div>
    </div>
    <div class="" style="position: relative;">
        <div id="alert_warning_update" class="position-fixed d-flex flex-row bottom-0 left-0 py-3" style="transform: translateY(150%); transition: transform .5s ease-in-out;">
                <span class="d-flex align-items-center justify-content-center card bg-warning rounded rounded-end-0 border-end-0 px-3 py-3 text-custom">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </span>
            <span class="card rounded rounded-start-0 d-flex align-items-center p-3 text-custom">You don't have access to update!</span>
        </div>
    </div>
    <div class="" style="position: relative;">
        <div id="alert_warning_delete" class="position-fixed d-flex flex-row bottom-0 left-0 py-3" style="transform: translateY(150%); transition: transform .5s ease-in-out;">
                <span class="d-flex align-items-center justify-content-center card bg-warning rounded rounded-end-0 border-end-0 px-3 py-3 text-custom">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </span>
            <span class="card rounded rounded-start-0 d-flex align-items-center p-3 text-custom">You don't have access to delete!</span>
        </div>
    </div>

    <div class="d-flex justify-content-between" >
        <h2 class="text-title" >Quality Control Department</h2>
        <button class="btn btn-secondary" id="back_btn" >Back</button>
    </div>

    <div class="d-flex flex-row my-3">
        <button type="button" class="btn btn-primary rounded-end-0 text-custom" id="add">Add</button>
        <input type="search" class="form-control shadow-none rounded-start-0 rounded-end-0 text-custom" id="search_inp" placeholder="Search by worker full name" >
        <button disabled id="search_btn" type="button" class="btn btn-primary rounded-start-0 text-custom" >Search</button>
    </div>

    <table class="table table-bordered text_table my-3">
        <thead>
        <tr>
            <th>Employee Name</th>
            <th>Position</th>
            <th>Inspection Area</th>
            <th>Inspection Date</th>
            <th>Issue Found</th>
            <th>Corrective Action</th>
            <th>Salary</th>
            <th>Employee Image</th>
            <th>Options</th>
        </tr>
        </thead>
        <tbody id="table_body">
        <?php
        $array = $sqlMain->getData('quality_control');
        if(sizeof($array)):
            foreach ($array as $row):
                ?>
                <tr data-id="<?php echo $row['id']; ?>">
                    <td class="employee_name" ><?php echo $row['employee_name'];?></td>
                    <td class="position" ><?php echo $row['position'];?></td>
                    <td class="inspection_area" ><?php echo $row['inspection_area'];?></td>
                    <td class="inspection_date" ><?php echo $row['inspection_date'];?></td>
                    <td class="issues_found" ><?php echo $row['issues_found'];?></td>
                    <td class="corrective_action" ><?php echo $row['corrective_action'];?></td>
                    <td class="salary" ><?php echo $row['salary'];?></td>
                    <td>
                        <img data-img="<?php echo $row['id']; ?>" src="../../workStaff/quality_control/quality_control/QC<?php echo $row['id']; ?>.jpg" style="width: 75px; height: 75px; object-fit: cover;" alt="">
                    </td>
                    <td>
                        <div class="d-flex justify-content-center">
                            <button data-delete="<?php echo $row['id']; ?>" type="button" class="rounded rounded-end-0 border border-end-0 delete-btn" >
                                <i class="fa-solid fa-trash"></i>
                            </button>
                            <button data-update="<?php echo $row['id']; ?>" type="button" class="rounded rounded-start-0 border update-btn">
                                <i class="fa-solid fa-pen" ></i>
                            </button>
                        </div>
                    </td>
                </tr>
            <?php
            endforeach;
        else:
            ?>
            <tr>
                <td colspan="9" class="text-title text-center" >Table is Empty!</td>
            </tr>
        <?php
        endif;
        ?>
        </tbody>
    </table>

    <div class="modal fade" id="add_update_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="add_update_form" enctype="multipart/form-data">
                    <div class="modal-header">
                        <div class="modal-title fs-2 text-title" id="title_modal"></div>
                        <button id="close-modal-header" type="button" class="btn btn-close shadow-none button_text" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">

                        <input id="update_id" type="hidden" value="" name="update_id">

                        <div class="my-3" >
                            <label class="text-custom" for="employee_name">Employee Name</label>
                            <input type="text" min="1" class="form-control shadow-none text-custom" name="employee_name" id="employee_name" autofocus>
                            <span id="err_employee_name" class="text text-danger text-custom"></span>
                        </div>
                        <div class="my-3" >
                            <label class="text-custom" for="position">Position</label>
                            <input type="text" class="form-control shadow-none text-custom" name="position" id="position">
                            <span id="err_position" class="text text-danger text-custom"></span>
                        </div>
                        <div class="my-3" >
                            <label class="text-custom" for="inspection_area">Inspection Area</label>
                            <input type="text" class="form-control shadow-none text-custom" name="inspection_area" id="inspection_area">
                            <span id="err_inspection_area" class="text text-danger text-custom"></span>
                        </div>
                        <div class="my-3" >
                            <label class="text-custom" for="inspection_date">Inspection Date</label>
                            <input type="date" class="form-control shadow-none text-custom" name="inspection_date" id="inspection_date" />
                            <span id="err_inspection_date" class="text text-danger text-custom"></span>
                        </div>
                        <div class="my-3" >
                            <label class="text-custom" for="issues_found">Issues Found</label>
                            <textarea class="form-control shadow-none text-custom" name="issues_found" id="issues_found"></textarea>
                            <span id="err_issues_found" class="text text-danger text-custom"></span>
                        </div>
                        <div class="my-3" >
                            <label class="text-custom" for="corrective_action">Corrective Action</label>
                            <textarea class="form-control shadow-none text-custom" name="corrective_action" id="corrective_action"></textarea>
                            <span id="err_corrective_action" class="text text-danger text-custom"></span>
                        </div>
                        <div class="my-3" >
                            <label class="text-custom" for="salary">Salary</label>
                            <input type="number" class="form-control shadow-none text-custom" name="salary" id="salary" min="0">
                            <span id="err_salary" class="text text-danger text-custom"></span>
                        </div>
                        <div class="my-3" >
                            <label class="text-custom" for="image_employee">Image Employee</label>
                            <input type="file" accept="image/jpeg" name="image" class="form-control shadow-none text-custom" id="image_employee">
                            <span id="err_image_employee" class="text text-danger text-custom"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="close-modal-footer" type="button" class="btn btn-danger shadow-none button_text" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary shadow-none button_text" id="submit_button"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="delete_modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="delete_form">

                    <input type="hidden" value="" name="delete_id">

                    <div class="modal-header">
                        <div class="modal-title fs-2 shadow-none text-title">Delete</div>
                        <button class="btn btn-close shadow-none button_text" type="button" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                            <span class="text-custom">Are you sure you want delete
                            <b class="text-custom" id="delete_name"></b>?</span>
                    </div>
                    <div class="modal-footer">
                        <button id="close-delete-modal-footer" class="btn btn-danger shadow-none button_text" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-secondary shadow-none button_text" type="submit">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once('../../../../bootstrap/script.php'); ?>
<script src="script.js" defer></script>
</body>
</html>