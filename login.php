<?php
session_start();
include "koneksi.php";

// Check jika sudah ada user yang login admin
if (isset($_SESSION['username'])) {
    header('location:admin.php');
    exit;
}

$status = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $stmt = $conn->prepare("SELECT username FROM users WHERE username =? AND password=?");

    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $hasil = $stmt->get_result();
    $row = $hasil->fetch_array(MYSQLI_ASSOC);

    // Periksa login
    if (!empty($row)) {
        // Menciptakan session
        $_SESSION['username'] = $row['username'];

        // Menuju ke halaman admin
        header("location:admin.php");
        exit; // PENTING: Hentikan script setelah redirect
    } else {
        // Login Gagal: Set status danger
        $status = "danger";
    }

    $stmt->close();
    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | My Daily Journal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
    <link rel="icon" href="assets/img/logo.jpeg" />
</head>
<body class="bg-danger-subtle">
    <div class="container mt-5 pt-5">
        <div class="row">
            <div class="col-12 col-sm-8 col-md-6 m-auto">
                <div class="card border-0 shadow rounded-5">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <i class="bi bi-person-circle h-1 display-4"></i>
                            <p>My Daily Journal</p>
                            <hr>
                        </div>
                        <form action="" method="post">
                            <input type="text" name="username" class="form-control my-4 py-2 rounded-4" placeholder="Username" required>
                            <input type="password" name="password" class="form-control my-4 py-2 rounded-4" placeholder="Password" required>
                            <div class="text-center my-3 d-grid">
                                <button class="btn btn-danger rounded-4" type="submit" name="submit">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($status != "") : ?>
        <div class="container mt-3">
            <div class="row">
                <div class="col-12 col-sm-8 col-md-6 m-auto">
                    <div class="card border-0 shadow rounded-5">
                        <div class="card-body text-center rounded-5 bg-<?= $status ?>-subtle">

                            Username: <?= htmlspecialchars($_POST["username"] ?? "") ?>
                            <br>
                            Password: <?= htmlspecialchars($_POST["password"] ?? "") ?>
                            <br>
                            <hr>

                            <?php if ($status == "success") : ?>
                                <p class="text-success fw-bold">Username dan Password Benar</p>
                            <?php else : ?>
                                <p class="text-danger fw-bold">Username dan Password Salah</p>
                            <?php endif ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
