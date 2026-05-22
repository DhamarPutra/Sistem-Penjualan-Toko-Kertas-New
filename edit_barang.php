<?php
if (isset($_GET['id'])) {
    $id_barang = $_GET['id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
        $kode_baru = $_POST['kode'];
        $nama_baru = $_POST['nama'];
        $harga_beli_baru = $_POST['harga_beli'];
        $harga_jual_baru = $_POST['harga_jual'];

        $query_update = "UPDATE db_barang SET kode_barang = ?, nama_barang = ?, harga_beli = ?, harga_jual = ? WHERE id = ?";
        $stmt = $conn->prepare($query_update);
        $stmt->bind_param('sssss', $kode_baru, $nama_baru, $harga_beli_baru, $harga_jual_baru, $id_barang);
        $stmt->execute();

        header('Location: index.php?go=stock');
        exit;
    }

    $query_select = "SELECT * FROM db_barang WHERE id = ?";
    $stmt = $conn->prepare($query_select);
    $stmt->bind_param('s', $id_barang);
    $stmt->execute();
    $result = $stmt->get_result();
    $barang = $result->fetch_assoc();

    if (!$barang) {
        echo 'Barang tidak ditemukan.';
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang</title>
    <link rel="stylesheet" href="css/register.css">
</head>

<body>
    <h1>Edit Barang</h1>
    <form action="" method="POST" class="container">
        <div class="form-group">
            <label for="kode">Kode Barang:</label>
            <input type="text" class="form-control" name="kode" value="<?php echo $barang['kode_barang']; ?>">
        </div>

        <div class="form-group">
            <label for="nama">Nama Barang:</label>
            <input type="text" class="form-control" name="nama" value="<?php echo $barang['nama_barang']; ?>">
        </div>

        <div class="form-group">
            <label for="harga_beli">Harga Beli:</label>
            <input type="text" class="form-control" name="harga_beli" value="<?php echo $barang['harga_beli']; ?>">
        </div>

        <div class="form-group">
            <label for="harga_jual">Harga Jual:</label>
            <input type="text" class="form-control" name="harga_jual" value="<?php echo $barang['harga_jual']; ?>">
        </div>

        <input type="submit" name="submit" value="Simpan Perubahan" class="btn btn-success">
    </form>
</body>

</html>