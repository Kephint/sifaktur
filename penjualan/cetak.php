<?php
require_once dirname(__DIR__) . '/config/koneksi.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { echo 'ID tidak valid'; exit; }

$stmt = $conn->prepare("
    SELECT f.*, c.nama_customer, c.perusahaan_cust, c.alamat AS alamat_customer,
           p.nama_perusahaan, p.alamat AS alamat_perusahaan, p.telp, p.fax
    FROM faktur f
    JOIN customer c ON f.id_customer = c.id_customer
    JOIN perusahaan p ON f.id_perusahaan = p.id_perusahaan
    WHERE f.id_faktur = ?
");
$stmt->bind_param('i', $id);
$stmt->execute();
$faktur = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$faktur) { echo 'Faktur tidak ditemukan'; exit; }

$stmt_d = $conn->prepare("
    SELECT df.*, pr.nama_produk, pr.satuan
    FROM detail_faktur df
    JOIN produk pr ON df.id_produk = pr.id_produk
    WHERE df.id_faktur = ?
    ORDER BY df.id_detail ASC
");
$stmt_d->bind_param('i', $id);
$stmt_d->execute();
$details = $stmt_d->get_result();

$subtotal_barang = 0;
$total_qty = 0;
$detail_rows = [];
while ($d = $details->fetch_assoc()) {
    $d['subtotal'] = $d['qty'] * $d['price'];
    $subtotal_barang += $d['subtotal'];
    $total_qty += $d['qty'];
    $detail_rows[] = $d;
}
$stmt_d->close();

$hitung_ppn = $subtotal_barang * 0.11;
$hitung_dp = $faktur['dp'] ?? 0;
$hitung_grand_total = $subtotal_barang + $hitung_ppn - $hitung_dp;

function formatTanggalIndo($datetime) {
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    $timestamp = strtotime($datetime);
    $tgl = date('d', $timestamp);
    $bln = $bulan[(int)date('m', $timestamp)];
    $thn = date('Y', $timestamp);
    $waktu = date('H:i:s', $timestamp);
    return "$tgl $bln $thn $waktu";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Faktur <?php echo htmlspecialchars($faktur['no_faktur']); ?></title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/fontawesome/css/fontawesome.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/fontawesome/css/solid.css">
    <style>
        body { 
            background: #fff; 
            font-family: Arial, sans-serif;
            color: #000;
        }
        .faktur-print {
            max-width: 297mm;
            margin: 0 auto;
            padding: 20px;
            min-height: 210mm;
            display: flex;
            flex-direction: column;
        }
        .faktur-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .faktur-table th, .faktur-table td {
            border: 0.1em solid #000;
            padding: 2px 5px;
        }
        .faktur-table tbody td {
            border-top: none;
            border-bottom: none;
        }
        .faktur-table tbody tr:last-child td {
            border-bottom: 0.1em solid #000;
        }
        @media print {
            @page { size: landscape; }
            .no-print { display: none !important; }
            body { padding: 0; margin: 0; }
            .faktur-print { padding: 0; max-width: 100%; min-height: 100vh; }
        }
        table td, table th {
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

<div class="no-print" style="padding:1rem; background:#f0f4f8; text-align:center; margin-bottom:20px;">
    <button onclick="window.print()" class="btn btn-primary btn-sm">
        <i class="fa-solid fa-print"></i> Cetak Faktur
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
            <div style="font-size:2rem; font-weight:bold; letter-spacing:10px; font-family:courier;">FAKTUR</div>
        </div>
    </div>

    <div style="display:flex; justify-content:space-between; margin-bottom:20px; font-size:0.9rem;">
        <div style="width:50%;">
            <table style="width:100%; text-align:left; border:none;">
                <tr><td style="padding:0 10px 0 0; font-weight:bold; width:130px;">Nama Pelanggan</td><td style="padding:0;">: <?php echo htmlspecialchars($faktur['nama_customer']); ?></td></tr>
                <tr><td style="padding:0 10px 0 0; font-weight:bold;">No. Telp</td><td style="padding:0;">: -</td></tr>
                <tr><td style="padding:0 10px 0 0; font-weight:bold; vertical-align:top;">Alamat</td><td style="padding:0; vertical-align:top;">: <?php echo htmlspecialchars($faktur['alamat_customer'] ?? '-'); ?></td></tr>
            </table>
        </div>
        <div style="width:45%; text-align:right;">
            <table style="width:100%; text-align:left; float:right; border:none; margin-left:auto; width:auto;">
                <tr><td style="padding:0 10px 0 0; font-weight:bold;">Kasir</td><td style="padding:0;">: <?php echo htmlspecialchars($faktur['user'] ?? 'Admin'); ?></td></tr>
                <tr><td style="padding:0 10px 0 0; font-weight:bold;">Tanggal</td><td style="padding:0;">: <?php echo formatTanggalIndo($faktur['created_at'] ?? $faktur['tgl_faktur'] . ' 00:00:00'); ?></td></tr>
                <tr><td style="padding:0 10px 0 0; font-weight:bold;">No Faktur</td><td style="padding:0;">: <?php echo htmlspecialchars($faktur['no_faktur']); ?></td></tr>
                <tr><td style="padding:0 10px 0 0; font-weight:bold;">Pembayaran</td><td style="padding:0;">: <?php echo strtoupper(htmlspecialchars($faktur['metode_bayar'])); ?></td></tr>
            </table>
            <div style="clear:both;"></div>
        </div>
    </div>

    <table class="faktur-table">
        <thead>
            <tr>
                <th style="text-align:center; width:5%;">No.</th>
                <th style="text-align:center; width:34%;">Nama barang</th>
                <th style="text-align:center; width:5%;">Qty</th>
                <th style="text-align:center; width:10%;">Satuan</th>
                <th style="text-align:center; width:18%;">Batch & ED</th>
                <th style="text-align:center; width:12%;">Harga</th>
                <th style="text-align:center; width:5%;">Disc</th>
                <th style="text-align:center; width:17%;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($detail_rows as $det): ?>
            <tr>
                <td style="text-align:center;"><?php echo $no++; ?></td>
                <td><?php echo htmlspecialchars($det['nama_produk']); ?></td>
                <td style="text-align:center;"><?php echo $det['qty']; ?></td>
                <td style="text-align:center;"><?php echo htmlspecialchars($det['satuan'] ?? '-'); ?></td>
                <td style="text-align:center; font-size:0.8rem; white-space:nowrap;">
                    <?php echo htmlspecialchars($det['batch'] ?? '-'); ?> / <?php echo $det['expired_date'] ? date('d/m/y', strtotime($det['expired_date'])) : '-'; ?>
                </td>
                <td style="text-align:right;">Rp <?php echo number_format($det['price'], 0, ',', '.'); ?></td>
                <td style="text-align:right;">0</td>
                <td style="text-align:right;">Rp <?php echo number_format($det['subtotal'], 0, ',', '.'); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" style="border:none;"></td>
                <td style="text-align:center;"><?php echo $total_qty; ?></td>
                <td colspan="2" style="border:none;"></td>
                <td colspan="2" style="text-align:right;">Total :</td>
                <td style="text-align:right;">Rp <?php echo number_format($subtotal_barang, 0, ',', '.'); ?></td>
            </tr>
            <tr>
                <td rowspan="4" style="vertical-align:top; border:none; padding:10px 5px; font-size:0.85rem;">
                    <strong>Catatan:</strong>
                </td>
                <td colspan="4" rowspan="4" style="vertical-align:top; border:none; padding:10px 0; text-align:center;">
                    <div style="font-size:0.85rem; line-height:1.4; display:inline-block; text-align:left;">
                        Terimakasih telah berkunjung. semoga sehat selalu.<br>
                        Maaf barang yang sudah dibeli,<br>
                        tidak dapat ditukar atau dikembalikan.
                    </div>
                </td>
                <td colspan="2" style="text-align:right;">Diskon :</td>
                <td style="text-align:right;">Rp 0</td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:right;">Pajak (11%) :</td>
                <td style="text-align:right;">Rp <?php echo number_format($hitung_ppn, 0, ',', '.'); ?></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:right;">DP / Uang Muka :</td>
                <td style="text-align:right;">Rp <?php echo number_format($hitung_dp, 0, ',', '.'); ?></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:right; font-weight:bold;">Grand Total :</td>
                <td style="text-align:right; font-weight:bold;">Rp <?php echo number_format($hitung_grand_total, 0, ',', '.'); ?></td>
            </tr>
        </tfoot>
    </table>

    <div style="display:flex; justify-content:flex-start; margin-top:20px; padding-left:10%;">
        <div style="display:flex; gap:80px; text-align:center;">
            <div style="width:200px;">
                <p style="margin-bottom:60px; font-size:0.9rem;">Penerima / Pembeli</p>
                <div style="border-bottom:1px solid #000; width:100%;"></div>
            </div>
            <div style="width:200px;">
                <p style="margin-bottom:60px; font-size:0.9rem;">Apotek Zenin</p>
                <div style="border-bottom:1px solid #000; width:100%;"></div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
