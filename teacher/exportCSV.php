<?php
$filename = 'submissions/test' . $_POST["test_id"] . '.csv';
$export_data = unserialize($_POST['export_data']);

$file = fopen($filename, "w");
fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

if($export_data) {
    fputcsv($file, array("ID", "Meno", "Priezvisko", "Počet bodov"), ";");
    foreach ($export_data as $line) {
        fputcsv($file, $line, ";");
    }
} else {
    fputcsv($file, array("Žiadne dáta"), ";");
}

fclose($file);

header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=" . $filename);
header("Content-Type: application/csv; ");

readfile($filename);

unlink($filename);
exit();