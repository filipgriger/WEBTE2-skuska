<?php

//pdf_exam_result.php


require_once('../class/pdf.php');
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'teacher') {
    header("Location: ../index.php");
    exit();
}

include '../SubmissionController.php';
include '../TestController.php';
include '../StudentController.php';

$submissionController = new SubmissionController();
$testController = new TestController();
$studentController = new StudentController();

if (!($test = $testController->getTest($_GET['testId']))) {
    header('Location teacherHome.php');
    exit();
}

$submissions = $submissionController->getTestSubmissions($test['id']);
$questions = $testController->getTestQuestions($test['id']);
if (isset($_GET["testId"])) {
    $submissions = $submissionController->getTestSubmissions($test['id']);

    $output = '<h2 align="center">Results of ' . $test['code'] . ' exam</h2><br><br>';
    foreach ($submissions as $submission) {
        $count = 1;
        $output .= '
		<br><table width="100%" border="1" cellpadding="5" cellspacing="0">
			<tr>
				<th>Student ID</th>
				<th>Student name</th>
				<th>Student surname</th>
				<th>Total of points</th>
			</tr>
		';

        $output .= '
		<tr>
			<td>' . $submission['student_code'] . '</td>
			<td>' . $submission['name'] . '</td>
			<td>' . $submission['surname'] . '</td>
			<td>' . $submission['points'] . '</td>
		</tr>
		';

        $output .= '</table><br>';

        foreach ($questions as $index => $question) {
            $output .= '<h4>Question number ' . $count . '</h4><br><br><p>' . $question['question'] . '</p><br><br>';
            $answer = $testController->getAnswerByStudentAndSub($submission['submission_id'], $question['id'], $question['type']);
            if ($question['type'] == "image") {
                $output .= '<h4>Student answered:</h4><br><br><img src="../' . $answer . '" alt="Mountain" style="max-height: 202px; max-width: 402px;"><br><br>';
            } 
            else if ($question['type'] == "expression"){
                $output .= '<h4>Nesplnená funkcionalita pre matematický výraz</h4><br><br><p></p><br><br>';
            }
            else {
                $output .= '<h4>Student answered:</h4><br><br><p>' . $answer . '</p><br><br>';
            }

            $count++;
        }


    }

    $pdf = new Pdf();

    $file_name = 'Exam_Results.pdf';

    $pdf->loadHtml($output);

    $pdf->render();

    $pdf->stream($file_name, array("Attachment" => false));

    exit(0);
}

?>