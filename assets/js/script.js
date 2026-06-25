function confirmDelete(url, namaItem) {
    Swal.fire({
        title: '<span style="font-size: 1.25rem;">Konfirmasi Hapus</span>',
        html: `<p style="font-size: 0.95rem; margin-bottom: 0;">Anda yakin ingin menghapus <b>"${namaItem}"</b>?</p>`,
        icon: 'warning',
        width: '380px',
        padding: '1rem',
        showCancelButton: true,
        buttonsStyling: false,
        customClass: {
            popup: 'rounded-4 shadow-sm border',
            confirmButton: 'btn btn-danger btn-sm px-3 mx-1',
            cancelButton: 'btn btn-light btn-sm px-3 mx-1 border'
        },
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
    return false;
}


function formatRupiah(angka) {
    var numberString = angka.toString().replace(/[^,\d]/g, '');
    var split = numberString.split(',');
    var sisa = split[0].length % 3;
    var rupiah = split[0].substr(0, sisa);
    var ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        var separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
    return rupiah;
}

function parseRupiah(rupiahString) {
    return parseFloat(rupiahString.replace(/\./g, '').replace(/,/g, '.')) || 0;
}

var rowIndex = 0;

function addDetailRow(produkData) {
    rowIndex++;
    var tbody = document.getElementById('detail-tbody');
    if (!tbody) return;

    var options = '<option value="">-- Pilih Produk --</option>';
    if (produkData) {
        produkData.forEach(function (p) {
            options += '<option value="' + p.id_produk + '" data-price="' + p.price + '">' + p.nama_produk + ' (Stok: ' + p.stock + ')</option>';
        });
    }

    var row = document.createElement('tr');
    row.id = 'row-' + rowIndex;
    row.innerHTML = `
        <td class="text-center">${rowIndex}</td>
        <td>
            <select class="form-select form-select-sm produk-select" name="detail[${rowIndex}][id_produk]" required onchange="updatePrice(this, ${rowIndex})">
                ${options}
            </select>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm text-center qty-input" name="detail[${rowIndex}][qty]" value="1" min="1" required onchange="calculateRowTotal(${rowIndex})" onkeyup="calculateRowTotal(${rowIndex})">
        </td>
        <td>
            <input type="text" class="form-control form-control-sm" name="detail[${rowIndex}][batch]" placeholder="Batch">
        </td>
        <td>
            <input type="date" class="form-control form-control-sm" name="detail[${rowIndex}][expired_date]">
        </td>
        <td>
            <input type="text" class="form-control form-control-sm text-end price-input" name="detail[${rowIndex}][price]" id="price-${rowIndex}" readonly>
            <input type="hidden" name="detail[${rowIndex}][price_val]" id="price-val-${rowIndex}" value="0">
        </td>
        <td>
            <span class="subtotal-display" id="subtotal-${rowIndex}">Rp 0</span>
            <input type="hidden" id="subtotal-val-${rowIndex}" value="0">
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-danger-custom btn-sm" onclick="removeDetailRow(${rowIndex})">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;

    tbody.appendChild(row);
    renumberRows();
}

function removeDetailRow(idx) {
    var row = document.getElementById('row-' + idx);
    if (row) {
        row.remove();
        renumberRows();
        calculateGrandTotal();
    }
}

function renumberRows() {
    var tbody = document.getElementById('detail-tbody');
    if (!tbody) return;
    var rows = tbody.querySelectorAll('tr');
    rows.forEach(function (row, i) {
        row.querySelector('td:first-child').textContent = i + 1;
    });
}

function updatePrice(selectEl, idx) {
    var selected = selectEl.options[selectEl.selectedIndex];
    var price = selected.getAttribute('data-price') || 0;
    price = parseFloat(price);

    var priceInput = document.getElementById('price-' + idx);
    var priceVal = document.getElementById('price-val-' + idx);

    if (priceInput) priceInput.value = 'Rp ' + formatRupiah(price.toFixed(0));
    if (priceVal) priceVal.value = price;

    calculateRowTotal(idx);
}

function calculateRowTotal(idx) {
    var priceVal = document.getElementById('price-val-' + idx);
    var qtyInput = document.querySelector('#row-' + idx + ' .qty-input');
    var subtotalDisplay = document.getElementById('subtotal-' + idx);
    var subtotalVal = document.getElementById('subtotal-val-' + idx);

    if (!priceVal || !qtyInput) return;

    var price = parseFloat(priceVal.value) || 0;
    var qty = parseInt(qtyInput.value) || 0;
    var subtotal = price * qty;

    if (subtotalDisplay) subtotalDisplay.textContent = 'Rp ' + formatRupiah(subtotal.toFixed(0));
    if (subtotalVal) subtotalVal.value = subtotal;

    calculateGrandTotal();
}

function calculateGrandTotal() {
    var subtotals = document.querySelectorAll('[id^="subtotal-val-"]');
    var totalBarang = 0;

    subtotals.forEach(function (el) {
        totalBarang += parseFloat(el.value) || 0;
    });

    var ppnRate = 0.11;
    var ppn = totalBarang * ppnRate;
    var dp = parseFloat(document.getElementById('dp-input') ? document.getElementById('dp-input').value : 0) || 0;
    var grandTotal = totalBarang + ppn - dp;

    var subtotalEl = document.getElementById('subtotal-barang');
    var ppnEl = document.getElementById('ppn-display');
    var dpEl = document.getElementById('dp-display');
    var grandEl = document.getElementById('grand-total');
    var ppnHidden = document.getElementById('ppn-hidden');
    var grandHidden = document.getElementById('grand-total-hidden');

    if (subtotalEl) subtotalEl.textContent = 'Rp ' + formatRupiah(totalBarang.toFixed(0));
    if (ppnEl) ppnEl.textContent = 'Rp ' + formatRupiah(ppn.toFixed(0));
    if (dpEl) dpEl.textContent = 'Rp ' + formatRupiah(dp.toFixed(0));
    if (grandEl) grandEl.textContent = 'Rp ' + formatRupiah(grandTotal.toFixed(0));
    if (ppnHidden) ppnHidden.value = ppn.toFixed(2);
    if (grandHidden) grandHidden.value = grandTotal.toFixed(2);
}

document.addEventListener('DOMContentLoaded', function () {
    var alerts = document.querySelectorAll('.alert-auto-close');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(function () {
                alert.remove();
            }, 500);
        }, 4000);
    });
});
