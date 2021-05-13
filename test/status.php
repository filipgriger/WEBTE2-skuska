<?php
include_once '../TestController.php';

$controller = new TestController();

$controller->changeStudentStatus($_POST["studentId"], $_POST["testCode"], $_POST["status"]);
?>