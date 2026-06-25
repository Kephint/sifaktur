
<aside class="sidebar" id="sidebar">
    <ul class="sidebar-menu">
        
        <li class="menu-label">Menu Utama</li>
        <li class="menu-item">
            <a href="<?php echo $base_url; ?>/index.php" class="<?php echo ($current_page === 'beranda') ? 'active' : ''; ?>">
                <i class="fa-solid fa-gauge"></i>
                <span>Beranda</span>
            </a>
        </li>

        
        <li class="menu-label">Master Data</li>
        <li class="menu-item">
            <a href="<?php echo $base_url; ?>/perusahaan/index.php" class="<?php echo ($current_page === 'perusahaan') ? 'active' : ''; ?>">
                <i class="fa-solid fa-building"></i>
                <span>Data Perusahaan</span>
            </a>
        </li>

        
        <li class="menu-item">
            <a href="<?php echo $base_url; ?>/customer/index.php" class="<?php echo ($current_page === 'customer') ? 'active' : ''; ?>">
                <i class="fa-solid fa-users"></i>
                <span>Data Customer</span>
            </a>
        </li>

        
        <li class="menu-item">
            <a href="<?php echo $base_url; ?>/produk/index.php" class="<?php echo ($current_page === 'produk') ? 'active' : ''; ?>">
                <i class="fa-solid fa-box"></i>
                <span>Data Produk</span>
            </a>
        </li>

        
        <li class="menu-label">Transaksi</li>
        <li class="menu-item">
            <a href="<?php echo $base_url; ?>/penjualan/index.php" class="<?php echo ($current_page === 'penjualan') ? 'active' : ''; ?>">
                <i class="fa-solid fa-receipt"></i>
                <span>Penjualan</span>
            </a>
        </li>

        <li class="menu-label">Laporan</li>
        <li class="menu-item">
            <a href="<?php echo $base_url; ?>/laporan/produk.php" class="<?php echo ($current_page === 'laporan_produk') ? 'active' : ''; ?>">
                <i class="fa-solid fa-file-invoice"></i>
                <span>Laporan Produk</span>
            </a>
        </li>
        <li class="menu-item">
            <a href="<?php echo $base_url; ?>/laporan/penjualan.php" class="<?php echo ($current_page === 'laporan_penjualan') ? 'active' : ''; ?>">
                <i class="fa-solid fa-chart-line"></i>
                <span>Laporan Penjualan</span>
            </a>
        </li>
    </ul>
</aside>


<div class="main-wrapper">
    <div class="main-content animate-fade-in">
