<?php

$root_path = dirname(__DIR__);
$base_url  = '/ujikom';


$current_page = basename(dirname($_SERVER['SCRIPT_FILENAME']));
$current_file = basename($_SERVER['SCRIPT_FILENAME'], '.php');

if ($current_page === 'ujikom') {
    $current_page = 'beranda';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SIFAKTUR - Sistem Faktur Penjualan Apotek">
    <title><?php echo isset($page_title) ? $page_title . ' — ' : ''; ?>SIFAKTUR</title>
    
    
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/bootstrap.min.css">
    
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/fontawesome/css/fontawesome.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/fontawesome/css/solid.css">
    
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/style.css">
</head>
<body>


<header class="top-header">
    <div class="brand">
        <i class="bi bi-capsule"></i>
        <span>SIFAKTUR</span>
    </div>
    <div class="header-right">
        <span class="d-none d-md-inline"><i class="bi bi-calendar3"></i> <?php echo date('d M Y'); ?></span>
        <span class="badge-user"><i class="fa-solid fa-circle-user"></i> Admin</span>
    </div>
</header>
