<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $userType = $_POST['userType'];
    $payment_expiry = '';
    if ($userType === 'monthly') {
        $payment_expiry = date('Y-m-d 00:00:00', strtotime('+1 month'));
    } elseif ($userType === 'yearly') {
        $payment_expiry = date('Y-m-d 00:00:00', strtotime('+1 year'));
    } elseif ($userType === 'weekly') {
        $payment_expiry = date('Y-m-d 00:00:00', strtotime('+1 week'));
    } elseif ($userType === 'trial') {
        $payment_expiry = date('Y-m-d 00:00:00', strtotime('+3 day'));
    } elseif ($userType === 'custom') {
        $customType = $_POST['customType'];
        $customInput = $_POST['customInput'];
        $payment_expiry = date('Y-m-d 00:00:00', strtotime('+' . $customInput . $customType));
    }
    $payment_status = "paid";
    $imageType = 'pp';

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        echo 'Username sudah digunakan.';
    } else {
        $query = "INSERT INTO users (username, password, payment_expiry, payment_status) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);

        $file_name = $_FILES['file']['name'];
        $file_temp = $_FILES['file']['tmp_name'];
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $new_file_name = $username . "." . $file_extension;

        $upload_directory = "upload/profile-picture/";
        $file_path = $upload_directory . $new_file_name;

        if (move_uploaded_file($file_temp, $file_path)) {
            $date = date('Y-m-d H:i:s');
            $insertQuery = "INSERT INTO images (username, image_path, payment_date, tipe) VALUES ('$username', '$file_path', '$date', '$imageType')";

            if (mysqli_query($conn, $insertQuery)) {
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "ssss", $username, $password, $payment_expiry, $payment_status);

                    if (mysqli_stmt_execute($stmt)) {
                        mysqli_stmt_close($stmt);
                        mysqli_close($conn);
                        echo 'Username berhasil didaftarkan.';
                    } else {
                        echo "Error: " . mysqli_stmt_error($stmt);
                    }
                } else {
                    echo "Error: " . mysqli_error($conn);
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
    <title>MataBiru</title>
</head>

<body>
    <h1>Registrasi</h1>
    <form action="" method="POST" enctype="multipart/form-data" class="container">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" class="form-control" id="username" name="username" required="" autocomplete="off">
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required="" autocomplete="off">
        </div>

        <div class="form-group">
            <label for="file">Profile Picture:</label>
            <input type="file" class="form-control-file" name="file" required="" autocomplete="off">
        </div>

        <div class="form-group">
            <label for="userType">Type Account:</label>
            <select class="form-control" id="userType" name="userType">
                <option value="0" selected="" hidden="">Pilih Durasi Akun</option>
                <option value="trial">3 Days - Trial</option>
                <option value="weekly">1 Week - Weekly</option>
                <option value="monthly">1 Month - Monthly</option>
                <option value="yearly">1 Year - Yearly</option>
                <option value="custom">Custom</option>
            </select>
        </div>

        <div class="form-group" id="additionalInput" style="display: none;">
            <label for="customInput">Custom Duration:</label>
            <div style="display: flex; width: 20%;">
                <input type="number" class="form-control" id="customInput" name="customInput" placeholder="Input">
                <select class="form-control" name="customType" id="customType">
                    <option value="0" selected hidden>Select</option>
                    <option value="week">Week</option>
                    <option value="month">Month</option>
                    <option value="year">Year</option>
                </select>
            </div>
        </div>

        <button type="submit" class="btn btn-success">Daftarkan</button>
    </form>
</body>
<script>
    document.getElementById('userType').addEventListener('change', function() {
        var selectedValue = this.value;
        var additionalInput = document.getElementById('additionalInput');

        if (selectedValue === 'custom') {
            additionalInput.style.display = 'block';
        } else {
            additionalInput.style.display = 'none';
        }
    });
</script>

</html>