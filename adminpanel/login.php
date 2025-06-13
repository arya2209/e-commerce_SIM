<?php
session_start();
require "../koneksi.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<style>
    .main {
        height: 100vh;
        background-color: #f8f9fa;
    }

    .login-box {
        width: 400px;
        background: rgba(255, 255, 255, 0.9);
        padding: 30px;
        border-radius: 10px;
    }
</style>

<body>
<div class="main d-flex justify-content-center align-items-center">
    <div class="login-box shadow">
        <h2 class="text-center mb-4">Login</h2>
        <form action="" method="post">
            <div class="mb-3">
                <input type="text" class="form-control" name="username" placeholder="Username" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <button class="btn btn-primary w-100" type="submit" name="loginbtn">Login</button>
        </form>
        <p class="text-center mt-3">Don't have an account? <a href="register.php">Register</a></p>

        <!-- Alert -->
        <?php
        if (isset($_POST['loginbtn'])) {
            $username = htmlspecialchars($_POST['username']);
            $password = htmlspecialchars($_POST['password']);

            $query = mysqli_query($mysqli, "SELECT * FROM user WHERE nama='$username'");
            $count = mysqli_num_rows($query);
            $data = mysqli_fetch_assoc($query);

            if ($count > 0) {
                if (password_verify($password, $data['password'])) {
                    $_SESSION['login'] = true;
                    $_SESSION['nama'] = $data['nama'];
                    $_SESSION['role'] = $data['role'];

                    // Redirect based on role
                    if ($data['role'] == 'admin') {
                        header("Location: ../adminpanel");
                    } else {
                        header("Location: ../home.php");
                    }
                    exit;
                } else {
                    echo '<div class="alert alert-warning text-center mt-3">Password salah</div>';
                }
            } else {
                echo '<div class="alert alert-warning text-center mt-3">Akun tidak ditemukan</div>';
            }
        }
        ?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
