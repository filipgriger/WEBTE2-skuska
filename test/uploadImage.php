<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'student') {
    header("Location: ../index.php");
    exit();
}

$projectFolder = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'images'; // ~/project/images
//create folder structure for the fisrt time
if (!is_dir($projectFolder . DIRECTORY_SEPARATOR . 'submissions')) {
    mkdir($basePath . DIRECTORY_SEPARATOR . 'submissions', 0777, true);
}
if (!is_dir($projectFolder . DIRECTORY_SEPARATOR . 'submissions' . DIRECTORY_SEPARATOR . 'students')) {
    mkdir($basePath . DIRECTORY_SEPARATOR . 'submissions' . DIRECTORY_SEPARATOR . 'students', 0777, true);
}

$data = $_POST['image'];
$question_id = $_POST['id'];

list($type, $data) = explode(';', $data);
list(, $data) = explode(',', $data);
$data = base64_decode($data);

//$time = time();
$basePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'submissions' . DIRECTORY_SEPARATOR . 'students' . DIRECTORY_SEPARATOR;

if (!is_dir($basePath . $_SESSION['studentId'])) {
    $a = mkdir($basePath . $_SESSION['studentId'], 0777, true);
}

//file_put_contents('images/'.$time.'.png', $data);
file_put_contents($basePath . $_SESSION['studentId'] . DIRECTORY_SEPARATOR . 'question-' . $question_id . '.png', $data);

echo 'images/submissions/students/' . $_SESSION["studentId"] . '/question-' . $question_id . '.png';
$_SESSION['question' . $question_id] = 'images/submissions/students/' . $_SESSION["studentId"] . '/question-' . $question_id . '.png';
?>
