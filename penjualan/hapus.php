<?php
require_once dirname(__DIR__) . '/config/koneksi.php';

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    
    $stmt = $conn->prepare("DELETE FROM faktur WHERE id_faktur = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();

    header('Location: index.php?msg=hapus_ok');
} else {
    header('Location: index.php');
}
exit;
