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
    <title>Vytvorenie testu</title>
    <script
        src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"></script>
    <script src="../js/createTest.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
<div class="container py-5">
    <h1>Vytvorenie nového testu</h1>
    <form action="../router.php" method="post">
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="test-code">Kľúč</label>
                    <input type="text" class="form-control" id="test-code" name="test-code" placeholder="xxxxxx" pattern="[0-9A-Za-z]{6}" value="<?php echo $controller->generateRandomHash();?>" readonly="readonly" style="color: #787878; cursor: no-drop">
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="test-time">Časový limit (v minútach)</label>
                    <input type="number" class="form-control" id="test-time" name="test-time" min="1" placeholder="00:00" required>
                </div>
            </div>
        </div>

        <hr class="border">
        <input type="hidden" name="route" value="createTest">
        <div id="controls" class="text-center">
            <div class="w-100 font-weight-bold mb-1">Pridať otázku</div>
            <button id="add-simple" class="btn btn-dark px-5 mx-1 my-1">S otvorenou krátkou odpoveďou</button>
            <button id="add-option" class="btn btn-dark px-5 mx-1 my-1">S výberom správnej odpovede</button>
            <button id="add-pair" class="btn btn-dark px-5 mx-1 my-1">Párovanie odpovedí</button>
            <button id="add-image" class="btn btn-dark px-5 mx-1 my-1">Nakreslenie obrázku</button>
            <button id="add-expression" class="btn btn-dark px-5 mx-1 my-1">Napísanie matematického výrazu</button>
        </div>
        <input type="submit" class="btn btn-block btn-primary mt-3" value="Pridať test">
    </form>

</div>

</body>
</html>