<?php
function returnStock($item_id, $quantity)
{
    global $conn;

    $sql = "UPDATE db_barang SET stock = stock + ? WHERE kode_barang = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $quantity, $item_id);

    if ($stmt->execute()) {
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $invoices_to_delete = $_POST["invoices_to_delete"];
    foreach ($invoices_to_delete as $invoice_id) {
        $isStockReturned = false;
        if (!$isStockReturned) {
            $invoice_info_sql = "SELECT id, item_id, quantity FROM penjualan WHERE id = ?";
            $stmt = $conn->prepare($invoice_info_sql);
            $stmt->bind_param("s", $invoice_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $invoice_info = $result->fetch_assoc();
                $id = $invoice_info['id'];
                $item_id = $invoice_info['item_id'];
                $quantity = $invoice_info['quantity'];

                returnStock($item_id, $quantity);

                $delete_sql = "DELETE FROM penjualan WHERE id = ?";
                $stmt = $conn->prepare($delete_sql);
                $stmt->bind_param("s", $invoice_id);

                if ($stmt->execute()) {
                    $isStockReturned = true;
                }
            }
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
    <h1><strong>Data Penjualan</strong></h1>

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
            $query = "SELECT penjualan.id, penjualan.tanggal_nota, penjualan.nomor_nota, penjualan.nama, penjualan.item_id, db_barang.nama_barang, penjualan.quantity, penjualan.total_price FROM penjualan JOIN db_barang ON penjualan.item_id = db_barang.kode_barang ORDER BY penjualan.id ASC, penjualan.item_id ASC";
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
                    <input type="checkbox" name="invoices_to_delete[]" value="' . $row["id"] . '">
                </td>
                </tr>';
            }
        }
        ?>
        </table>
        </div>
        <br><button type="submit" class="btn btn-danger" name="delete">Delete Selected</button>
    </form>
</body>
<script>
    document.getElementById("select-all-btn").addEventListener("click", function() {
        var checkboxes = document.querySelectorAll('input[name="invoices_to_delete[]"]');
        var selectedNota = null;
        var isChecked = false;

        checkboxes.forEach(function(checkbox) {
            if (checkbox.checked) {
                isChecked = true;
                selectedNota = checkbox.parentElement.previousElementSibling.previousElementSibling.previousElementSibling.previousElementSibling.innerText;
            }
        });

        if (!isChecked) {
            alert("No invoices selected.");
            return;
        }

        checkboxes.forEach(function(checkbox) {
            var nota = checkbox.parentElement.previousElementSibling.previousElementSibling.previousElementSibling.previousElementSibling.innerText;
            if (nota === selectedNota) {
                checkbox.checked = true;
            }
        });
    });
</script>

</html>