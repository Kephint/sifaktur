
<aside class="sidebar" id="sidebar">
    <ul class="sidebar-menu">
        
        <li class="menu-label">Menu Utama</li>
        <li class="menu-item">
            <a href="<?php echo $base_url; ?>/index.php" class="<?php echo ($current_page === 'beranda') ? 'active' : ''; ?>">
                <i class="bi bi-speedometer2"></i>
                <span>Beranda</span>
            </a>
        </li>

        
        <li class="menu-label">Master Data</li>
        <li class="menu-item">
            <a href="<?php echo $base_url; ?>/perusahaan/index.php" class="<?php echo ($current_page === 'perusahaan') ? 'active' : ''; ?>">
                <i class="bi bi-building"></i>
                <span>Data Perusahaan</span>
            </a>
        </li>

        
        <li class="menu-item">
            <a href="<?php echo $base_url; ?>/customer/index.php" class="<?php echo ($current_page === 'customer') ? 'active' : ''; ?>">
                <i class="bi bi-people"></i>
                <span>Data Customer</span>
            </a>
        </li>

        
        <li class="menu-item">
            <a href="<?php echo $base_url; ?>/produk/index.php" class="<?php echo ($current_page === 'produk') ? 'active' : ''; ?>">
                <i class="bi bi-box-seam"></i>
                <span>Data Produk</span>
            </a>
        </li>

        
        <li class="menu-label">Transaksi</li>
        <li class="menu-item">
            <a href="<?php echo $base_url; ?>/penjualan/index.php" class="<?php echo ($current_page === 'penjualan') ? 'active' : ''; ?>">
                <i class="bi bi-receipt"></i>
                <span>Faktur Penjualan</span>
            </a>
        </li>

        <li class="menu-label">Laporan</li>
        <li class="menu-item">
            <a href="<?php echo $base_url; ?>/laporan/produk.php" class="<?php echo ($current_page === 'laporan_produk') ? 'active' : ''; ?>">
                <i class="bi bi-file-earmark-bar-graph"></i>
                <span>Laporan Produk</span>
            </a>
        </li>
        <li class="menu-item">
            <a href="<?php echo $base_url; ?>/laporan/penjualan.php" class="<?php echo ($current_page === 'laporan_penjualan') ? 'active' : ''; ?>">
                <i class="bi bi-graph-up"></i>
                <span>Laporan Penjualan</span>
            </a>
        </li>
    </ul>
</aside>


<div class="main-wrapper">
    <div class="main-content animate-fade-in">
