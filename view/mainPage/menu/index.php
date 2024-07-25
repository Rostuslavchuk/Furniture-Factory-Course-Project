<?php
    session_start();

    $userStatus = isset($_GET['admin']) ? 'admin' : (isset($_GET['client']) ? 'client' : 'guest');
    $username = null;

    if ($userStatus === 'admin' && isset($_GET['admin'])) {
        $username = $_GET['admin'];
    } elseif ($userStatus === 'client' && isset($_GET['client'])) {
        $username = $_GET['client'];
    }

    if ($username && $userStatus) {
        $_SESSION['username'] = $username;
        $_SESSION['status'] = $userStatus;
    }

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <?php require_once('../../../bootstrap/style.php'); ?>

    <link rel="stylesheet" href="style.css">

    <title>Furniture Factory</title>
</head>
<body>
<div class="container-fluid p-0">
    <nav class="navbar navbar-expand-md navbar-light bg-light px-2">

        <a class="navbar-brand text-title text-decoration-none" href="#">
            FurnitureCo.
            <h4 class="button_text text-decoration-none m-0">Welcome, <?php echo $_SESSION['username']; ?>!</h4>
        </a>

        <button class="navbar-toggler text-custom shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#menu" aria-controls="menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon text-custom"></span>
        </button>

        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav ms-auto gap-2">
                <li class="nav-item">
                    <a href="#" class="nav-link text-decoration-none text-custom" id="work_staff_btn" >Our Work Staff</a>
                </li>
                <li class="nav-item <?php echo $_SESSION['status'] === 'admin' ? 'd-none' : ''; ?>">
                    <a id="order_link" href="#" class="nav-link text-decoration-none text-custom">Order</a>
                </li>
                <li class="nav-item">
                    <button id='quit_btn' class="btn btn-danger text-button">Quit</button>
                </li>
            </ul>
        </div>
    </nav>


    <div class="modal fade" id="confirm_quit" >
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="form_quit">
                    <div class="modal-header">
                        <div class="modal-title">Quit</div>
                        <button class="btn btn-close" data-bs-dismiss="modal" type="button"></button>
                    </div>
                    <div class="modal-body">
                        <span class="button_text">
                            Are you sure you want quit <b><?php echo $_SESSION['username']; ?></b>?
                        </span>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-danger" type="submit">Quit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="about-factory" class="about_factory">
        <?php require_once('../about/index.php'); ?>
    </div>
</div>

<?php require_once('../../../bootstrap/script.php'); ?>
<script src="script.js" defer ></script>
</body>
</html>