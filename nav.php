<?php
if ($user_id === 'dev@panjul.com') {
    echo '
            <li><a class="btn btn-primary mx-1" href="index.php?go=nota">Nota Penjualan</a></li>
            <li><a class="btn btn-primary mx-1" href="index.php?go=add_stock">Nota Pembelian</a></li>
            <li><a class="btn btn-primary mx-1" href="index.php?go=penjualan">DB Penjualan</a></li>
            <li><a class="btn btn-primary mx-1" href="index.php?go=pembelian">DB Pembelian</a></li>
            <li><a class="btn btn-primary mx-1" href="index.php?go=stock">DB Stock</a></li>
            <li><a class="btn btn-primary mx-1" href="index.php?go=invoice">Invoice</a></li>
            <li><a class="btn btn-primary mx-1" href="index.php?go=piutang">Piutang</a></li> 
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Admin Menu
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="btn btn-primary dropdown-item mx-1" href="index.php?go=dashboard">Dashboard</a>
                <a class="btn btn-primary dropdown-item mx-1" href="index.php?go=user">User</a>
                <a class="btn btn-primary dropdown-item mx-1" href="index.php?go=register">Registrasi</a>
                    <a class="btn btn-primary dropdown-item mx-1" href="index.php?go=update">Payment</a> 
                    <a class="btn btn-primary dropdown-item mx-1" href="index.php?go=payment">Bukti Pembayaran</a>
                </div>
            </div>';
} elseif ($user_id === 'guest') {
    echo '<link rel="stylesheet" href="css/guest.css">
            <li><a class="btn btn-primary mx-1" href="index.php?go=nota">Nota Penjualan</a></li>
            <li><a class="btn btn-primary mx-1" href="index.php?go=add_stock">Nota Pembelian</a></li>
            <li><a class="btn btn-primary mx-1" href="index.php?go=penjualan">DB Penjualan</a></li>
            <li><a class="btn btn-primary mx-1" href="index.php?go=pembelian">DB Pembelian</a></li>
            <li><a class="btn btn-primary mx-1" href="index.php?go=stock">DB Stock</a></li>
            <li><a class="btn btn-primary mx-1" href="index.php?go=invoice">Invoice</a></li>
            <li><a class="btn btn-primary mx-1" href="index.php?go=piutang">Piutang</a></li>
            <li><a class="btn btn-primary dropdown-item mx-1" href="index.php?go=dashboard">Dashboard</a></li>';
} else {
    echo '<li><a class="btn btn-primary mx-1" href="index.php?go=nota">Nota Penjualan</a></li>
            <li><a class="btn btn-primary mx-1" href="index.php?go=add_stock">Nota Pembelian</a></li>
            <li><a class="btn btn-primary mx-1" href="index.php?go=penjualan">DB Penjualan</a></li>
            <li><a class="btn btn-primary mx-1" href="index.php?go=pembelian">DB Pembelian</a></li>
            <li><a class="btn btn-primary mx-1" href="index.php?go=stock">DB Stock</a></li>
            <li><a class="btn btn-primary mx-1" href="index.php?go=invoice">Invoice</a></li>
            <li><a class="btn btn-primary mx-1" href="index.php?go=piutang">Piutang</a></li>
            <li><a class="btn btn-primary dropdown-item mx-1" href="index.php?go=dashboard">Dashboard</a></li>';
}
?>