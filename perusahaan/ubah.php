<?php
$page_title = 'Ubah Perusahaan';
require_once dirname(__DIR__) . '/config/koneksi.php';


$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: index.php');
    exit;
}


require_once dirname(__DIR__) . '/classes/Perusahaan.php';
$perusahaanObj = new Perusahaan();
$data = $perusahaanObj->getById($id);

if (!$data) {
    header('Location: index.php');
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama   = trim($_POST['nama_perusahaan'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $telp   = trim($_POST['telp'] ?? '');
    $fax    = trim($_POST['fax'] ?? '');

    if (empty($nama) || empty($alamat) || empty($telp) || empty($fax)) {
        $error = 'Semua kolom (Nama, Alamat, Telepon, Fax) wajib diisi!';
    } elseif (!preg_match('/[a-zA-Z]/', $nama)) {
        $error = 'Nama perusahaan harus mengandung setidaknya satu huruf!';
    } elseif (!preg_match('/^[a-zA-Z\s\.\-\'&]+$/', $nama)) {
        $error = 'Nama perusahaan hanya boleh berisi huruf dan spasi!';
    } elseif (!preg_match('/^[0-9]{8,15}$/', $telp)) {
        $error = 'Format telepon tidak valid! Harus berupa angka 8-15 digit.';
    } elseif (!preg_match('/^[0-9]{8,15}$/', $fax)) {
        $error = 'Format fax tidak valid! Harus berupa angka 8-15 digit.';
    } else {
        if ($perusahaanObj->update($id, $nama, $alamat, $telp, $fax)) {
            header('Location: index.php?msg=ubah_ok');
            exit;
        } else {
            $error = 'Gagal mengubah data perusahaan. Silakan cek logs/error.log.';
        }
    }
}

include dirname(__DIR__) . '/includes/header.php';
include dirname(__DIR__) . '/includes/sidebar.php';
?>


<div class="page-header">
    <h2><i class="bi bi-pencil-square"></i> Ubah Perusahaan</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>/index.php">Beranda</a></li>
            <li class="breadcrumb-item"><a href="index.php">Data Perusahaan</a></li>
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
        <i class="fa-solid fa-building"></i> Form Ubah Perusahaan
    </div>
    <div class="card-body">
        <form method="POST" action="">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nama_perusahaan" class="form-label">Nama Perusahaan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nama_perusahaan" name="nama_perusahaan" 
                           pattern="[a-zA-Z\s\.\-\'&]+" title="Hanya boleh huruf dan spasi"
                           value="<?php echo htmlspecialchars($data['nama_perusahaan']); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="alamat" name="alamat"
                           value="<?php echo htmlspecialchars($data['alamat'] ?? ''); ?>" required minlength="5">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="telp" class="form-label">Telepon <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="telp" name="telp"
                           pattern="[0-9]{8,15}" title="Harus berupa angka, 8-15 digit"
                           value="<?php echo htmlspecialchars($data['telp'] ?? ''); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="fax" class="form-label">Fax <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="fax" name="fax"
                           pattern="[0-9]{8,15}" title="Harus berupa angka, 8-15 digit"
                           value="<?php echo htmlspecialchars($data['fax'] ?? ''); ?>" required>
                </div>
            </div>
            <hr>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success-custom">
                    <i class="fa-solid fa-check"></i> Simpan Perubahan
                </button>
                <a href="index.php" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
