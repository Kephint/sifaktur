<?php
$current_page = 'laporan_produk';
$page_title = 'Laporan Produk';
require_once dirname(__DIR__) . '/config/koneksi.php';
include dirname(__DIR__) . '/includes/header.php';
include dirname(__DIR__) . '/includes/sidebar.php';

$result = $conn->query("SELECT * FROM produk ORDER BY nama_produk ASC");
?>

<div class="page-header">
    <h2> Laporan Produk</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>/index.php">Beranda</a></li>
            <li class="breadcrumb-item active">Laporan Produk</li>
        </ol>
    </nav>
</div>

<div class="mb-3">
    <a href="cetak_produk.php" target="_blank" class="btn btn-primary-custom">
        <i class="bi bi-printer"></i> Cetak Laporan
    </a>
</div>

<div class="content-card">
    <div class="card-header-custom">
        <i class="bi bi-box-seam"></i> Ringkasan Stok Produk
    </div>
    <div class="card-body p-0">
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width:50px;" class="text-center">No</th>
                        <th>Nama Produk</th>
                        <th>Jenis</th>
                        <th>Satuan</th>
                        <th class="text-end">Harga Satuan</th>
                        <th class="text-center">Stok Tersedia</th>
                        <th class="text-end">Nilai Aset</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php 
                        $no = 1; 
                        $total_stok = 0;
                        $total_aset = 0;
                        while ($row = $result->fetch_assoc()): 
                            $nilai_aset = $row['stock'] * $row['price'];
                            $total_stok += $row['stock'];
                            $total_aset += $nilai_aset;
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $no++; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['nama_produk']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['jenis'] ?? '-'); ?></td>
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
                            <td class="text-end">Rp <?php echo number_format($nilai_aset, 0, ',', '.'); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="bi bi-box-seam d-block"></i>
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

<div class="row mt-4">
    <div class="col-md-6 offset-md-6">
        <div class="content-card bg-light">
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <th style="width:50%;">Total Item Produk</th>
                        <td class="text-end"><strong><?php echo isset($no) ? $no - 1 : 0; ?></strong></td>
                    </tr>
                    <tr>
                        <th>Total Stok Keseluruhan</th>
                        <td class="text-end"><strong><?php echo isset($total_stok) ? number_format($total_stok, 0, ',', '.') : 0; ?></strong></td>
                    </tr>
                    <tr>
                        <th>Total Nilai Aset</th>
                        <td class="text-end"><strong>Rp <?php echo isset($total_aset) ? number_format($total_aset, 0, ',', '.') : 0; ?></strong></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
