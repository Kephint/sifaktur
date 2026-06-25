<?php
$page_title = 'Data Customer';
require_once dirname(__DIR__) . '/config/koneksi.php';
include dirname(__DIR__) . '/includes/header.php';
include dirname(__DIR__) . '/includes/sidebar.php';


$msg = '';
$msg_type = '';
if (isset($_GET['msg'])) {
    switch ($_GET['msg']) {
        case 'tambah_ok':
            $msg = 'Data customer berhasil ditambahkan!';
            $msg_type = 'success';
            break;
        case 'ubah_ok':
            $msg = 'Data customer berhasil diubah!';
            $msg_type = 'success';
            break;
        case 'hapus_ok':
            $msg = 'Data customer berhasil dihapus!';
            $msg_type = 'success';
            break;
        case 'hapus_gagal':
            $msg = 'Gagal menghapus data customer! Data mungkin sedang digunakan di faktur.';
            $msg_type = 'danger';
            break;
    }
}

$result = $conn->query("SELECT * FROM customer ORDER BY id_customer DESC");
?>


<div class="page-header">
    <h2></i> Data Customer</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>/index.php">Beranda</a></li>
            <li class="breadcrumb-item active">Data Customer</li>
        </ol>
    </nav>
</div>

<?php if ($msg): ?>
<div class="alert alert-<?php echo $msg_type; ?> alert-auto-close">
    <i class="bi bi-<?php echo ($msg_type === 'success') ? 'check-circle' : 'exclamation-triangle'; ?>"></i>
    <?php echo $msg; ?>
</div>
<?php endif; ?>


<div class="mb-3 d-flex gap-2">
    <a href="tambah.php" class="btn btn-primary-custom">
        <i class="bi bi-plus-circle"></i> Tambah Customer
    </a>
    <a href="cetak.php" target="_blank" class="btn btn-info-custom">
        <i class="fa-solid fa-print"></i> Cetak Daftar
    </a>
</div>


<div class="content-card">
    <div class="card-header-custom">
        <i class="fa-solid fa-list-ul"></i> Daftar Customer
    </div>
    <div class="card-body p-0">
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Nama Customer</th>
                        <th>Perusahaan</th>
                        <th>Alamat</th>
                        <th style="width:160px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="text-center"><?php echo $no++; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['nama_customer']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['perusahaan_cust'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($row['alamat'] ?? '-'); ?></td>
                            <td>
                                <div class="btn-group-action justify-content-center">
                                    <a href="ubah.php?id=<?php echo $row['id_customer']; ?>" class="text-warning fs-5 mx-1" title="Ubah">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <button type="button" class="btn btn-link text-danger p-0 fs-5 mx-1 border-0 text-decoration-none" title="Hapus"
                                        onclick="confirmDelete('hapus.php?id=<?php echo $row['id_customer']; ?>', '<?php echo addslashes($row['nama_customer']); ?>')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="fa-solid fa-users d-block"></i>
                                    <p>Belum ada data customer</p>
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
