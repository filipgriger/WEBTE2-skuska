<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'student'){
    header("Location: ../index.php");
    exit();
}

$question_id = $_POST['id'];

$path = '../images/submissions/students/'.$_SESSION["studentId"].'/question-'.$question_id.'.png';

if (file_exists($path)) {
    echo $path;
}
?>