<?php
$page_title = 'Data Produk';
require_once dirname(__DIR__) . '/config/koneksi.php';
include dirname(__DIR__) . '/includes/header.php';
include dirname(__DIR__) . '/includes/sidebar.php';


$msg = '';
$msg_type = '';
if (isset($_GET['msg'])) {
    switch ($_GET['msg']) {
        case 'tambah_ok':
            $msg = 'Data produk berhasil ditambahkan!';
            $msg_type = 'success';
            break;
        case 'ubah_ok':
            $msg = 'Data produk berhasil diubah!';
            $msg_type = 'success';
            break;
        case 'hapus_ok':
            $msg = 'Data produk berhasil dihapus!';
            $msg_type = 'success';
            break;
        case 'hapus_gagal':
            $msg = 'Gagal menghapus data produk! Produk mungkin sedang digunakan di faktur.';
            $msg_type = 'danger';
            break;
    }
}

$result = $conn->query("SELECT * FROM produk ORDER BY id_produk DESC");
?>


<div class="page-header">
    <h2> Data Produk</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>/index.php">Beranda</a></li>
            <li class="breadcrumb-item active">Data Produk</li>
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
        <i class="bi bi-plus-circle"></i> Tambah Produk
    </a>
</div>


<div class="content-card">
    <div class="card-header-custom">
        <i class="fa-solid fa-list-ul"></i> Daftar Produk
    </div>
    <div class="card-body p-0">
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Nama Produk</th>
                        <th>Jenis</th>
                        <th>Satuan</th>
                        <th class="text-end">Harga</th>
                        <th class="text-center">Stok</th>
                        <th style="width:140px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="text-center"><?php echo $no++; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['nama_produk']); ?></strong></td>
                            <td>
                                <span class="badge bg-secondary badge-status"><?php echo htmlspecialchars($row['jenis'] ?? '-'); ?></span>
                            </td>
                            <td><?php echo htmlspecialchars($row['satuan'] ?? '-'); ?></td>
                            <td class="text-end">Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></td>
                            <td class="text-center">
                                <?php
                                $stock_class = 'success';
                                if ($row['stock'] <= 10) $stock_class = 'danger';
                                elseif ($row['stock'] <= 30) $stock_class = 'warning';
                                ?>
                                <span class="badge bg-<?php echo $stock_class; ?> badge-status"><?php echo $row['stock']; ?></span>
                            </td>
                            <td>
                                <div class="btn-group-action justify-content-center">
                                    <a href="ubah.php?id=<?php echo $row['id_produk']; ?>" class="text-warning fs-5 mx-1" title="Ubah">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <button type="button" class="btn btn-link text-danger p-0 fs-5 mx-1 border-0 text-decoration-none" title="Hapus"
                                        onclick="confirmDelete('hapus.php?id=<?php echo $row['id_produk']; ?>', '<?php echo addslashes($row['nama_produk']); ?>')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="fa-solid fa-box d-block"></i>
                                    <p>Belum ada data produk</p>
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
