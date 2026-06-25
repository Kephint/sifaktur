<?php




require_once dirname(__DIR__) . '/config/koneksi.php';

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    require_once dirname(__DIR__) . '/classes/Perusahaan.php';
    $perusahaanObj = new Perusahaan();

    
    if ($perusahaanObj->isUsedInFaktur($id)) {
        header('Location: index.php?msg=hapus_gagal');
        exit;
    }

    if ($perusahaanObj->delete($id)) {
        header('Location: index.php?msg=hapus_ok');
    } else {
        
        header('Location: index.php?msg=hapus_gagal');
    }
} else {
    header('Location: index.php');
}
exit;
