<?php
include 'config.php';

try {
<<<<<<< HEAD

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
=======
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
>>>>>>> c37076d28fd4c49ef0ebc612bac302f482bd2c60
