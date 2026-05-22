<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pembayaran</title>
</head>

<body>
    <h1>Status User</h1>
    <form method="post" action="">
        <div class="container">
        <table class="table table-striped">
            <tr>
                <th>Username</th>
                <th>Masa Aktif</th>
                <th>Status Pembayaran</th>
                <th>Edit</th>
                <th><img src="img/delete.png" width="16px"></th>
            </tr>
            <?php
            if (isset($_POST['submit'])) {
                foreach ($_POST['checkbox'] as $id) {
                    $getUser = "SELECT username FROM users WHERE id = $id";
                    mysqli_query($conn, $getUser);
                    $usernameResult = mysqli_query($conn, $getUser);
                    $userData = mysqli_fetch_assoc($usernameResult);
                    $username = $userData['username'];
                    $query = "DELETE FROM users WHERE id = $id";
                    mysqli_query($conn, $query);
                    $imageQuery = "DELETE FROM images WHERE username = '$username' AND tipe = 'pp'";
                    mysqli_query($conn, $imageQuery);
                }
            }

            $query = "SELECT id, username, payment_expiry, payment_status FROM users";
            $result = mysqli_query($conn, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $tenggang = floor((strtotime($row['payment_expiry']) - time()) / 86400 + 1);
                    if ($tenggang >= 0) {
                        if ($tenggang >= 365) {
                            $masaTenggang = number_format(floor((strtotime($row['payment_expiry']) - time()) / 86400 + 1) / 365, 0, '.', '');
                            $time = "Tahun";
                        } elseif ($tenggang >= date('t') && $tenggang <= 365) {
                            $masaTenggang = number_format(floor((strtotime($row['payment_expiry']) - time()) / 86400 + 1) / date('t'), 0, '.', '');
                            $time = "Bulan";
                        } elseif ($tenggang >= 7 && $tenggang <= date('t')) {
                            $masaTenggang = number_format(floor((strtotime($row['payment_expiry']) - time()) / 86400 + 1) / 7);
                            $time = "Minggu";
                        } else {
                            $masaTenggang = floor((strtotime($row['payment_expiry']) - time()) / 86400 + 1);
                            $time = "Hari";
                        }
                    } else {
                        $masaTenggang = "0";
                        $time = "Hari";
                    }
                    echo "<tr>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td>" . $masaTenggang . " " . $time . "</td>";
                    echo "<td>" . $row['payment_status'] . "</td>";
                    echo '<td><a href="index.php?go=edit_user&id=' . $row['id'] . '"><img src="img/edit.png" width="16px"></a></td>';
                    echo '<td><input type="checkbox" name="checkbox[]" value="' . $row['id'] . '"></td>';
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>Tidak ada data User.</td></tr>";
            }
            mysqli_close($conn);
            ?>
        </table>
        <input type="submit" name="submit" value="Delete User" class="btn btn-danger">
    </div>
    </form>
</body>

</html>