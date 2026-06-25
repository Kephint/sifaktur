<?php
$page_title = 'Beranda';
require_once __DIR__ . '/config/koneksi.php';
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';


$total_perusahaan = $conn->query("SELECT COUNT(*) AS total FROM perusahaan")->fetch_assoc()['total'];
$total_customer   = $conn->query("SELECT COUNT(*) AS total FROM customer")->fetch_assoc()['total'];
$total_produk     = $conn->query("SELECT COUNT(*) AS total FROM produk")->fetch_assoc()['total'];
$total_faktur     = $conn->query("SELECT COUNT(*) AS total FROM faktur")->fetch_assoc()['total'];


$faktur_terbaru = $conn->query("
    SELECT f.no_faktur, f.tgl_faktur, f.grand_total, f.metode_bayar, c.nama_customer, p.nama_perusahaan
    FROM faktur f
    JOIN customer c ON f.id_customer = c.id_customer
    JOIN perusahaan p ON f.id_perusahaan = p.id_perusahaan
    ORDER BY f.id_faktur DESC
    LIMIT 5
");
?>


<div class="page-header">
    <h2></i> Dashboard</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Beranda</li>
        </ol>
    </nav>
</div>


<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card card-primary animate-fade-in">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary">
                    <i class="bi bi-building"></i>
                </div>
                <div>
                    <div class="stat-value"><?php echo $total_perusahaan; ?></div>
                    <div class="stat-label">Perusahaan</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card card-success animate-fade-in">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-success">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <div class="stat-value"><?php echo $total_customer; ?></div>
                    <div class="stat-label">Customer</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card card-warning animate-fade-in">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-warning">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div>
                    <div class="stat-value"><?php echo $total_produk; ?></div>
                    <div class="stat-label">Produk</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card card-info animate-fade-in">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-info">
                    <i class="bi bi-receipt"></i>
                </div>
                <div>
                    <div class="stat-value"><?php echo $total_faktur; ?></div>
                    <div class="stat-label">Faktur</div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="content-card">
    <div class="card-header-custom">
        <i class="bi bi-clock-history"></i> Faktur Terbaru
    </div>
    <div class="card-body p-0">
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>No. Faktur</th>
                        <th>Tanggal</th>
                        <th>Customer</th>
                        <th>Perusahaan</th>
                        <th>Metode Bayar</th>
                        <th class="text-end">Grand Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($faktur_terbaru && $faktur_terbaru->num_rows > 0): ?>
                        <?php while ($row = $faktur_terbaru->fetch_assoc()): ?>
                        <tr>
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
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="bi bi-inbox d-block"></i>
                                    <p>Belum ada data faktur</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
