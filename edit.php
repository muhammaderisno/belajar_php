<?php
require_once 'config.php';

$error = '';
$success = '';

// Memeriksa parameter ID
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);

// Mengambil data produk berdasarkan ID
try {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        header("Location: index.php");
        exit;
    }
} catch (PDOException $e) {
    die("Gagal memuat data produk: " . $e->getMessage());
}

// Proses form update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $stok = isset($_POST['stok']) ? trim($_POST['stok']) : '';
    $price = isset($_POST['price']) ? trim($_POST['price']) : '';

    if ($name === '' || $stok === '' || $price === '') {
        $error = 'Semua field (Nama, Stok, Harga) harus diisi!';
    } elseif (!is_numeric($stok) || intval($stok) < 0) {
        $error = 'Stok harus berupa angka bulat positif!';
    } elseif (!is_numeric($price) || floatval($price) < 0) {
        $error = 'Harga harus berupa angka positif!';
    } else {
        try {
            $stmt = $conn->prepare("UPDATE products SET name = :name, stok = :stok, price = :price WHERE id = :id");
            $stmt->execute([
                ':name' => $name,
                ':stok' => intval($stok),
                ':price' => floatval($price),
                ':id' => $id
            ]);
            
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            $error = 'Gagal memperbarui data: ' . $e->getMessage();
        }
    }
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .form-container {
      max-width: 600px;
      margin: 50px auto;
      background: #ffffff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }
  </style>
</head>

<body>
  <div class="container-xxl">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary rounded mb-4 mt-3">
      <div class="container-fluid">
        <a class="navbar-brand font-weight-bold" href="index.php">Belajar PHP</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link" href="index.php">Daftar Produk</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="add.php">Tambah Produk</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container">
      <div class="form-container">
        <h3 class="mb-4 text-center text-primary">Edit Produk</h3>
        
        <?php if (!empty($error)): ?>
          <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($error); ?>
          </div>
        <?php endif; ?>

        <form action="edit.php?id=<?php echo $product['ID']; ?>" method="POST">
          <div class="mb-3">
            <label for="name" class="form-label">Nama Produk</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan nama produk" value="<?php echo htmlspecialchars($product['name']); ?>" required>
          </div>
          
          <div class="mb-3">
            <label for="stok" class="form-label">Stok</label>
            <input type="number" class="form-control" id="stok" name="stok" min="0" placeholder="Masukkan jumlah stok" value="<?php echo htmlspecialchars($product['stok']); ?>" required>
          </div>

          <div class="mb-3">
            <label for="price" class="form-label">Harga</label>
            <div class="input-group">
              <span class="input-group-text">Rp</span>
              <input type="number" step="any" class="form-control" id="price" name="price" min="0" placeholder="Masukkan harga produk" value="<?php echo htmlspecialchars($product['price']); ?>" required>
            </div>
          </div>
          
          <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
            <a href="index.php" class="btn btn-secondary me-md-2">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
