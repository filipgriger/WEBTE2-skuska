<?php
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'teacher'){
    header("Location: ../index.php");
    exit();
}

include '../SubmissionController.php';
include '../TestController.php';
include '../StudentController.php';

$submissionController = new SubmissionController();
$testController = new TestController();
$studentController = new StudentController();

if (!($submission = $submissionController->getSubmission($_GET['submissionId']))){
    header('Location teacherHome.php');
    exit();
}

$student = $studentController->getStudent($submission['student_id']);
$test = $testController->getTest($submission['test_id']);
$results = $submissionController->getSubmissionResults($submission['id']);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit submission</title>
    <script
        src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"></script>
    <script src="../js/editSubmissions.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/showResults.css">
</head>
<body>
<div class="container py-5">

    <div class="d-flex justify-content-between">
        <div class="h1">Edit submission</div>
        <div class="align-self-center">
            <a href="showTestSubmissions.php?testId=<?=$test['id']?>" class="btn btn-dark px-5 mx-2">Back</a>
            <a href="teacherHome.php" class="btn btn-dark px-5 mx-2">Home</a>
        </div>
    </div>
    <hr class="border">

    <table class="table text-center">
        <thead>
        <tr>
            <th class="w-25">Name</th>
            <th class="w-25">Student ID</th>
            <th class="w-25">Time of submission</th>
            <th class="w-25">Points</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?=$student['name'].' '.$student['surname']?></td>
            <td><?=$student['student_code']?></td>
            <td><?=$submission['created_at']?></td>
            <td><?=$submission['total_points'].'/'.$test['total_points']?></td>
        </tr>
        </tbody>
    </table>
    <hr class="border">

    <form action="../router.php" method="post">
        <?php foreach ($results['simple'] as $simpleQuestion):?>

            <table class="table border text-center">
                <tr class="text-left">
                    <th colspan="4" class="pl-5"><span class="pr-2">Q:</span><?=$simpleQuestion['question']?></th>
                </tr>
                <tr >
                    <th class="w-30">Your answer</th>
                    <th class="w-30">Correct answer</th>
                    <th class="w-20">Points</th>
                    <th class="w-20">Action</th>
                </tr>
                <tr>
                    <td><?=$simpleQuestion['user_answer']?></td>
                    <td><?=$simpleQuestion['correct_answer']?></td>
                    <td>
                        <div class="row">
                            <input disabled type="number" step="0.1" min="0" max="<?=$simpleQuestion['max_points']?>" class="col-8 text-center" aria-label="Answer points" name="modifications[notPair][<?=$simpleQuestion['answer_id']?>]" value="<?=$simpleQuestion['points']?>">
                            <span class="col-4 font-weight-bold">[<?=$simpleQuestion['max_points']?>]</span>
                        </div>
                    </td>
                    <td><button class="btn btn-dark px-5 enable-input">Edit</button></td>
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
                    <th class="w-30">Your answer</th>
                    <th class="w-30">Correct answer</th>
                    <th class="w-20">Points</th>
                    <th class="w-20">Action</th>
                </tr>
                <tr>
                    <td><?=$optionQuestion['user_answer']?></td>
                    <td><?=$optionQuestion['correct_answer']?></td>
                    <td>
                        <div class="row">
                            <input disabled type="number" step="0.1" min="0" max="<?=$optionQuestion['max_points']?>" class="col-8 text-center" aria-label="Answer points" name="modifications[notPair][<?=$optionQuestion['answer_id']?>]" value="<?=$optionQuestion['points']?>">
                            <span class="col-4 font-weight-bold">[<?=$optionQuestion['max_points']?>]</span>
                        </div>
                    </td>
                    <td><button class="btn btn-dark px-5 enable-input">Edit</button></td>
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
                    <th class="w-20">Item</th>
                    <th class="w-20">Your answer</th>
                    <th class="w-20">Correct answer</th>
                    <th class="w-20">Points</th>
                    <th class="w-20">Action</th>
                </tr>
                <?php foreach (json_decode($pairQuestion['pairs'], true) as $pair):?>
                    <tr>
                        <td><?=$pair['left']?></td>
                        <td><?=$pair['user_answer']?></td>
                        <td><?=$pair['correct_answer']?></td>
                        <td>
                            <div class="row">
                                <input disabled type="number" step="0.1" min="0" max="<?=$pair['max_points']?>" class="col-8 text-center" aria-label="Answer points" name="modifications[pair][<?=$pair['answer_id']?>][<?=$pair['answer_pair_id']?>]" value="<?=$pair['points']?>">
                                <span class="col-4 font-weight-bold">[<?=$pair['max_points']?>]</span>
                            </div>
                        </td>
                        <td><button class="btn btn-dark px-5 enable-input">Edit</button></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <hr class="border">
        <?php endforeach; ?>
        <input type="hidden" name="submissionId" value="<?=$submission['id']?>">
        <input type="hidden" name="testId" value="<?=$test['id']?>">
        <input type="hidden" name="route" value="editSubmission">
        <input type="submit" class="btn btn-dark btn-block" value="Apply changes">
    </form>
</div>
</body>
</html>