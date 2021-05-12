<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'teacher'){
    header("Location: ../index.php");
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>
    <script
        src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
<div class="container pt-5">
    <div class="row">
        <div class="col-6 text-left">
            <div class="h6 float-left">Logged in as <span class="font-weight-bold"><?=$_SESSION['email']?></span></div>
        </div>
        <div class="col-6 text-right">
            <a href="../logout.php" class="btn btn-dark float-right">Log Out</a>
        </div>
    </div>

    <div class="my-3">
        <a href="../test/createTest.php" class="btn btn-dark btn-block">Create test</a>
    </div>
    <h2>All tests</h2>
    <div class="my-3">
        <table class="table text-center">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Code</th>
                    <th>Created at</th>
                    <th>Active</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            include_once '../TestController.php';
            $controller = new TestController();
            $tests = $controller->getAllTests();
            foreach ($tests as $test): ?>
                <tr>
                    <td><?=$test['id']?></td>
                    <td><?=$test['code']?></td>
                    <td><?=$test['created_at']?></td>
                    <td><i class="fas fa-2x <?=($test['active'] ? 'text-success fa-check' : 'text-danger fa-times')?>-circle"></i></td>
                    <td><a class="btn btn-dark <?=($test['active'] ? 'deactivate-test' : 'activate-test')?>"><?=($test['active'] ? 'Deactivate' : 'Activate')?></a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</div>
</body>
</html>