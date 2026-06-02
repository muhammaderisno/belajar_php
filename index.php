<?php
include 'config.php';

try {
    $pdo = new PDO($dsn,$usern,$pass);

    //set error mode
    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);

    echo "koneksi berhasil";
} catch (PDOException $e) {
    die "koneksi gagal: " . $e->getMessage();
}



?>