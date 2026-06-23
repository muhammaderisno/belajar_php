<?php
require_once 'config.php';

$error = '';
$success = '';

// Mengatur mode error PDO
try {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

// Handler untuk aksi Kurangi Stok
if (isset($_GET['action']) && $_GET['action'] === 'reduce' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    try {
        // Kurangi stok hanya jika stok > 0
        $stmt = $conn->prepare("UPDATE products SET stok = stok - 1 WHERE id = :id AND stok > 0");
        $stmt->execute([':id' => $id]);

        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        $error = "Gagal mengurangi stok: " . $e->getMessage();
    }
}

// Handler untuk aksi Hapus Produk
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    try {
        $stmt = $conn->prepare("DELETE FROM products WHERE id = :id");
        $stmt->execute([':id' => $id]);

        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        $error = "Gagal menghapus produk: " . $e->getMessage();
    }
}

// Mengambil semua data produk
try {
    $stmt = $conn->query("SELECT * FROM products ORDER BY id DESC");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Gagal mengambil data produk: " . $e->getMessage());
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .table-container {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            margin-top: 20px;
        }

        .action-btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
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
                            <a class="nav-link active" aria-current="page" href="index.php">Daftar Produk</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="add.php">Tambah Produk</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="text-primary">Daftar Produk</h2>
                <a href="add.php" class="btn btn-success">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Produk Baru
                </a>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <div class="table-container">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" style="width: 80px;">ID</th>
                                <th scope="col">Nama Produk</th>
                                <th scope="col" style="width: 150px;">Stok</th>
                                <th scope="col" style="width: 200px;">Harga</th>
                                <th scope="col" style="width: 320px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($products) > 0): ?>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($product['ID']); ?></td>
                                        <td class="fw-semibold"><?php echo htmlspecialchars($product['name']); ?></td>
                                        <td>
                                            <?php if ($product['stok'] > 0): ?>
                                                <span class="badge bg-success-subtle text-success fs-6"><?php echo htmlspecialchars($product['stok']); ?> pcs</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger-subtle text-danger fs-6">Habis</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-primary fw-bold">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <!-- Kurangi Stok -->
                                                <?php if ($product['stok'] > 0): ?>
                                                    <a href="index.php?action=reduce&id=<?php echo $product['ID']; ?>" class="btn btn-warning action-btn text-white" title="Kurangi Stok 1">
                                                        <i class="bi bi-dash-circle me-1"></i> Kurang Stok
                                                    </a>
                                                <?php else: ?>
                                                    <button class="btn btn-secondary action-btn" disabled>
                                                        <i class="bi bi-dash-circle me-1"></i> Kurang Stok
                                                    </button>
                                                <?php endif; ?>

                                                <!-- Edit Produk -->
                                                <a href="edit.php?id=<?php echo $product['ID']; ?>" class="btn btn-primary action-btn" title="Edit Produk">
                                                    <i class="bi bi-pencil-square me-1"></i> Edit
                                                </a>

                                                <!-- Hapus Produk -->
                                                <a href="index.php?action=delete&id=<?php echo $product['ID']; ?>"
                                                    class="btn btn-danger action-btn"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus produk &quot;<?php echo htmlspecialchars($product['name']); ?>&quot;?');"
                                                    title="Hapus Produk">
                                                    <i class="bi bi-trash me-1"></i> Hapus
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada produk. Silakan tambah produk baru terlebih dahulu.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>