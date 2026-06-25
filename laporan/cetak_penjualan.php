<?php
require_once dirname(__DIR__) . '/config/koneksi.php';

$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-t');

$query = "SELECT f.*, c.nama_customer 
          FROM faktur f 
          JOIN customer c ON f.id_customer = c.id_customer 
          WHERE f.tgl_faktur BETWEEN ? AND ? 
          ORDER BY f.tgl_faktur ASC, f.id_faktur ASC";

$stmt = $conn->prepare($query);
$stmt->bind_param('ss', $start_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Penjualan — APOTEK Zenin</title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/fontawesome/css/fontawesome.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/fontawesome/css/solid.css">
    <style>
        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: #fff;
            color: #000;
            padding: 1rem 2rem;
        }
        .faktur-print {
            max-width: 1000px;
            margin: 0 auto;
            background: #fff;
            padding: 20px 40px;
        }
        .faktur-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .faktur-table th, .faktur-table td {
            border: 0.1em solid #000;
            padding: 4px 8px;
        }
        .faktur-table th {
            background-color: #fff;
        }
        .faktur-table tbody td {
            border-top: none;
            border-bottom: none;
        }
        .faktur-table tbody tr:last-child td {
            border-bottom: 0.1em solid #000;
        }
        table td, table th {
            font-size: 0.9rem;
        }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; margin: 0; }
            .faktur-print { padding: 0; max-width: 100%; min-height: 100vh; }
        }
    </style>
</head>
<body>

<div class="no-print mb-3" style="max-width:1000px; margin:0 auto; padding:0 40px;">
    <button onclick="window.print()" class="btn btn-primary btn-sm">
        <i class="fa-solid fa-print"></i> Cetak Laporan
    </button>
    <button onclick="window.close()" class="btn btn-secondary btn-sm">
        <i class="fa-solid fa-xmark"></i> Tutup
    </button>
</div>

<div class="faktur-print">
    <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:4px double #000; padding-bottom:10px; margin-bottom:20px;">
        <div>
            <div style="font-size:1.5rem; font-weight:bold; letter-spacing:1px;">APOTEK Zenin</div>
            <p style="margin:0; font-size:0.85rem; font-weight: bold; line-height:1.4;">
                No. Surat Izin Apotek: 123/SIA/DPMPTSP/2026<br>
                Jl. Raya Pembangunan No. 123, Tangerang<br>
                Telp: 021-5551234 | Email: cs@apotekzenin.com<br>
                Web: www.apotekzenin.com
            </p>
        </div>
        <div style="text-align:right;">
            <div style="font-size:1.5rem; font-weight:bold; letter-spacing:3px; font-family:courier;">LAPORAN PENJUALAN</div>
            <p style="margin-top:5px; font-size:0.85rem;">Periode: <?php echo date('d/m/Y', strtotime($start_date)) . ' s.d. ' . date('d/m/Y', strtotime($end_date)); ?></p>
        </div>
    </div>

    <table class="faktur-table">
        <thead>
            <tr>
                <th style="text-align:center; width:5%;">No</th>
                <th style="text-align:center; width:15%;">Tanggal</th>
                <th style="text-align:center; width:15%;">No Faktur</th>
                <th style="text-align:center; width:25%;">Customer</th>
                <th style="text-align:center; width:15%;">Pembayaran</th>
                <th style="text-align:center; width:10%;">PPN</th>
                <th style="text-align:center; width:15%;">Grand Total</th>
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
                    <td style="text-align:center;"><?php echo $no++; ?></td>
                    <td style="text-align:center;"><?php echo date('d/m/Y', strtotime($row['tgl_faktur'])); ?></td>
                    <td style="text-align:center;"><?php echo htmlspecialchars($row['no_faktur']); ?></td>
                    <td><?php echo htmlspecialchars($row['nama_customer']); ?></td>
                    <td style="text-align:center;"><?php echo htmlspecialchars($row['metode_bayar']); ?></td>
                    <td style="text-align:right;">Rp <?php echo number_format($row['ppn'], 0, ',', '.'); ?></td>
                    <td style="text-align:right;">Rp <?php echo number_format($row['grand_total'], 0, ',', '.'); ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="7" style="text-align:center;">Tidak ada transaksi penjualan pada periode ini.</td></tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" style="text-align:right; font-weight:bold;">TOTAL KESELURUHAN :</td>
                <td style="text-align:right; font-weight:bold;">Rp <?php echo isset($total_ppn) ? number_format($total_ppn, 0, ',', '.') : 0; ?></td>
                <td style="text-align:right; font-weight:bold;">Rp <?php echo isset($total_grand) ? number_format($total_grand, 0, ',', '.') : 0; ?></td>
            </tr>
        </tfoot>
    </table>
    
    <div style="display:flex; justify-content:space-between; margin-top:40px;">
        <div style="font-size:0.9rem;">
            Total Transaksi: <strong><?php echo $result ? $result->num_rows : 0; ?> Faktur</strong>
        </div>
        <div style="text-align:center; width:200px;">
            <p style="margin-bottom:60px; font-size:0.9rem;">Mengetahui,</p>
            <div style="border-bottom:1px solid #000; width:100%;"></div>
        </div>
    </div>
</div>

</body>
</html>
