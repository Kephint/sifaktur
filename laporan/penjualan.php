<?php
$current_page = 'laporan_penjualan';
$page_title = 'Laporan Penjualan';
require_once dirname(__DIR__) . '/config/koneksi.php';

$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-t');

if (empty($start_date) || empty($end_date)) {
    $start_date = date('Y-m-01');
    $end_date = date('Y-m-t');
}

$query = "SELECT f.*, c.nama_customer 
          FROM faktur f 
          JOIN customer c ON f.id_customer = c.id_customer 
          WHERE f.tgl_faktur BETWEEN ? AND ? 
          ORDER BY f.tgl_faktur DESC, f.id_faktur DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param('ss', $start_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();

include dirname(__DIR__) . '/includes/header.php';
include dirname(__DIR__) . '/includes/sidebar.php';
?>

<div class="page-header">
    <h2> Laporan Penjualan</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>/index.php">Beranda</a></li>
            <li class="breadcrumb-item active">Laporan Penjualan</li>
        </ol>
    </nav>
</div>

<div class="content-card mb-4">
    <div class="card-body">
        <form method="GET" action="" class="row align-items-end">
            <div class="col-md-4 mb-3 mb-md-0">
                <label for="start_date" class="form-label">Dari Tanggal</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <label for="end_date" class="form-label">Sampai Tanggal</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary-custom w-100">
                    <i class="fa-solid fa-magnifying-glass"></i> Tampilkan Data
                </button>
            </div>
        </form>
    </div>
</div>

<div class="mb-3">
    <a href="cetak_penjualan.php?start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>" target="_blank" class="btn btn-success-custom">
        <i class="fa-solid fa-print"></i> Cetak Laporan (PDF/Print)
    </a>
</div>

<div class="content-card">
    <div class="card-header-custom">
        <i class="fa-solid fa-receipt"></i> Data Transaksi Penjualan (Periode: <?php echo date('d/m/Y', strtotime($start_date)) . ' s.d. ' . date('d/m/Y', strtotime($end_date)); ?>)
    </div>
    <div class="card-body p-0">
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width:50px;" class="text-center">No</th>
                        <th>No Faktur</th>
                        <th>Tanggal</th>
                        <th>Customer</th>
                        <th class="text-center">Pembayaran</th>
                        <th class="text-end">PPN</th>
                        <th class="text-end">Grand Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if ($result && $result->num_rows > 0): 
                        $no = 1; 
                        $total_ppn = 0;
                        $total_grand = 0;
                        while ($row = $result->fetch_assoc()): 
                            $total_ppn += $row['ppn'];
                            $total_grand += $row['grand_total'];
                    ?>
                        <tr>
                            <td class="text-center"><?php echo $no++; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['no_faktur']); ?></strong></td>
                            <td><?php echo date('d/m/Y', strtotime($row['tgl_faktur'])); ?></td>
                            <td><?php echo htmlspecialchars($row['nama_customer']); ?></td>
                            <td class="text-center">
                                <span class="badge bg-<?php echo ($row['metode_bayar'] === 'Tunai') ? 'success' : 'primary'; ?> badge-status">
                                    <?php echo htmlspecialchars($row['metode_bayar']); ?>
                                </span>
                            </td>
                            <td class="text-end">Rp <?php echo number_format($row['ppn'], 0, ',', '.'); ?></td>
                            <td class="text-end"><strong>Rp <?php echo number_format($row['grand_total'], 0, ',', '.'); ?></strong></td>
                        </tr>
                    <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="fa-solid fa-receipt d-block"></i>
                                    <p>Tidak ada transaksi pada periode ini.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row mt-4 mb-4">
    <div class="col-md-6 offset-md-6">
        <div class="content-card bg-light">
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <th style="width:50%;">Total Jumlah Transaksi</th>
                        <td class="text-end"><strong><?php echo isset($no) ? $no - 1 : 0; ?></strong></td>
                    </tr>
                    <tr>
                        <th>Total PPN (11%) Terkumpul</th>
                        <td class="text-end"><strong>Rp <?php echo isset($total_ppn) ? number_format($total_ppn, 0, ',', '.') : 0; ?></strong></td>
                    </tr>
                    <tr>
                        <th style="font-size:1.2rem; color:#1e3a5f;">Total Omzet Penjualan</th>
                        <td class="text-end" style="font-size:1.2rem; color:#1e3a5f;"><strong>Rp <?php echo isset($total_grand) ? number_format($total_grand, 0, ',', '.') : 0; ?></strong></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
