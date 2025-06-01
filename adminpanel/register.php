<?php
    session_start();
    require "../koneksi.php";
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
        background-image: linear-gradient(rgba(0, 0, 0, 0.55), rgba(0, 0, 0, 0.55)), url(../image/lan-deng-quddu_dZKkQ-unsplash.jpg);
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
        height: 100vh;
        
    }

    .login-box{
        height: 300px;
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
                <div>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                </div>
                <div>
                    <button class="btn btn-primary form-control mt-3" type="submit" name="loginbtn" >Register</button>
                </div>
            </form>
        </div>

        <div class="mt-3" style="width: 500px">
        </div>

    </div>
</body>
</html>