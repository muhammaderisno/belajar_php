<?php
$host = "localhost";
$db = "belajar_php";
$usern = "root";
$pass = "";
$dsn = "mysql:host=$host;dbname=$db";
$conn = new PDO($dsn, $usern, $pass);
