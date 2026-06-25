<?php
require_once dirname(__DIR__) . '/config/koneksi.php';

$result = $conn->query("SELECT * FROM customer ORDER BY id_customer ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Data Customer — APOTEK Zenin</title>
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
        <i class="fa-solid fa-print"></i> Cetak Daftar
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
            <div style="font-size:1.5rem; font-weight:bold; letter-spacing:3px; font-family:courier;">DATA CUSTOMER</div>
            <p style="margin-top:5px; font-size:0.85rem;">Dicetak pada: <?php echo date('d/m/Y H:i'); ?></p>
        </div>
    </div>

    <table class="faktur-table">
        <thead>
            <tr>
                <th style="text-align:center; width:5%;">No</th>
                <th style="text-align:center; width:25%;">Nama Customer</th>
                <th style="text-align:center; width:30%;">Perusahaan</th>
                <th style="text-align:center; width:40%;">Alamat</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td style="text-align:center;"><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($row['nama_customer']); ?></td>
                    <td><?php echo htmlspecialchars($row['perusahaan_cust'] ?? '-'); ?></td>
                    <td><?php echo htmlspecialchars($row['alamat'] ?? '-'); ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4" style="text-align:center;">Tidak ada data customer.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div style="margin-top:10px; font-size:0.9rem; font-weight:bold;">
        Total Customer: <?php echo $result ? $result->num_rows : 0; ?> data
    </div>
    
    <div style="display:flex; justify-content:flex-end; margin-top:40px;">
        <div style="text-align:center; width:200px;">
            <p style="margin-bottom:60px; font-size:0.9rem;">Mengetahui,</p>
            <div style="border-bottom:1px solid #000; width:100%;"></div>
        </div>
    </div>
</div>

</body>
</html>
