<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <?php require_once('../../bootstrap/style.php');?>
    <link rel="stylesheet" href="style.css">
    <title>Register</title>
</head>
<body>
    <div class="row mx-2">
        <div id="card_register" class="card col-md-6 offset-md-3 px-0 mt-5" >
            <form id="form_register">
                <div class="card-header d-flex justify-content-between">
                    <div class="modal-title text-title">Register</div>
                    <a href="http://localhost:63342/furniture_factory/auth/login/login.php" class="text text-decoration-none text-custom" >Login</a>
                </div>
                <div class="card-body">
                    <div class="my-3" >
                        <label for="username">Username</label>
                        <input autocomplete="false" type="text" name="username" id="username" placeholder="Username" class="form-control text-custom" autofocus>
                        <span id="err_username_register" class="text-custom text text-danger"></span>
                    </div>
                    <div class="my-3" >
                        <label for="firstname">FirstName</label>
                        <input autocomplete="false" type="text" name="firstname" id="firstname" placeholder="Firstname" class="form-control text-custom">
                        <span id="err_firstname_register" class="text-custom text text-danger"></span>
                    </div>
                    <div class="my-3" >
                        <label for="lastname">LastName</label>
                        <input autocomplete="false" type="text" name="lastname" id="lastname" placeholder="Lastname" class="form-control text-custom">
                        <span id="err_lastname_register" class="text-custom text text-danger"></span>
                    </div>
                    <div class="my-3" >
                        <label for="email">Email</label>
                        <input autocomplete="false" type="text" name="email" id="email" placeholder="Email" class="form-control text-custom">
                        <span id="err_email_register" class="text-custom text text-danger"></span>
                    </div>
                    <div class="my-3" >
                        <label for="password">Password</label>
                        <input autocomplete="false" type="password" name="password" id="password" placeholder="Password" class="form-control text-custom">
                        <span id="err_password_register" class="text-custom text text-danger"></span>
                    </div>
                    <div class="my-3" >
                        <label for="re-password">Repeat Password</label>
                        <input autocomplete="false" type="password" name="re-password" id="re-password" placeholder="Repeat Password" class="form-control text-custom">
                        <span id="err_re-password_register" class="text-custom text text-danger"></span>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button class="btn btn-secondary button_text" type="submit" >Register</button>
                </div>
            </form>
        </div>
    </div>
    <?php require_once('../../bootstrap/script.php');?>
    <script src="script.js" defer ></script>
</body>
</html>