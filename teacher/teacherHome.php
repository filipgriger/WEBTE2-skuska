<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'teacher') {
    header("Location: ../index.php");
    exit();
}

include '../SubmissionController.php';
$submissionController = new SubmissionController();
?>

<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Domov</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <script
            src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
            crossorigin="anonymous"></script>
    <script src="../js/teacherHome.js"></script>
</head>
<body>
<div class="container pt-5">
    <div class="row">
        <div class="col-6 text-left">
            <div class="h6 float-left">Prihlásený ako <span class="font-weight-bold"><?= $_SESSION['email'] ?></span>
            </div>
        </div>
        <div class="col-6 text-right">
            <a href="../logout.php" class="btn btn-dark float-right">Odhlásiť sa</a>
        </div>
    </div>

    <div class="my-3">
        <a href="../test/createTest.php" class="btn btn-dark btn-block">Vytvoriť test</a>
    </div>
    <h2>Testy</h2>
    <div class="table-overflow my-3">
        <table class="table text-center">
            <thead>
            <tr>
                <th>ID</th>
                <th>Kľúč</th>
                <th>Dátum vytvorenia</th>
                <th>Stav</th>
                <th>Akcia</th>
                <th>Export</th>
            </tr>
            </thead>
            <tbody>
            <?php
            include_once '../TestController.php';
            $controller = new TestController();
            $tests = $controller->getAllTests();
            foreach ($tests as $test): ?>
                <tr>
                    <td><?= $test['id'] ?></td>
                    <td><?= $test['code'] ?></td>
                    <td><?= $test['created_at'] ?></td>
                    <td>
                        <i class="fas fa-2x <?= ($test['active'] ? 'text-success fa-check' : 'text-danger fa-times') ?>-circle"></i>
                    </td>
                    <td>
                        <div class="table-action">
                            <div class="table-action-child">
                                <button class="btn btn-<?= ($test['active'] ? 'danger deactivate-test" data-status="0' : 'success activate-test" data-status="1') ?>"
                                        data-test-id="<?= $test['id'] ?>">
                                    <?= ($test['active'] ? 'Deaktivovať' : 'Aktivovať') ?>
                                </button>
                            </div>
                            <div class="table-action-child">
                                <a class="btn btn-dark" href="showTestSubmissions.php?testId=<?= $test['id'] ?>">Odovzdané
                                    testy</a>
                            </div>
                            <div class="table-action-child">
                                <a class="btn btn-dark" href="showTestProgress.php?testId=<?= $test['id'] ?>">Zobraziť
                                    priebeh</a>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="table-action">
                            <div class="table-action-child">
                                <a class="btn btn-warning table-action-child-inline"
                                   href="pdf_export.php?testId=<?= $test['id'] ?>">PDF</a>
                            </div>
                            <div class="table-action-child">
                                <form class="table-action-child-inline" method='post' action='exportCSV.php'>
                                    <button type='submit' class="btn btn-primary" value='Export CSV' name='Export'>CSV
                                    </button>
                                    <?php
                                    $submissions_arr = [];
                                    $submissions = $submissionController->getTestSubmissions($test['id']);
                                    foreach ($submissions as $submission):
                                        $submissions_arr[] = array($submission['student_code'], $submission['name'], $submission['surname'], doubleval(substr($submission["points"], 0, strpos($submission["points"], "/") - 1)));
                                    endforeach;
                                    $serialize_submissions_arr = serialize($submissions_arr);
                                    ?>
                                    <textarea name='export_data'
                                              style='display: none;'><?php echo $serialize_submissions_arr; ?></textarea>
                                    <input type="hidden" name="test_id" value="<?php echo $test['id']; ?>">
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>