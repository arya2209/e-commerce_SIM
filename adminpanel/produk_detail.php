<?php
    require "session.php";
    require "../koneksi.php";

    $id = $_GET['p'];

    $query = mysqli_query($mysqli, "SELECT a.*, b.nama AS nama_kategori FROM produk a JOIN kategori b ON a.kategori_id=b.id WHERE a.id='$id'");
    $data = mysqli_fetch_array($query);

    $queryKategori = mysqli_query($mysqli, "SELECT * FROM kategori WHERE id!='$data[kategori_id]'");
   
    function generateRandomString($length = 10){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characterslength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++){
            $randomString .= $characters[rand(0, $characterslength - 1)];
        } 
        return $randomString;
    }
 
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
    <title>Detail Produk</title>
</head>
<style>
      form div{
        margin-bottom: 10px;
    }
    body{
        background-color: #e7f2f8;    
    }
</style>
<body>
    <?php require "navbar.php"; ?>
        <div class="container mt-5">   
            <h2>Detail Produk</h2>

          <div class="my-5 col-12 col-md-6 mb-5">
                <form action="" method="post" enctype="multipart/form-data">
                <div>
                    <label for="nama">Nama</label>
                    <input type="text" id="nama" name="nama" value="<?php echo $data['nama']; ?>" class="form-control" autocomplete="off" required >
                </div>
                <div>
                    <label for="kategori">Kategori</label>
                    <select name="kategori" id="kategori" class="form-control"required>
                        <option value="<?php echo $data['kategori_id']; ?>"><?php echo $data['nama_kategori']; ?></option>
                     <?php
                        while($dataKategori=mysqli_fetch_array($queryKategori)){
                    ?>
                        <option value="<?php echo $dataKategori['id']; ?>"><?php echo $dataKategori['nama']; ?></option>
                    <?php
                        }
                    ?>
                    </select>
                </div>
                <div>
                    <label for="harga">Harga</label>
                    <input type="number" name="harga" value="<?php echo $data['harga']; ?>" id="harga" class="form-control" required>
                </div>
                <div>
                    <label for="foto">Foto</label>
                    <input type="file" name="foto" class="form-control" id="foto">
                </div>
                <div>
                    <label for="currentfoto">Foto Produk</label>
                    <img src="../image/<?php echo $data['foto'] ?>" alt="" width="300">
                </div>
                <div>
                    <label for="detail">Detail</label>
                    <textarea name="detail" id="detail" class="form-control" cols="30" rows="10">
                        <?php echo $data['detail']; ?>
                    </textarea>
                </div>
                <div>
                    <label for="ketersediaan_stok">Ketersediaan Stok</label>
                    <select name="ketersediaan_stok" id="ketersediaan_stok" class="form-control">
                        <option value="<?php echo $data['ketersediaan_stok']; ?>">
                        <?php echo $data['ketersediaan_stok']; ?></option>
                        <?php
                            if($data['ketersediaan_stok']=='tersedia'){
                        ?>
                             <option value="habis">Habis</option>
                        <?php
                            }
                            else{
                        ?>
                             <option value="tersedia">Tersedia</option>
                        <?php
                            }
                        ?>
                    </select>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary" name="simpan">Simpan</button>
                    <button type="submit" class="btn btn-danger" name="hapus">Hapus</button>
                </div>
                </form>      

                <?php
                    if(isset($_POST['simpan'])){
                        $nama = htmlspecialchars($_POST['nama']);
                        $kategori = htmlspecialchars($_POST['kategori']);
                        $harga = htmlspecialchars($_POST['harga']);
                        $detail = htmlspecialchars($_POST['detail']);
                        $ketersediaan_stok = htmlspecialchars($_POST['ketersediaan_stok']);
    
                        $target_dir = "../image/";
                        $nama_file = basename($_FILES["foto"]["name"]);
                        $target_file = $target_dir . $nama_file;
                        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                        $image_size = $_FILES["foto"]["size"];
                        $random_name = generateRandomString(20);
                        $new_name = $random_name . "." . $imageFileType;

                        if($nama=='' || $kategori=='' || $harga==''){
                ?>
                        <div class="alert alert-warning mt-3" role="alert">
                            Nama, Kategori dan Harga wajib diisi
                        </div>   
                <?php
                        }
                        else{
                            $queryupdate = mysqli_query($mysqli, "UPDATE produk SET kategori_id='$kategori', nama='$nama', harga='$harga',
                            detail='$detail', ketersediaan_stok='$ketersediaan_stok' WHERE id='$id'");

                            if($nama_file!=''){
                                if($image_size > 500000){
                ?>
                                   <div class="alert alert-warning mt-3" role="alert">
                                        File tidak boleh lebih dari 500 kb
                                    </div>   
                <?php
                                }
                                else{
                                    if($imageFileType!= 'jpg' && $imageFileType!= 'png' && $imageFileType!= 'gif' ){
                ?>  
                                         <div class="alert alert-warning mt-3" role="alert">
                                            File wajib bertipe jpg atau png atau gif
                                        </div>  
                <?php
                                    }
                                    else{
                                        move_uploaded_file($_FILES["foto"]["tmp_name"], $target_dir . $new_name);

                                        $queryupdate = mysqli_query($mysqli, "UPDATE produk SET foto='$new_name' 
                                        WHERE id='$id'");

                                        if($queryupdate){
                ?>              
                                            <div class="alert alert-primary mt-3" role="alert">
                                                    Produk Berhasil Diupdate
                                            </div> 
                                                <meta http-equiv="refresh" content="2; url=produk.php"/>
                <?php
                                        }
                                        else{
                                            echo mysql_error($mysqli);
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if(isset($_POST['hapus'])){
                        $queryhapus = mysqli_query($mysqli, "DELETE FROM produk WHERE id='$id'");

                        if($queryhapus){
                ?>
                                <div class="alert alert-primary mt-3" role="alert">
                                        Produk Berhasil Dihapus
                                </div> 
                                    <meta http-equiv="refresh" content="2; url=produk.php" />
                <?php
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