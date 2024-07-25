<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <?php require_once('../../bootstrap/style.php');?>
    <link rel="stylesheet" href="./style.css">

    <title>Login</title>
</head>
<body>
<div class="row mx-2">
    <div id="card_login" class="card col-md-6 offset-md-3 px-0 mt-5" >
        <form id="form_login">
            <div class="card-header d-flex justify-content-between">
                <div class="modal-title text-title">Login</div>
                <a href="http://localhost:63342/furniture_factory/auth/register/register.php" class="text text-decoration-none text-custom" >Register</a>
            </div>
            <div class="card-body">
                <div class="my-3" >
                    <label for="username">Username</label>
                    <input autocomplete="false" type="text" name="username" id="username" placeholder="Username" class="form-control text-custom" autofocus>
                    <span id="err_username_login" class="text-custom text text-danger"></span>
                </div>
                <div class="my-3" >
                    <label for="password">Password</label>
                    <input autocomplete="false" type="password" name="password" id="password" placeholder="Password" class="form-control text-custom">
                    <span id="err_password_login" class="text-custom text text-danger"></span>
                </div>
            </div>
            <div class="card-footer text-end">
                <button class="btn btn-secondary button_text" type="submit" >Login</button>
            </div>
        </form>
    </div>
</div>
<?php require_once('../../bootstrap/script.php');?>
<script src="script.js" defer ></script>
</body>
</html>