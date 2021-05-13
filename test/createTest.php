<?php
session_start();

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
    <title>New test</title>
    <script
        src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"></script>
    <script src="../js/createTest.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container py-5">
    <h1>New Test</h1>
    <form action="../router.php" method="post">
        <div class="form-group">
            <label for="test-code">Test code</label>
            <input type="text" class="form-control" id="test-code" name="test-code" placeholder="xxxxxx">
        </div>
        <hr class="border">
        <input type="hidden" name="route" value="createTest">
        <div id="controls" class="text-center">
            <button id="add-simple" class="btn btn-dark px-5 mx-3">Simple answer</button>
            <button id="add-option" class="btn btn-dark px-5 mx-3">Choose option</button>
            <button id="add-pair" class="btn btn-dark px-5 mx-3">Pair options</button>
            <button id="add-image" class="btn btn-dark px-5 mx-3">Image</button>
        </div>
        <input type="submit" class="btn btn-block btn-primary mt-3" value="Submit">
    </form>

</div>

</body>
</html>