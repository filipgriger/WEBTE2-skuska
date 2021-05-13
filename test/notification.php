<?php
include_once '../TestController.php';

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

$controller = new TestController();

$index = 0;

while(true) {
    if($controller->studentStatusChanged($_GET["testId"])) {
        sendSSE(++$index, json_encode($controller->studentStatusChanged($_GET["testId"])));
    } else {
        sendSSE(++$index, null);
    }
    sleep(2);
}

function sendSSE($id, $msg) {
    echo "id: $id\n";
    echo "event: evt\n";
    echo "data: $msg\n\n";

    ob_flush();
    flush();
}
?>