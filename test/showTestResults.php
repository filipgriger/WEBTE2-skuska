<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'student' || !isset($_SESSION['submissionId'])){
    header("Location: ../index.php");
    exit();
}

include_once '../SubmissionController.php';
$controller = new SubmissionController();
$submissionId = $_SESSION['submissionId'];
$results = $controller->getSubmissionResults($submissionId);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Result</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/showResults.css">
</head>
<body>

<div class="container">

    <div class="row">
        <div class="col-6 text-left">
            <div class="h1 my-5">Tvoj vysledok:<span class="pl-4"><?=$results['total']?>b</span></div>
        </div>
        <div class="col-6 text-right">
            <a href="../logout.php" class="btn btn-dark px-5 my-5">Exit</a>
        </div>
    </div>


    <hr class="border">

    <?php foreach ($results['simple'] as $simpleQuestion):?>

        <table class="table border text-center">
            <tr class="text-left">
                <th colspan="3" class="pl-5"><span class="pr-2">Q:</span><?=$simpleQuestion['question']?></th>
            </tr>
            <tr >
                <th>Your answer</th>
                <th>Correct answer</th>
                <th>Points</th>
            </tr>
            <tr>
                <td class="w-33"><?=$simpleQuestion['user_answer']?></td>
                <td class="w-33"><?=$simpleQuestion['correct_answer']?></td>
                <td class="w-33"><?=$simpleQuestion['points']?></td>
            </tr>
        </table>
        <hr class="border">
    <?php endforeach; ?>

    <?php foreach ($results['option'] as $optionQuestion):?>

        <table class="table border text-center">
            <tr class="text-left">
                <th colspan="3" class="pl-5"><span class="pr-2">Q:</span><?=$optionQuestion['question']?></th>
            </tr>
            <tr>
                <th class="w-33">Your answer</th>
                <th class="w-33">Correct answer</th>
                <th class="w-33">Points</th>
            </tr>
            <tr>
                <td><?=$optionQuestion['user_answer']?></td>
                <td><?=$optionQuestion['correct_answer']?></td>
                <td><?=$optionQuestion['points']?></td>
            </tr>
        </table>
        <hr class="border">
    <?php endforeach; ?>

    <?php foreach ($results['pair'] as $pairQuestion):?>

        <table class="table border text-center">
            <tr class="text-left">
                <th colspan="4" class="pl-5"><span class="pr-2">Q:</span><?=$pairQuestion['question']?></th>
            </tr>
            <tr >
                <th class="w-25">Item</th>
                <th class="w-25">Your answer</th>
                <th class="w-25">Correct answer</th>
                <th class="w-25">Points</th>
            </tr>
            <?php foreach (json_decode($pairQuestion['pairs'], true) as $pair):?>
                <tr>
                    <td><?=$pair['left']?></td>
                    <td><?=$pair['user_answer']?></td>
                    <td><?=$pair['correct_answer']?></td>
                    <td><?=$pair['points']?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <hr class="border">
    <?php endforeach; ?>

</div>

</body>
</html>
