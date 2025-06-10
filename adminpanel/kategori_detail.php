<?php
    require "session.php";
    require "../koneksi.php";

    $id = $_GET['p'];

    $query = mysqli_query($mysqli, "SELECT * FROM kategori WHERE id='$id'");
    $data = mysqli_fetch_array($query);
   
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
     <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kategori</title>
</head>
<style>
     body{
        background-color: #e7f2f8;    
    }
</style>
<body>
    <?php require "navbar.php"; ?>
    <div class="container mt-5">   
        <h2>Detail Kategori</h2>

        <div class="col-12 col-md-6">
            <form action="" method="post">
                <div>
                    <label for="Kategori">Kategori</label>
                    <input type="text" name="kategori" id="kategori" class="form-control" value="<?php echo $data['nama']; ?>">
                </div>    
                
                <div class="mt-5 d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary" name="editBtn">Edit</button>
                    <button type="submit" class="btn btn-danger" name="deleteBtn">Delete</button>
                </div>
            </form>

            <?php
                if(isset($_POST['editBtn'])){
                    $kategori = htmlspecialchars($_POST['kategori']);

                    if($data['nama']==$kategori){
                        ?>
                          <meta http-equiv="refresh" content="0; url=kategori.php" />
                        <?php
                    }
                    else{
                        $query = mysqli_query($mysqli, "SELECT * FROM kategori WHERE nama='$kategori'");
                        $jumlahData = mysqli_num_rows($query);
                        
                        if($jumlahData> 0){
                            ?>
                                <div class="alert alert-warning mt-3" role="alert">
                                     Kategori Sudah Ada
                                </div>  
                            <?php
                        }
                        else{
                            $querysimpan = mysqli_query($mysqli, "UPDATE kategori SET nama='$kategori' WHERE 
                            id='$id'");
    
                            if($querysimpan){
                                ?>
                                     <div class="alert alert-primary mt-3" role="alert">
                                        Kategori Berhasil Tersimpan
                                    </div> 
                                    <meta http-equiv="refresh" content="2; url=kategori.php" />
                                <?php
                            }
                            else{
                                echo mysql_error($mysqli);
                                }    
                        }
                    }
                }
                    if(isset($_POST['deleteBtn'])){
                        $queryCheck= mysqli_query($mysqli, "SELECT * FROM produk WHERE kategori_id='$id'");
                        $dataCount = mysqli_num_rows($queryCheck);
                        
                        if($dataCount>0){
                            ?>
                                  <div class="alert alert-warning mt-3" role="alert">
                                        Kategori tidak bisa dihapus karena sudah digunakan di produk
                            <?php
                            die();
                        }


                        $queryDelete = mysqli_query($mysqli, "DELETE FROM kategori WHERE id='$id'");

                        if($queryDelete){
                            ?>
                                <div class="alert alert-primary mt-3" role="alert">
                                        Kategori Berhasil Dihapus
                                </div> 
                                    <meta http-equiv="refresh" content="2; url=kategori.php" />
                            <?php
                        }
                        else{
                            echo mysql_error($mysqli);
                        }
                    }
            ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
        </script>
</body>
</html>