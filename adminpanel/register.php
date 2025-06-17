<?php
session_start();
require "../koneksi.php";

// Tangkap status redirect
$redirect = false;
if (isset($_SESSION['message']) && isset($_SESSION['msg_type'])) {
    $message = $_SESSION['message'];
    $msg_type = $_SESSION['msg_type'];

    // Cek apakah message-nya tentang register berhasil
    if ($message === 'Register berhasil! Silakan login.') {
        $redirect = true;
    }

    // Hapus setelah ditangkap
    unset($_SESSION['message']);
    unset($_SESSION['msg_type']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
</head>

<style>
    .main{
        /* background-image: linear-gradient(rgba(0, 0, 0, 0.55), rgba(0, 0, 0, 0.55)), url(../image/lan-deng-quddu_dZKkQ-unsplash.jpg); */
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
        height: 100vh;
        
    }

    .login-box{
        height: 320px;
        width: 500px;
        box-sizing: border-box;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.85);

    }

</style>

<body>
    <div class="main d-flex flex-column justify-content-center align-items-center ">
        <div class="login-box p-5 shadow">
            <h2 class="text-center">Register</h2>   
            <form action="" method="post">
                <div class="mb-3">
                    <input type="text" class="form-control" name="username" id="username" placeholder="Username">
                </div>
                 <div class="mb-3">
                    <input type="text" class="form-control" name="email" id="email" placeholder="Email">
                </div>
                <div>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                </div>
                <div>
                    <button class="btn btn-primary form-control mt-3 " type="submit" name="registerbtn" >Register</button>
                </div>
            </form>
           
        </div>
             <!-- Alert -->
    <?php if (isset($message)): ?>
        <div class="alert alert-<?= $msg_type ?> text-center mt-3" role="alert">
            <?= $message ?>
        </div>
        <?php if ($redirect): ?>
            <script>
                setTimeout(function () {
                    window.location.href = 'login.php';
                }, 1000); // Delay 1 detik
            </script>
        <?php endif; ?>
    <?php endif; ?>
         <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['msg_type']; ?> text-center mt-3" role="alert">
            <?php echo $_SESSION['message']; ?>
        </div>
        <?php
            unset($_SESSION['message']);
            unset($_SESSION['msg_type']);
        ?>
        <?php endif; ?>

        <div class="mt-3" style="width: 500px">
            <?php 
            if(isset($_POST['registerbtn'])) {
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                
                $check_email = $mysqli->query("SELECT email FROM user WHERE email ='$email'");
                if ($check_email->num_rows > 0) {
                    $_SESSION['message'] = 'Email sudah terdaftar';
                    $_SESSION['msg_type'] = 'warning';
                    header('Location: register.php'); // Tetap di halaman register
                } else {
                    $mysqli->query("INSERT INTO user(nama, email, password) VALUES ('$username', '$email', '$password')");
                    $_SESSION['message'] = 'Register berhasil! Silakan login.';
                    $_SESSION['msg_type'] = 'success';
                    header('Location: register.php');
                }
            }
            ?>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>