<?php
include 'config.php';

try {

    $pdo = new PDO($dsn, $usern, $pass);
    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    $query = "SELECT * FROM products";

    $stmt = $pdo->query($query);

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

   foreach ($products as $product) {
    echo htmlspecialchars($product['name']) . "<br>";
}

} catch (PDOException $e) {

    echo "koneksi gagal: " . $e->getMessage();
}
?>
