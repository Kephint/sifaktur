<?php
$page_title = 'Penjualan';
require_once dirname(__DIR__) . '/config/koneksi.php';
include dirname(__DIR__) . '/includes/header.php';
include dirname(__DIR__) . '/includes/sidebar.php';


$msg = '';
$msg_type = '';
if (isset($_GET['msg'])) {
    switch ($_GET['msg']) {
        case 'tambah_ok':
            $msg = 'Faktur penjualan berhasil ditambahkan!';
            $msg_type = 'success';
            break;
        case 'ubah_ok':
            $msg = 'Faktur penjualan berhasil diubah!';
            $msg_type = 'success';
            break;
        case 'hapus_ok':
            $msg = 'Faktur penjualan berhasil dihapus!';
            $msg_type = 'success';
            break;
    }
}

$result = $conn->query("
    SELECT f.*, c.nama_customer, p.nama_perusahaan
    FROM faktur f
    JOIN customer c ON f.id_customer = c.id_customer
    JOIN perusahaan p ON f.id_perusahaan = p.id_perusahaan
    ORDER BY f.id_faktur DESC
");
?>


<div class="page-header">
    <h2></i> Penjualan</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>/index.php">Beranda</a></li>
            <li class="breadcrumb-item active">Penjualan</li>
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
        <i class="bi bi-plus-circle"></i> Tambah Faktur
    </a>
</div>


<div class="content-card">
    <div class="card-header-custom">
        <i class="fa-solid fa-list-ul"></i> Daftar Penjualan
    </div>
    <div class="card-body p-0">
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>No. Faktur</th>
                        <th>Tanggal</th>
                        <th>Customer</th>
                        <th>Perusahaan</th>
                        <th>Metode</th>
                        <th class="text-end">Grand Total</th>
                        <th style="width:180px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="text-center"><?php echo $no++; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['no_faktur']); ?></strong></td>
                            <td><?php echo date('d/m/Y', strtotime($row['tgl_faktur'])); ?></td>
                            <td><?php echo htmlspecialchars($row['nama_customer']); ?></td>
                            <td><?php echo htmlspecialchars($row['nama_perusahaan']); ?></td>
                            <td>
                                <span class="badge bg-<?php echo ($row['metode_bayar'] === 'Tunai') ? 'success' : 'primary'; ?> badge-status">
                                    <?php echo htmlspecialchars($row['metode_bayar']); ?>
                                </span>
                            </td>
                            <td class="text-end"><strong>Rp <?php echo number_format($row['grand_total'], 0, ',', '.'); ?></strong></td>
                            <td>
                                <div class="btn-group-action justify-content-center">
                                    <a href="cetak.php?id=<?php echo $row['id_faktur']; ?>" target="_blank" class="text-info fs-5 mx-1" title="Cetak">
                                        <i class="fa-solid fa-print"></i>
                                    </a>
                                    <a href="ubah.php?id=<?php echo $row['id_faktur']; ?>" class="text-warning fs-5 mx-1" title="Ubah">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <button type="button" class="btn btn-link text-danger p-0 fs-5 mx-1 border-0 text-decoration-none" title="Hapus"
                                        onclick="confirmDelete('hapus.php?id=<?php echo $row['id_faktur']; ?>', '<?php echo addslashes($row['no_faktur']); ?>')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="fa-solid fa-receipt d-block"></i>
                                    <p>Belum ada data faktur penjualan</p>
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
