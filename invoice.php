<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['lunasi'])) {
    $invoices_to_lunasi = $_POST["invoices_to_lunasi"];

    foreach ($invoices_to_lunasi as $nomorNota) {
        $update_status_sql = "UPDATE penjualan SET status_pembayaran = 'Ter-Lunasi' WHERE nomor_nota = $nomorNota";
        if ($conn->query($update_status_sql)) {
            header('Location: index.php?go=invoice');
        }
    }
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mata Biru</title>
    <link rel="shortcut icon" href="icon.png">
</head>

<body>
    <h1><strong>Invoice</strong></h1>

    <form method="post" action="" class="container">
    <div class="form-group">    
            <input type="text" class="form-control" name="keyword" placeholder="Masukkan Nomor Nota">
        </div>
        <button type="submit" class="btn btn-primary">Cari</button>
        <div class="table-responsive">
        <?php
        echo '<table class="table">
        <tr>
        <th>No.Nota</th>
        <th>Nama</th>
        <th>Nama Barang</th>
        <th>Qty</th>
        <th>Total</th>
        <th><img src="img/delete.png" width="16px"></th>
        </tr>
        <tr>
        <span></span>
        </tr>';
        if (isset($_POST['keyword'])) {
            $keyword = $_POST['keyword'];

            $query = "SELECT * FROM penjualan JOIN db_barang ON penjualan.item_id = db_barang.kode_barang WHERE penjualan.nomor_nota LIKE '%$keyword%'";
        } else {
            $query = "SELECT penjualan.id, penjualan.tanggal_nota, penjualan.nomor_nota, penjualan.nama, penjualan.item_id, db_barang.nama_barang, penjualan.quantity, penjualan.total_price, penjualan.status_pembayaran FROM penjualan JOIN db_barang ON penjualan.item_id = db_barang.kode_barang WHERE penjualan.status_pembayaran = 'Belum' ORDER BY penjualan.id ASC, penjualan.item_id ASC";
        }

        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>
                <td>' . $row["nomor_nota"] . '</td>
                <td>' . substr($row["nama"], 0, 10) . '</td>
                <td>' . $row["nama_barang"] . '</td>
                <td>' . $row["quantity"] . '</td>
                <td>' . $row["total_price"] . '</td>
                <td>
                    <input type="checkbox" name="invoices_to_lunasi[]" value="' . $row["nomor_nota"] . '">
                </td>
                </tr>';
            }
        }

        echo '</table>';
        ?>
        </div>
        <br><button type="submit" class="btn btn-success" name="lunasi">Lunasi Pembayaran</button>
    </form>
</body>

</html>