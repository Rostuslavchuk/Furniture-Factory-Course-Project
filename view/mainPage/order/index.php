<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php require_once('../../../bootstrap/style.php');?>
    <?php require_once('../../../bootstrap/fontawesome.php');?>
    <link rel="stylesheet" href="style.css">
    <title>Order</title>
</head>
<body>
        <?php
            require_once('../../../sqlMain.php');
            $sqlMain = new MainSql();
        ?>

        <div class="container-md position-relative">

            <div style="position: relative; z-index: 1;">
                <div id="alert_success" class="position-fixed p-1 bottom-0 left-0 mb-3 d-flex" style="transform: translateY(150%); transition: transform .2s ease-in;">
                    <span class="card d-flex align-items-center justify-content-center py-3 px-3 border-end-0 rounded-end-0 bg-success text-custom">
                        <i class="fa-solid fa-check"></i>
                    </span>
                        <span class="card align-items-center justify-content-center py-3 px-3 rounded-start-0 text-custom" id="success_message">
                    </span>
                </div>
            </div>


            <div class="d-flex justify-content-between align-items-center my-3">
                <h2 class="text-title">Welcome in order, <?php echo $sqlMain->getUsername(); ?></h2>
                <button class="btn btn-secondary" type="button" id="back_btn">Back</button>
            </div>

            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a id="search_button" class="nav-link text text-black active text-custom" data-bs-toggle="tab" href="#search_order">Search Order</a>
                </li>
                <li class="nav-item">
                    <a id="create_button" class="nav-link text text-black text-custom" data-bs-toggle="tab" href="#create_order">Create Order</a>
                </li>
            </ul>

            <div class="tab-content">
                <div id="create_order" class="tab-pane fade">
                    <div class="card border-top-0 rounded-top-0">
                        <form id="form_create">
                            <div class="card-header">
                                <div class="text-title">Create</div>
                            </div>
                            <div class="card-body">
                                <div class="my-2">
                                    <label class="text-custom" for="create_username">Username</label>
                                    <input type="text" class="form-control shadow-none text-custom" name="create_username" id="create_username" autofocus autocomplete="false">
                                    <span id="err_create_username" class="text text-danger text-custom"></span>
                                </div>
                                <div class="my-2">
                                    <label class="text-custom" for="create_order_name">Order Name</label>
                                    <input type="text" class="form-control shadow-none text-custom" name="create_order_name" id="create_order_name" autocomplete="false">
                                    <span id="err_create_order_name" class="text text-danger text-custom"></span>
                                </div>
                                <div class="my-2">
                                    <label class="text-custom" for="create_order_description">Order Description</label>
                                    <textarea class="form-control shadow-none text-custom" name="create_order_description" id="create_order_description" autocomplete="false"></textarea>
                                    <span id="err_create_order_description" class="text text-danger text-custom"></span>
                                </div>
                                <div class="my-2" >
                                    <label class="text-custom" for="create_perform_to">Perform To</label>
                                    <input type="time" class="form-control shadow-none text-custom" name="create_perform_to" id="create_perform_to" autocomplete="false">
                                    <span id="err_create_perform_to" class="text text-danger text-custom"></span>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button class="btn btn-secondary button_text" type="submit">Create</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div id="search_order" class="tab-pane fade show active">
                    <div class="card border-top-0 rounded-top-0">
                        <form id="form_search">
                            <div class="card-header">
                                <div class="text-title">Search</div>
                            </div>
                            <div class="card-body">
                                <div class="my-2">
                                    <label class="text-custom" for="search_username">Username</label>
                                    <input type="text" class="form-control shadow-none text-custom" name="search_username" id="search_username" autofocus autocomplete="false">
                                    <span id="err_search_username" class="text text-danger text-custom"></span>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button class="btn btn-secondary button_text" type="submit">Search</button>
                            </div>
                        </form>

                        <div class="none px-2" id="table">
                            <table class="table table-bordered text_table" >
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Order name</th>
                                        <th>Order Description</th>
                                        <th>Perform To</th>
                                        <th>Options</th>
                                    </tr>
                                </thead>
                                <tbody id="body_search">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="update_modal_order" >
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form class="form" id="form_update_order">
                            <div class="modal-header">
                                <div class="modal-title text-title">Update</div>
                                <button id="close-update-header" class="btn btn-close button_text" data-bs-dismiss="modal" type="button"></button>
                            </div>
                            <div class="modal-body">
                                <div class="my-2">
                                    <label class="text-custom" for="update_order_name">Order Name</label>
                                    <input type="text" class="form-control shadow-none text-custom" name="update_order_name" id="update_order_name" autocomplete="false">
                                    <span id="err_update_order_name" class="text text-danger text-custom"></span>
                                </div>
                                <div class="my-2">
                                    <label class="text-custom" for="update_order_description">Order Description</label>
                                    <textarea class="form-control shadow-none text-custom" name="update_order_description" id="update_order_description" autocomplete="false"></textarea>
                                    <span id="err_update_order_description" class="text text-danger text-custom"></span>
                                </div>
                                <div class="my-2" >
                                    <label class="text-custom" for="update_perform_to">Perform To</label>
                                    <input type="time" class="form-control shadow-none text-custom" name="update_perform_to" id="update_perform_to" autocomplete="false">
                                    <span id="err_update_perform_to" class="text text-danger text-custom"></span>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button id="close-update-footer" class="btn btn-danger button_text" type="button" data-bs-dismiss="modal" >Cancel</button>
                                <button type="submit" class="btn btn-primary" >Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="delete_modal_order" >
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="form_delete_order" class="form">
                            <div class="modal-header">
                                <div class="modal-title text-title">Delete</div>
                                <button id="delete-close-header" type="button" class="btn btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <span>
                                    Are you sure you want delete Order: <b id="delete_text"></b> ?
                                </span>
                            </div>
                            <div class="modal-footer">
                                <button id="delete-close-footer" type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Delete</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php require_once('../../../bootstrap/script.php');?>
        <script src="script.js" defer ></script>
</body>
</html>