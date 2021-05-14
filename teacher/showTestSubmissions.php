<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Test<?= $test['code'] ?></title>
    <script
            src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
            crossorigin="anonymous"></script>
    <script src="../js/showTestSubmissions.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8"
            src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
    <script>
        window.onload = function () {
            var testId = "<?php echo $_GET['testId']; ?>"

            var source = new EventSource("../test/notification.php?testId=" + testId);
            source.addEventListener('evt', (e) => {
                //console.log(JSON.parse(e.data)['not_submitted'].length);
                if(JSON.parse(e.data)['tabbed_out'].length) {
                    $(".missing-students").html("Študenti, ktorí majú otvorený iný tab (ids): " + JSON.parse(e.data)['tabbed_out']);
                } else {
                    if($(".missing-students").html() != "") {
                        $(".missing-students").html("");
                    }
                }
                if(JSON.parse(e.data)['not_submitted'].length) {
                    $(".not_submitted").html("Test ešte píše (ids): " + JSON.parse(e.data)['not_submitted']);
                } else {
                    if($(".not_submitted").html() != "") {
                        $(".not_submitted").html("");
                    }
                }
                if(JSON.parse(e.data)['submitted'].length) {
                    $(".submitted").html("Test už dopísali (ids): " + JSON.parse(e.data)['submitted']);
                } else {
                    if($(".submitted").html() != "") {
                        $(".submitted").html("");
                    }
                }
            });
        }
    </script>
</head>
<body>
<div class="container py-5">

    <div class="missing-students"></div>

    <div class="d-flex justify-content-between">
        <div class="h1">Test <<span class="font-italic"><?= $test['code'] ?></span>> submissions</div>
        <div class="align-self-center"><a href="teacherHome.php" class="btn btn-dark px-5">Home</a></div>
    </div>

    <hr class="border">

    <form method='post' action='exportCSV.php'>
        <input type='submit' value='Export CSV' name='Export'>

        <table id="submissions-table" class="text-center display row-border hover">
            <thead>
            <tr>
                <th>Student ID</th>
                <th>First name</th>
                <th>Last name</th>
                <th>Total</th>
                <th>Answers not evaluated</th>
                <th>Action</th>
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
                           class="btn btn-dark">Edit evaluation</a></td>
                </tr>
                <?php
                $submissions_arr[] = array($submission['student_code'], $submission['name'], $submission['surname'], doubleval(substr($submission["points"], 0, strpos($submission["points"], "/")-1)));
            endforeach; ?>
            </tbody>
        </table>
        <?php
        $serialize_submissions_arr = serialize($submissions_arr);
        ?>
        <textarea name='export_data' style='display: none;'><?php echo $serialize_submissions_arr; ?></textarea>
        <input type="hidden" name="test_id" value="<?php echo $_GET['testId'] ?>">
    </form>
    <div class="not_submitted"></div>
    <div class="submitted"></div>
</div>
</div>
</body>
</html>
