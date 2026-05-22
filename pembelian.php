<?php
function returnStock($item_id, $quantity)
{
    global $conn;

    $sql = "UPDATE db_barang SET stock = stock - ? WHERE kode_barang = ?";
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
            $invoice_info_sql = "SELECT id, item_id, quantity FROM pembelian WHERE id = ?";
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

                $delete_sql = "DELETE FROM pembelian WHERE id = ?";
                $stmt = $conn->prepare($delete_sql);
                $stmt->bind_param("s", $invoice_id);

                if ($stmt->execute()) {
                    $isStockReturned = true;
                }
            }
        }
    }
}


$sql = "SELECT pembelian.id, pembelian.tanggal_nota, pembelian.nomor_nota, pembelian.supplier, pembelian.item_id, db_barang.nama_barang, pembelian.quantity, pembelian.total_price FROM pembelian JOIN db_barang ON pembelian.item_id = db_barang.kode_barang ORDER BY pembelian.id ASC, pembelian.item_id ASC";
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
    <h1><strong>Data Pembelian</strong></h1>

    <form method="post" action="" class="container">
        <div class="table-responsive">
        <?php
        if ($result->num_rows > 0) {
            echo '<table class="table">
        <tr>
        <th>No.Nota</th>
        <th>Supplier</th>
        <th>Nama Barang</th>
        <th>Qty</th>
        <th>Total</th>
        <th><img src="img/delete.png" width="16px"></th>
        </tr>
        <tr>
        <span></span>
        </tr>';

            while ($row = $result->fetch_assoc()) {
                echo '<tr>
            <td>' . $row["nomor_nota"] . '</td>
            <td>' . substr($row["supplier"], 0, 10) . '</td>
            <td>' . $row["nama_barang"] . '</td>
            <td>' . $row["quantity"] . '</td>
            <td>' . $row["total_price"] . '</td>
            <td>
                <input type="checkbox" name="invoices_to_delete[]" value="' . $row["id"] . '">
            </td>
            </tr>';
            }

            echo '</table>';
        }
        ?>
        </div>
        <br><button type="submit" class="btn btn-danger" name="delete">Delete Selected</button>
    </form>
</body>

</html>