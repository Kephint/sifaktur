<?php
require_once dirname(__DIR__) . '/config/koneksi.php';

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM faktur WHERE id_customer = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $used = $stmt->get_result()->fetch_assoc()['total'];
    $stmt->close();

    if ($used > 0) {
        header('Location: index.php?msg=hapus_gagal');
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM customer WHERE id_customer = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();

    header('Location: index.php?msg=hapus_ok');
} else {
    header('Location: index.php');
}
exit;
