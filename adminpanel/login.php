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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<style>
    .main {
        background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url(../image/gambar1.jpg);
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
        height: 100vh;
    }

    .login-box {
        height: 300px;
        width: 500px;
        box-sizing: border-box;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.85);
    }

    .password-field {
        position: relative;
    }

    .password-field input {
        padding-right: 40px;
    }

    .toggle-password {
        position: absolute;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);
        cursor: pointer;
        color: #999;
        font-size: 1.2rem;
        line-height: 1;
    }
</style>

<body>
    <div class="main d-flex flex-column justify-content-center align-items-center ">
        <div class="login-box p-5 shadow">
            <h2 class="text-center p-2"><strong>LOGIN</strong></h2>
            <form action="" method="post">
                <div class="mb-3">
                    <input type="text" class="form-control" name="username" id="username" placeholder="Username">
                </div>
                <div class="password-field">
                    <input type="password" class="form-control" name="password" id="loginPassword"
                        placeholder="Password" required>
                    <i class="fas fa-eye toggle-password" onclick="togglePassword('loginPassword', this)"></i>
                </div>
                <div>
                    <button class="btn btn-primary form-control mt-3" type="submit" name="loginbtn">Login</button>
                </div>
            </form>
            <p class="text-center p-1">Don't have an Account? <a href="register.php">Register</a></p>
        </div>

        <div class="mt-3" style="width: 500px">
            <?php
            if (isset($_POST['loginbtn'])) {
                $username = htmlspecialchars($_POST['username']);
                $password = htmlspecialchars($_POST['password']);

                $query = mysqli_query($mysqli, "SELECT * FROM user WHERE nama='$username'");
                $countdata = mysqli_num_rows($query);
                $data = mysqli_fetch_array($query);

                if ($countdata > 0) {
                    if (password_verify($password, $data['password'])) {
                        $_SESSION['nama'] = $data['nama'];
                        $_SESSION['login'] = true;
                        header('location: ../adminpanel');
                    } else {
                        ?>
                        <div class="alert alert-warning text-center" role="alert">
                            Password Salah
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="alert alert-warning text-center" role="alert">
                        Akun tidak tersedia
                    </div>
                    <?php
                }
                ;
            }
            ?>
        </div>
    </div>

    <script>
        function togglePassword(id, icon) {
            const input = document.getElementById(id);
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>
</body>

</html>