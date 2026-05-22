<?php
$sql = "SELECT nama_barang, SUM(stock) as total_stock, kode_barang, id, harga_beli, harga_jual FROM db_barang GROUP BY nama_barang, kode_barang, id ORDER BY id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Mata Biru</title>
    <link rel="shortcut icon" href="icon.png">
</head>

<body>
    <h1><strong>Data Stock</strong></h1>
    <form method="post" class="container">
        <div class="table-responsive">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
            foreach ($_POST['checkbox'] as $id) {
                $query = "DELETE FROM db_barang WHERE id = $id";
                mysqli_query($conn, $query);
                header('Location: index.php?go=stock');
            }
        }
        if ($result->num_rows > 0) {
            $totalStock = 0;
            echo '<table class="table">
        <tr>
        <th>No</th>
        <th>Kode</th>
        <th>Nama Barang</th>
        <th>Harga Beli</th>
        <th>Harga Jual</th>
        <th>Stock</th>
        <th>Edit</th>
        <th><img src="img/delete.png" width="16px"></th>
        </tr>
        <tr>
        <span></span>
        </tr>';

            while (($row = $result->fetch_assoc())) {
                echo '<tr>
            <td>' . $row['id'] . '</td>
            <td>' . $row['kode_barang'] . '</td>
            <td>' . $row['nama_barang'] . '</td>
            <td>' . $row['harga_beli'] . '</td>
            <td>' . $row['harga_jual'] . '</td>
            <td>' . $row['total_stock'] . '</td>
            <td><a id="edit" href="index.php?go=edit_barang&id=' . $row['id'] . '"><img src="img/edit.png" width="16px"></a></td>
            <td><input type="checkbox" name="checkbox[]" value="' . $row['id'] . '"></td>
            </tr>';

                $totalStock += $row['total_stock'];
            }
            echo '</table>';
        } else {
            echo 'No products available.';
        }
        $conn->close();
        ?>
        </div>
        <br><button type="submit" class="btn btn-danger" name="delete">Delete Selected</button>
    </form><br>
    <button class="btn btn-primary" onclick="window.location.href='index.php?go=add_barang'">Tambah Barang</button>
</body>

</html>