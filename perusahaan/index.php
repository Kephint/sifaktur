<?php
$page_title = 'Data Perusahaan';
require_once dirname(__DIR__) . '/config/koneksi.php';
include dirname(__DIR__) . '/includes/header.php';
include dirname(__DIR__) . '/includes/sidebar.php';


$msg = '';
$msg_type = '';
if (isset($_GET['msg'])) {
    switch ($_GET['msg']) {
        case 'tambah_ok':
            $msg = 'Data perusahaan berhasil ditambahkan!';
            $msg_type = 'success';
            break;
        case 'ubah_ok':
            $msg = 'Data perusahaan berhasil diubah!';
            $msg_type = 'success';
            break;
        case 'hapus_ok':
            $msg = 'Data perusahaan berhasil dihapus!';
            $msg_type = 'success';
            break;
        case 'hapus_gagal':
            $msg = 'Gagal menghapus data perusahaan! Data mungkin sedang digunakan di faktur.';
            $msg_type = 'danger';
            break;
    }
}


require_once dirname(__DIR__) . '/classes/Perusahaan.php';
$perusahaanObj = new Perusahaan();
$data_perusahaan = $perusahaanObj->getAll();
?>


<div class="page-header">
    <h2></i> Data Perusahaan</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>/index.php">Beranda</a></li>
            <li class="breadcrumb-item active">Data Perusahaan</li>
        </ol>
    </nav>
</div>

<?php if ($msg): ?>
<div class="alert alert-<?php echo $msg_type; ?> alert-auto-close">
    <i class="bi bi-<?php echo ($msg_type === 'success') ? 'check-circle' : 'exclamation-triangle'; ?>"></i>
    <?php echo $msg; ?>
</div>
<?php endif; ?>


<div class="mb-3">
    <a href="tambah.php" class="btn btn-primary-custom">
        <i class="bi bi-plus-circle"></i> Tambah Perusahaan
    </a>
</div>


<div class="content-card">
    <div class="card-header-custom">
        <i class="bi bi-list-ul"></i> Daftar Perusahaan
    </div>
    <div class="card-body p-0">
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Nama Perusahaan</th>
                        <th>Alamat</th>
                        <th>Telepon</th>
                        <th>Fax</th>
                        <th style="width:140px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($data_perusahaan && count($data_perusahaan) > 0): ?>
                        <?php $no = 1; foreach ($data_perusahaan as $row): ?>
                        <tr>
                            <td class="text-center"><?php echo $no++; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['nama_perusahaan']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['alamat'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($row['telp'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($row['fax'] ?? '-'); ?></td>
                            <td>
                                <div class="btn-group-action justify-content-center">
                                    <a href="ubah.php?id=<?php echo $row['id_perusahaan']; ?>" class="text-warning fs-5 mx-1" title="Ubah">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <button type="button" class="btn btn-link text-danger p-0 fs-5 mx-1 border-0 text-decoration-none" title="Hapus"
                                        onclick="confirmDelete('hapus.php?id=<?php echo $row['id_perusahaan']; ?>', '<?php echo addslashes($row['nama_perusahaan']); ?>')">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="bi bi-building d-block"></i>
                                    <p>Belum ada data perusahaan</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
