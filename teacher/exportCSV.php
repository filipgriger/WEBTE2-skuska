<?php
$filename = 'submissions/test' . $_POST["test_id"] . '.csv';
$export_data = unserialize($_POST['export_data']);

$file = fopen($filename, "w");

foreach ($export_data as $line) {
    fputcsv($file, $line, ";");
}

fclose($file);

header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=" . $filename);
header("Content-Type: application/csv; ");

readfile($filename);

unlink($filename);
exit();