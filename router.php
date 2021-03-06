<?php
session_start();

include_once 'TestController.php';
include_once 'SubmissionController.php';
include_once 'config.php';

if ($_SESSION['role'] == 'student') {
    $studentId = $_SESSION['studentId'];
} elseif ($_SESSION['role'] == 'teacher') {
    $teacherId = $_SESSION['teacherId'];
}

if (isset($_POST)) {
    switch ($_POST['route']) {
        case 'createTest':
            (new TestController())->createTest($teacherId, $_POST['test-code'], $_POST['test-time'], $_POST['questions']);
            header('Location: teacher/teacherHome.php');
            exit();
        case 'saveAnswers':
            $testController = new TestController();
            $submissionController = new SubmissionController();

            $submissionId = $submissionController->createSubmission($_POST['test-id'], $studentId);
            $testController->saveAnswers($studentId, $submissionId, $_POST['answers']);

            $submissionController->evaluateSubmission($submissionId);

            $submissionController->updateStatusTestSubmitted($studentId, $_POST['test-id']);

            $_SESSION['submissionId'] = $submissionId;
            header('Location: templates/testSubmitted.php');
            exit();
        case 'updateTestStatus':
            $testController = new TestController();
            $newStatus = $testController->updateTestStatus($_POST['testId'], $_POST['status']);
            echo $newStatus;
            break;
        case 'editSubmission':
            $submissionController = new SubmissionController();
            $submissionController->editSubmission($_POST['submissionId'], $_POST['modifications']);
            header('Location: teacher/showTestSubmissions.php?testId=' . $_POST['testId']);
            break;
    }
}