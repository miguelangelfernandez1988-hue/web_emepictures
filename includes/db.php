<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//$pdo = new PDO('mysql:host=localhost:3306;dbname=tbp;charset=utf8', 'root', '');

$pdo = new PDO('mysql:host=sql311.infinityfree.com:3306;dbname=if0_39404932_eme;charset=utf8', 'if0_39404932', 'Miguel001905');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>