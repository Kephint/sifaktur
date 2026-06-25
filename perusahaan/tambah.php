<?php
$page_title = 'Tambah Perusahaan';
require_once dirname(__DIR__) . '/config/koneksi.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama   = trim($_POST['nama_perusahaan'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $telp   = trim($_POST['telp'] ?? '');
    $fax    = trim($_POST['fax'] ?? '');

    if (!empty($nama)) {
        require_once dirname(__DIR__) . '/classes/Perusahaan.php';
        $perusahaanObj = new Perusahaan();
        if ($perusahaanObj->insert($nama, $alamat, $telp, $fax)) {
            header('Location: index.php?msg=tambah_ok');
            exit;
        } else {
            $error = 'Gagal menyimpan data perusahaan. Silakan cek logs/error.log.';
        }
    } else {
        $error = 'Nama perusahaan wajib diisi!';
    }
}

include dirname(__DIR__) . '/includes/header.php';
include dirname(__DIR__) . '/includes/sidebar.php';
?>


<div class="page-header">
    <h2></i> Tambah Perusahaan</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>/index.php">Beranda</a></li>
            <li class="breadcrumb-item"><a href="index.php">Data Perusahaan</a></li>
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
        <i class="fa-solid fa-building"></i> Form Tambah Perusahaan
    </div>
    <div class="card-body">
        <form method="POST" action="">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nama_perusahaan" class="form-label">Nama Perusahaan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nama_perusahaan" name="nama_perusahaan" 
                           value="<?php echo htmlspecialchars($_POST['nama_perusahaan'] ?? ''); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <input type="text" class="form-control" id="alamat" name="alamat"
                           value="<?php echo htmlspecialchars($_POST['alamat'] ?? ''); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="telp" class="form-label">Telepon</label>
                    <input type="text" class="form-control" id="telp" name="telp"
                           value="<?php echo htmlspecialchars($_POST['telp'] ?? ''); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="fax" class="form-label">Fax</label>
                    <input type="text" class="form-control" id="fax" name="fax"
                           value="<?php echo htmlspecialchars($_POST['fax'] ?? ''); ?>">
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
