<?php
$page_title = 'Ubah Produk';
require_once dirname(__DIR__) . '/config/koneksi.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: index.php'); exit; }

$stmt = $conn->prepare("SELECT * FROM produk WHERE id_produk = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$data) { header('Location: index.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama   = trim($_POST['nama_produk'] ?? '');
    $price  = floatval($_POST['price'] ?? 0);
    $jenis  = trim($_POST['jenis'] ?? '');
    $satuan = trim($_POST['satuan'] ?? '');
    $stock  = intval($_POST['stock'] ?? 0);

    if (!empty($nama) && $price >= 0) {
        $jenis  = !empty($jenis) ? $jenis : null;
        $satuan = !empty($satuan) ? $satuan : null;

        $stmt = $conn->prepare("UPDATE produk SET nama_produk=?, price=?, jenis=?, satuan=?, stock=? WHERE id_produk=?");
        $stmt->bind_param('sdssii', $nama, $price, $jenis, $satuan, $stock, $id);
        $stmt->execute();
        $stmt->close();
        header('Location: index.php?msg=ubah_ok');
        exit;
    } else {
        $error = 'Nama produk dan harga wajib diisi!';
    }
}

include dirname(__DIR__) . '/includes/header.php';
include dirname(__DIR__) . '/includes/sidebar.php';
?>


<div class="page-header">
    <h2><i class="bi bi-pencil-square"></i> Ubah Produk</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>/index.php">Beranda</a></li>
            <li class="breadcrumb-item"><a href="index.php">Data Produk</a></li>
            <li class="breadcrumb-item active">Ubah</li>
        </ol>
    </nav>
</div>

<?php if (isset($error)): ?>
<div class="alert alert-danger alert-auto-close">
    <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
</div>
<?php endif; ?>

<div class="content-card">
    <div class="card-header-custom">
        <i class="bi bi-box-seam"></i> Form Ubah Produk
    </div>
    <div class="card-body">
        <form method="POST" action="">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nama_produk" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nama_produk" name="nama_produk" 
                           value="<?php echo htmlspecialchars($data['nama_produk']); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="jenis" class="form-label">Jenis</label>
                    <input type="text" class="form-control" id="jenis" name="jenis"
                           value="<?php echo htmlspecialchars($data['jenis'] ?? ''); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="satuan" class="form-label">Satuan</label>
                    <input type="text" class="form-control" id="satuan" name="satuan"
                           value="<?php echo htmlspecialchars($data['satuan'] ?? ''); ?>"
                           placeholder="Contoh: strip, tablet, ampul">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="price" class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="price" name="price" step="0.01" min="0"
                           value="<?php echo $data['price']; ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="stock" class="form-label">Stok <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="stock" name="stock" min="0"
                           value="<?php echo $data['stock']; ?>" required>
                </div>
            </div>
            <hr>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success-custom">
                    <i class="bi bi-check-lg"></i> Simpan Perubahan
                </button>
                <a href="index.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
