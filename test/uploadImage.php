<?php
$data = $_POST['image'];
$question_id = $_POST['id'];

list($type, $data) = explode(';', $data);
list(, $data)      = explode(',', $data);
$data = base64_decode($data);

//$time = time();

//file_put_contents('images/'.$time.'.png', $data);
file_put_contents('images/'.$question_id.'.png', $data);

echo $question_id . '.png';
?>
