`   <?php
    require "session.php";
    require "../koneksi.php";

    // Ambil data produk dari database
    $query = mysqli_query($mysqli, "SELECT produk.*, kategori.nama as nama_kategori 
                                  FROM produk 
                                  JOIN kategori ON produk.kategori_id = kategori.id");

    $jumlahProduk = mysqli_num_rows($query);


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
    <title>Produk</title>
</head>
<style>
     .no-decoration{
        text-decoration:none;
    }

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
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="../adminpanel" class="no-decoration text-muted">
                        <i class="fa fa-home"></i> Home 
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Produk
                </li>
             </ol>
        </nav>

        <div class="mt-3 mb-5">
            
            <h2>List produk</h2>
            <div class="d-flex justify-content-end">
            <a href="addproduk.php" class="btn btn-primary d-flex justify-content-end">+ Tambah Produk</a> 
            </div>
            <div class="table-responsive mt-5">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No .</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Ketersediaan Stok</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if($jumlahProduk==0){
                        ?>
                            <tr>
                                <td colspan=6 class="text-center">Data Produk tidak tersedia</td>
                            </tr>
                        <?php
                            }
                            else{
                                $jumlah = 1;
                                while($data=mysqli_fetch_array($query)){
                        ?>
                                <tr>
                                    <td><?php echo $jumlah; ?></td>
                                    <td><?php echo $data['nama']; ?></td>
                                    <td><?php echo $data['nama_kategori']; ?></td>
                                    <td><?php echo $data['harga']; ?></td>
                                    <td><?php echo $data['ketersediaan_stok']; ?></td>
                                    <td>
                                        <a href="produk_detail.php?p= <?php echo $data['id']; ?>" 
                                        class="btn btn-info"> <i class="fa fa-search"></i></a>
                                    </td>
                                </tr>
                        <?php        
                                $jumlah++;    
                                }
                            }
                        ?>    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
        </script>
</body>
</html>