<?php
session_start();
require 'konfig.php';
$user_id = $_SESSION['username'];
$query = "SELECT * FROM users WHERE username = '$user_id'";
$result = mysqli_query($conn, $query);

if ($result) {
    $payment = mysqli_fetch_assoc($result);

    if (strtotime($payment['payment_expiry']) < time()) {
        $updateQuery = "UPDATE users SET payment_status = 'expired' WHERE username = '$user_id'";
        if (mysqli_query($conn, $updateQuery)) {
            header('Location: expired.php');
            session_unset();
            session_destroy();
            header("Location: login.php");
            exit();
            exit;
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } elseif ((strtotime($payment['payment_expiry']) - time()) < (72 * 60 * 60)) {
        $warning = floor((strtotime($payment['payment_expiry']) - time()) / 86400 + 1);
        $warnMassage = '<div class="warning">!!!Masa Aktif: ' . $warning . ' Hari</div>';
    } else {
        $warnMassage = '';
    }
}

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

$query = "SELECT * FROM images where tipe = 'pp' AND username = '$user_id'";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);

if ($row) {
    $imagePath = $row['image_path'];
}

mysqli_free_result($result);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title>MataBiru</title>
    <link rel="shortcut icon" href="img/icon.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .square-rounded {
            max-width: 32px;
            aspect-ratio: 1/1;
            border-radius: 50%;
        }
    </style>
</head>

<body>
    <header class="bg-primary text-white">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <abbr title="Edit Profile Picture">
                            <li id="pp"><a href="?go=edit_pp"><img src="<?php echo $imagePath; ?>" class="img-fluid square-rounded bg-light"></a></li>
                        </abbr>
                        <?php
                        include 'nav.php';
                        ?>
                    </ul>
                </div>
            </nav>
        </div>
    </header>
    <main class="container mt-4">
        <?php
        include 'halaman.php';
        include 'konfig.php';
        echo $warnMassage;
        ?>
        <form method="post" action="" class="mt-3">
            <input type="submit" name="logout" id="logout" value="Logout" class="btn btn-danger">
        </form>
    </main>
    <footer class="bg-dark text-white mt-4 p-4 text-center">
        &copy; 2023 Damar Putra. All Rights Reserved.
    </footer>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var inputs = document.querySelectorAll('input');
        inputs.forEach(function(input) {
            input.setAttribute('autocomplete', 'off');
        });
    });
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
    });
    document.addEventListener("DOMContentLoaded", function() {
        const dropdown = document.querySelector('.dropdown');
        const dropdownMenu = document.querySelector('.dropdown-menu');

        dropdown.addEventListener('click', function() {
            dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
        });

        document.addEventListener('click', function(event) {
            if (!dropdown.contains(event.target)) {
                dropdownMenu.style.display = 'none';
            }
        });
    });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</html>