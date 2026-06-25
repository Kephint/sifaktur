<?php
$page_title = 'Tambah Customer';
require_once dirname(__DIR__) . '/config/koneksi.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama       = trim($_POST['nama_customer'] ?? '');
    $perusahaan = trim($_POST['perusahaan_cust'] ?? '');
    $alamat     = trim($_POST['alamat'] ?? '');

    if (empty($nama) || empty($perusahaan) || empty($alamat)) {
        $error = 'Semua kolom (Nama, Perusahaan, Alamat) wajib diisi!';
    } elseif (!preg_match('/^[a-zA-Z\s\.\-\']+$/', $nama)) {
        $error = 'Nama customer hanya boleh berisi huruf dan spasi!';
    } elseif (!preg_match('/^[a-zA-Z\s\.\-\'&]+$/', $perusahaan)) {
        $error = 'Nama perusahaan hanya boleh berisi huruf dan spasi!';
    } else {
        $alamat     = !empty($alamat) ? $alamat : null;

        $stmt = $conn->prepare("INSERT INTO customer (nama_customer, perusahaan_cust, alamat) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $nama, $perusahaan, $alamat);
        $stmt->execute();
        $stmt->close();
        header('Location: index.php?msg=tambah_ok');
        exit;
    }
}

include dirname(__DIR__) . '/includes/header.php';
include dirname(__DIR__) . '/includes/sidebar.php';
?>


<div class="page-header">
    <h2></i> Tambah Customer</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>/index.php">Beranda</a></li>
            <li class="breadcrumb-item"><a href="index.php">Data Customer</a></li>
            <li class="breadcrumb-item active">Tambah</li>
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
        <i class="fa-solid fa-users"></i> Form Tambah Customer
    </div>
    <div class="card-body">
        <form method="POST" action="">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nama_customer" class="form-label">Nama Customer <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nama_customer" name="nama_customer" 
                           pattern="[a-zA-Z\s\.\-\']+" title="Hanya boleh huruf dan spasi"
                           value="<?php echo htmlspecialchars($_POST['nama_customer'] ?? ''); ?>" required minlength="2">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="perusahaan_cust" class="form-label">Perusahaan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="perusahaan_cust" name="perusahaan_cust"
                           pattern="[a-zA-Z\s\.\-\'&]+" title="Hanya boleh huruf dan spasi"
                           value="<?php echo htmlspecialchars($_POST['perusahaan_cust'] ?? ''); ?>" required minlength="2">
                </div>
                <div class="col-md-12 mb-3">
                    <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3" required minlength="5"><?php echo htmlspecialchars($_POST['alamat'] ?? ''); ?></textarea>
                </div>
            </div>
            <hr>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success-custom">
                    <i class="fa-solid fa-check"></i> Simpan
                </button>
                <a href="index.php" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
