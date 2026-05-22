<?php
$sql = "SELECT penjualan.id, penjualan.tanggal_nota, penjualan.nomor_nota, penjualan.nama, penjualan.item_id, db_barang.nama_barang, penjualan.quantity, penjualan.total_price FROM penjualan JOIN db_barang ON penjualan.item_id = db_barang.kode_barang WHERE penjualan.status_pembayaran = 'Belum' OR penjualan.status_pembayaran = 'Ter-Lunasi' ORDER BY penjualan.id ASC, penjualan.item_id ASC";
$result = $conn->query($sql);
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
    <h1><strong>Data Piutang</strong></h1>

    <form method="post" action="" class="container">
        <div class="table-responsive">
            <?php
            if ($result->num_rows > 0) {
                echo '<table class="table">
        <tr>
        <th>No.Nota</th>
        <th>Nama</th>
        <th>Nama Barang</th>
        <th>Qty</th>
        <th>Total</th>
        </tr>
        <tr>
        <span></span>
        </tr>';

                while ($row = $result->fetch_assoc()) {
                    echo '<tr>
            <td>' . $row["nomor_nota"] . '</td>
            <td>' . substr($row["nama"], 0, 10) . '</td>
            <td>' . $row["nama_barang"] . '</td>
            <td>' . $row["quantity"] . '</td>
            <td>' . $row["total_price"] . '</td>
            </tr>';
                }
                echo '</table>';
            }
            ?>
        </div>
    </form>
</body>

</html>