<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'teacher'){
    header("Location: ../index.php");
    exit();
}

include_once '../TestController.php';
$controller = new TestController();
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
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="test-code">Test code</label>
                    <input type="text" class="form-control" id="test-code" name="test-code" placeholder="xxxxxx" pattern="[0-9A-Za-z]{6}" value="<?php echo $controller->generateRandomHash();?>" readonly="readonly" style="color: #787878; cursor: no-drop">
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="test-code">Time limit (in minutes)</label>
                    <input type="number" class="form-control" id="test-time" name="test-time" placeholder="00:00">
                </div>
            </div>
        </div>

        <hr class="border">
        <input type="hidden" name="route" value="createTest">
        <div id="controls" class="text-center">
            <div class="w-100 font-weight-bold mb-2">Add question</div>
            <button id="add-simple" class="btn btn-dark px-5 mx-1">Simple answer</button>
            <button id="add-option" class="btn btn-dark px-5 mx-1">Choose option</button>
            <button id="add-pair" class="btn btn-dark px-5 mx-1">Pair options</button>
            <button id="add-image" class="btn btn-dark px-5 mx-1">Image</button>
            <button id="add-expression" class="btn btn-dark px-5 mx-1">Mathematical expression</button>
        </div>
        <input type="submit" class="btn btn-block btn-primary mt-3" value="Submit">
    </form>

</div>

</body>
</html>