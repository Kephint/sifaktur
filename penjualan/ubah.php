<?php
$page_title = 'Ubah Faktur';
require_once dirname(__DIR__) . '/config/koneksi.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: index.php'); exit; }


$stmt = $conn->prepare("SELECT * FROM faktur WHERE id_faktur = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$faktur = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$faktur) { header('Location: index.php'); exit; }


$stmt_d = $conn->prepare("SELECT df.*, pr.nama_produk, pr.stock FROM detail_faktur df JOIN produk pr ON df.id_produk = pr.id_produk WHERE df.id_faktur = ?");
$stmt_d->bind_param('i', $id);
$stmt_d->execute();
$details_result = $stmt_d->get_result();
$existing_details = [];
while ($d = $details_result->fetch_assoc()) {
    $existing_details[] = $d;
}
$stmt_d->close();


$customers   = $conn->query("SELECT * FROM customer ORDER BY nama_customer");
$perusahaans = $conn->query("SELECT * FROM perusahaan ORDER BY nama_perusahaan");
$produks     = $conn->query("SELECT * FROM produk ORDER BY nama_produk");

$produk_list = [];
while ($p = $produks->fetch_assoc()) {
    $produk_list[] = $p;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $no_faktur     = trim($_POST['no_faktur'] ?? '');
    $tgl_faktur    = $_POST['tgl_faktur'] ?? '';
    $due_date      = $_POST['due_date'] ?? '';
    $metode_bayar  = $_POST['metode_bayar'] ?? '';
    $user_faktur   = trim($_POST['user'] ?? '');
    $id_customer   = intval($_POST['id_customer'] ?? 0);
    $id_perusahaan = intval($_POST['id_perusahaan'] ?? 0);
    $dp            = floatval($_POST['dp'] ?? 0);
    $ppn_val       = floatval($_POST['ppn_hidden'] ?? 0);
    $grand_total   = floatval($_POST['grand_total_hidden'] ?? 0);
    $details_post  = $_POST['detail'] ?? [];

    $errors = [];
    if (empty($no_faktur)) $errors[] = 'No. Faktur wajib diisi';
    if (empty($tgl_faktur)) $errors[] = 'Tanggal faktur wajib diisi';
    if ($id_customer <= 0) $errors[] = 'Customer wajib dipilih';
    if ($id_perusahaan <= 0) $errors[] = 'Perusahaan wajib dipilih';
    if (empty($details_post)) $errors[] = 'Minimal 1 item produk harus ditambahkan';

    
    if (!empty($no_faktur)) {
        $cek = $conn->prepare("SELECT COUNT(*) AS total FROM faktur WHERE no_faktur = ? AND id_faktur != ?");
        $cek->bind_param('si', $no_faktur, $id);
        $cek->execute();
        if ($cek->get_result()->fetch_assoc()['total'] > 0) {
            $errors[] = 'No. Faktur sudah digunakan!';
        }
        $cek->close();
    }

    if (empty($errors)) {
        $due_date = !empty($due_date) ? $due_date : null;

        $conn->begin_transaction();
        try {
            
            $stmt = $conn->prepare("UPDATE faktur SET no_faktur=?, tgl_faktur=?, due_date=?, metode_bayar=?, ppn=?, dp=?, grand_total=?, user=?, id_customer=?, id_perusahaan=? WHERE id_faktur=?");
            $stmt->bind_param('ssssdddsiis', $no_faktur, $tgl_faktur, $due_date, $metode_bayar, $ppn_val, $dp, $grand_total, $user_faktur, $id_customer, $id_perusahaan, $id);
            $stmt->execute();
            $stmt->close();

            
            $conn->query("DELETE FROM detail_faktur WHERE id_faktur = $id");

            
            $stmt_d = $conn->prepare("INSERT INTO detail_faktur (id_faktur, no_faktur, id_produk, qty, batch, expired_date, price) VALUES (?, ?, ?, ?, ?, ?, ?)");
            foreach ($details_post as $det) {
                $det_id_produk   = intval($det['id_produk'] ?? 0);
                $det_qty         = intval($det['qty'] ?? 1);
                $det_batch       = trim($det['batch'] ?? '');
                $det_expired     = trim($det['expired_date'] ?? '');
                $det_price       = floatval($det['price_val'] ?? 0);

                if ($det_id_produk > 0 && $det_qty > 0) {
                    $det_batch   = !empty($det_batch) ? $det_batch : null;
                    $det_expired = !empty($det_expired) ? $det_expired : null;
                    $stmt_d->bind_param('ississd', $id, $no_faktur, $det_id_produk, $det_qty, $det_batch, $det_expired, $det_price);
                    $stmt_d->execute();
                }
            }
            $stmt_d->close();

            $conn->commit();
            header('Location: index.php?msg=ubah_ok');
            exit;
        } catch (Exception $e) {
            $conn->rollback();
            $errors[] = 'Gagal menyimpan data: ' . $e->getMessage();
        }
    }
}

include dirname(__DIR__) . '/includes/header.php';
include dirname(__DIR__) . '/includes/sidebar.php';
?>


<div class="page-header">
    <h2><i class="bi bi-pencil-square"></i> Ubah Faktur Penjualan</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>/index.php">Beranda</a></li>
            <li class="breadcrumb-item"><a href="index.php">Faktur Penjualan</a></li>
            <li class="breadcrumb-item active">Ubah</li>
        </ol>
    </nav>
</div>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger">
    <i class="bi bi-exclamation-triangle"></i>
    <ul class="mb-0 mt-1">
        <?php foreach ($errors as $err): ?>
            <li><?php echo htmlspecialchars($err); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<form method="POST" action="" id="form-faktur">
    
    <div class="content-card mb-3">
        <div class="card-header-custom">
            <i class="bi bi-file-earmark-text"></i> Data Faktur
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="no_faktur" class="form-label">No. Faktur (Otomatis)</label>
                    <input type="text" class="form-control bg-light" id="no_faktur" name="no_faktur" 
                           value="<?php echo htmlspecialchars($faktur['no_faktur']); ?>" readonly>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="tgl_faktur" class="form-label">Tanggal Faktur <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="tgl_faktur" name="tgl_faktur" 
                           value="<?php echo $faktur['tgl_faktur']; ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="due_date" class="form-label">Jatuh Tempo</label>
                    <input type="date" class="form-control" id="due_date" name="due_date" 
                           value="<?php echo $faktur['due_date'] ?? ''; ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="id_customer" class="form-label">Customer <span class="text-danger">*</span></label>
                    <select class="form-select" id="id_customer" name="id_customer" required>
                        <option value="">-- Pilih Customer --</option>
                        <?php 
                        $customers->data_seek(0);
                        while ($c = $customers->fetch_assoc()): ?>
                            <option value="<?php echo $c['id_customer']; ?>" 
                                <?php echo ($faktur['id_customer'] == $c['id_customer']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($c['nama_customer']); ?>
                                <?php echo $c['perusahaan_cust'] ? ' (' . htmlspecialchars($c['perusahaan_cust']) . ')' : ''; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="id_perusahaan" class="form-label">Perusahaan <span class="text-danger">*</span></label>
                    <select class="form-select" id="id_perusahaan" name="id_perusahaan" required>
                        <option value="">-- Pilih Perusahaan --</option>
                        <?php 
                        $perusahaans->data_seek(0);
                        while ($pr = $perusahaans->fetch_assoc()): ?>
                            <option value="<?php echo $pr['id_perusahaan']; ?>" 
                                <?php echo ($faktur['id_perusahaan'] == $pr['id_perusahaan']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($pr['nama_perusahaan']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="metode_bayar" class="form-label">Metode Bayar</label>
                    <select class="form-select" id="metode_bayar" name="metode_bayar">
                        <option value="Tunai" <?php echo ($faktur['metode_bayar'] === 'Tunai') ? 'selected' : ''; ?>>Tunai</option>
                        <option value="Transfer Bank" <?php echo ($faktur['metode_bayar'] === 'Transfer Bank') ? 'selected' : ''; ?>>Transfer Bank</option>
                        <option value="Kredit" <?php echo ($faktur['metode_bayar'] === 'Kredit') ? 'selected' : ''; ?>>Kredit</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="user" class="form-label">User/Kasir</label>
                    <input type="text" class="form-control" id="user" name="user" 
                           value="<?php echo htmlspecialchars($faktur['user'] ?? ''); ?>">
                </div>
            </div>
        </div>
    </div>

    
    <div class="content-card mb-3">
        <div class="card-header-custom d-flex justify-content-between align-items-center">
            <span><i class="bi bi-cart3"></i> Detail Item</span>
            <button type="button" class="btn btn-sm btn-light" onclick="addDetailRow(produkData)">
                <i class="bi bi-plus-circle"></i> Tambah Item
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width:50px;" class="text-center">No</th>
                            <th>Produk</th>
                            <th style="width:120px;" class="text-center">Qty</th>
                            <th style="width:120px;">Batch</th>
                            <th style="width:130px;">Expired</th>
                            <th style="width:150px;">Harga Satuan</th>
                            <th style="width:150px;">Subtotal</th>
                            <th style="width:60px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="detail-tbody">
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
    <div class="content-card mb-3">
        <div class="card-header-custom">
            <i class="bi bi-calculator"></i> Ringkasan
        </div>
        <div class="card-body">
            <div class="row justify-content-end">
                <div class="col-md-5">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td class="text-end" style="width:50%;">Subtotal Barang:</td>
                            <td class="text-end"><strong id="subtotal-barang">Rp 0</strong></td>
                        </tr>
                        <tr>
                            <td class="text-end">PPN (11%):</td>
                            <td class="text-end"><strong id="ppn-display">Rp 0</strong></td>
                        </tr>
                        <tr>
                            <td class="text-end">DP / Uang Muka:</td>
                            <td>
                                <input type="number" class="form-control form-control-sm text-end" id="dp-input" name="dp" 
                                       value="<?php echo $faktur['dp']; ?>" min="0" step="0.01"
                                       onchange="calculateGrandTotal()" onkeyup="calculateGrandTotal()">
                            </td>
                        </tr>
                        <tr class="border-top">
                            <td class="text-end"><h5 class="mb-0">Grand Total:</h5></td>
                            <td class="text-end"><h5 class="mb-0 text-primary" id="grand-total">Rp 0</h5></td>
                        </tr>
                    </table>
                    <input type="hidden" name="ppn_hidden" id="ppn-hidden" value="0">
                    <input type="hidden" name="grand_total_hidden" id="grand-total-hidden" value="0">
                </div>
            </div>
        </div>
    </div>

    
    <div class="d-flex gap-2 mb-4">
        <button type="submit" class="btn btn-success-custom">
            <i class="fa-solid fa-check"></i> Simpan Perubahan
        </button>
        <a href="index.php" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>
</form>

<script>
var produkData = <?php echo json_encode($produk_list); ?>;

// Load existing detail rows
document.addEventListener('DOMContentLoaded', function() {
    var existingDetails = <?php echo json_encode($existing_details); ?>;

    if (existingDetails.length > 0) {
        existingDetails.forEach(function(det) {
            addDetailRow(produkData);
            // Set the values for the last added row
            var currentRow = document.getElementById('row-' + rowIndex);
            if (currentRow) {
                var select = currentRow.querySelector('.produk-select');
                if (select) {
                    select.value = det.id_produk;
                    updatePrice(select, rowIndex);
                }
                var qtyInput = currentRow.querySelector('.qty-input');
                if (qtyInput) {
                    qtyInput.value = det.qty;
                }
                var batchInput = currentRow.querySelector('input[name="detail[' + rowIndex + '][batch]"]');
                if (batchInput && det.batch) {
                    batchInput.value = det.batch;
                }
                var expiredInput = currentRow.querySelector('input[name="detail[' + rowIndex + '][expired_date]"]');
                if (expiredInput && det.expired_date) {
                    expiredInput.value = det.expired_date;
                }
                // Set price from saved data (not from current product price)
                var priceInput = document.getElementById('price-' + rowIndex);
                var priceVal = document.getElementById('price-val-' + rowIndex);
                if (priceInput) priceInput.value = 'Rp ' + formatRupiah(parseFloat(det.price).toFixed(0));
                if (priceVal) priceVal.value = det.price;

                calculateRowTotal(rowIndex);
            }
        });
    } else {
        addDetailRow(produkData);
    }
});
</script>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
