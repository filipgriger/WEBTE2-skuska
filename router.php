<?php
session_start();

var_dump($_SESSION);

include_once 'TestController.php';
include_once 'SubmissionController.php';
include_once 'config.php';

if ($_SESSION['role'] == 'student'){
    $studentId = $_SESSION['studentId'];
} elseif ($_SESSION['role'] == 'teacher'){
    $teacherId = $_SESSION['teacherId'];
}


if (isset($_POST)){
    switch ($_POST['route']){
        case 'createTest':
            (new TestController())->createTest($teacherId, $_POST['test-code'], $_POST['questions']);
            header('Location: teacher/teacherHome.php');
            exit();
        case 'saveAnswers':
            $testController = new TestController();
            $submissionController = new SubmissionController();

            $submissionId = $submissionController->createSubmission($_POST['test-id'], $studentId);
            $testController->saveAnswers($submissionId, $_POST['answers']);

            $submissionController->evaluateSubmission($submissionId);

            $_SESSION['submissionId'] = $submissionId;
            header('Location: templates/testSubmitted.php');
            exit();
    }
}