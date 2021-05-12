<?php
/* Attempt to connect to MySQL database */
include_once 'config.php';

try{
$pdo = new PDO("mysql:host=" . HOSTNAME . ";dbname=" . DATABASE, USERNAME, PASSWORD);
// Set the PDO error mode to exception
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
die("ERROR: Could not connect. " . $e->getMessage());
}