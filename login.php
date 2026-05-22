<?php
session_start();

if (isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'konfig.php';
    if ($_POST['guest']) {
        $username = 'guest';
        $password = 'guest';
    } else {
        $username = $_POST['username'];
        $password = $_POST['password'];
    }

    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            header('Location: index.php');
            exit;
        } else {
            echo "Password salah.";
        }
    } else {
        echo "Username tidak ditemukan.";
    }

    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MataBiru</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title">Login</h2>
                        <form action="" method="POST" class="mt-4">
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" class="form-control" id="username" name="username" autocomplete="off">
                            </div>

                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>

                            <input type="submit" class="btn btn-primary" value="Masuk">
                            <input type="submit" class="btn btn-secondary" name="guest" value="Guest">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</html>