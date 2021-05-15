<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'teacher') {
    header("Location: ../index.php");
    exit();
}

include_once '../TestController.php';

$testController = new TestController();

if (!($test = $testController->getTest($_GET['testId']))) {
    header('Location teacherHome.php');
    exit();
}
?>

<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Priebeh testu <?= $test['code'] ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/showResults.css">
    <link rel="stylesheet" href="../css/style.css">
    <script
            src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
            crossorigin="anonymous"></script>
    <script>
        $(function () {
            var testId = "<?php echo $_GET['testId']; ?>";

            var source = new EventSource("../test/notification.php?testId=" + testId);

            source.addEventListener('evt', (e) => {
                let data = JSON.parse(e.data);
                //console.log(data);
                let inProgress = $('#in-progress');
                let finished = $('#finished');
                inProgress.empty();
                finished.empty();

                $.each(data.not_submitted, function (index, item) {
                    let row = '<tr>' +
                        '<td>' + item.name + '</td>' +
                        '<td>' + item.student_code + '</td>' +
                        '<td>' + (item.status ? 'Píše' : '<span class="text-danger font-weight-bold">V inom okne</span>') + '</td>' +
                        '</tr>';
                    inProgress.append($(row));
                });
                $.each(data.submitted, function (index, item) {
                    let row = '<tr>' +
                        '<td>' + item.name + '</td>' +
                        '<td>' + item.student_code + '</td>' +
                        '<td>' + item.submitted_at + '</td>' +
                        '<td><a class="btn btn-dark" href="editSubmission.php?submissionId=' + item.submission_id + '">Zobraziť</a></td>' +
                        '</tr>';
                    finished.append($(row));
                });
            });
        });
    </script>
</head>
<body>
<div class="container py-5">

    <div class="d-flex justify-content-between">
        <div class="h1">Priebeh testu <<span class="font-italic"><?= $test['code'] ?></span>></div>
        <div class="align-self-center"><a href="teacherHome.php" class="btn btn-dark px-5">Domov</a></div>
    </div>


    <hr class="border">

    <div class="row">
        <div class="col-6 border-right">
            <div class="h2">Test píšu</div>
            <div class="table-overflow">
                <table class="table text-center">
                    <thead>
                    <tr>
                        <th class="w-33">Meno</th>
                        <th class="w-33">ID študenta</th>
                        <th class="w-33">Stav</th>
                    </tr>
                    </thead>
                    <tbody id="in-progress"></tbody>
                </table>
            </div>
        </div>
        <div class="col-6">
            <div class="h2">Test odovzdali</div>
            <div class="table-overflow">
                <table class="table text-center">
                    <thead>
                    <tr>
                        <th>Meno</th>
                        <th>ID študenta</th>
                        <th>Odovzdané</th>
                        <th>Akcia</th>
                    </tr>
                    </thead>
                    <tbody id="finished"></tbody>
                </table>
            </div>
        </div>
    </div>

</div>
</body>
</html>
