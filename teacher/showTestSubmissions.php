<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'teacher') {
    header("Location: ../index.php");
    exit();
}

include '../SubmissionController.php';
include '../TestController.php';

$submissionController = new SubmissionController();
$testController = new TestController();

if (!($test = $testController->getTest($_GET['testId']))) {
    header('Location teacherHome.php');
    exit();
}

$submissions = $submissionController->getTestSubmissions($test['id']);
?>

<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Test <?= $test['code'] ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
    <link rel="stylesheet" href="../css/style.css">
    <script
            src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
            crossorigin="anonymous"></script>
    <script src="../js/showTestSubmissions.js"></script>
    <script type="text/javascript" charset="utf8"
            src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
</head>
<body>
<div class="container py-5">

    <div class="d-flex justify-content-between">
        <div class="h1">Odovzdané testy <<span class="font-italic"><?= $test['code'] ?></span>></div>
        <div class="align-self-center"><a href="teacherHome.php" class="btn btn-dark px-5">Domov</a></div>
    </div>

    <hr class="border">

    <div class="table-overflow">
        <table id="submissions-table" class="text-center display row-border hover">
            <thead>
            <tr>
                <th>ID študenta</th>
                <th>Krstné meno</th>
                <th>Priezvisko</th>
                <th>Celkový počet bodov</th>
                <th>Neobodované otázky</th>
                <th>Akcia</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $submissions_arr = [];
            foreach ($submissions as $submission):?>
                <tr>
                    <td><?= $submission['student_code'] ?></td>
                    <td><?= $submission['name'] ?></td>
                    <td><?= $submission['surname'] ?></td>
                    <td><?= $submission['points'] ?>b</td>
                    <?= '<td' . ($submission['not_evaluated'] ? ' class="text-danger">' . $submission['not_evaluated'] : '>' . $submission['not_evaluated']) . '</td>' ?>
                    <td><a href="editSubmission.php?submissionId=<?= $submission['submission_id'] ?>"
                           class="btn btn-dark">Upraviť hodnotenie</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
