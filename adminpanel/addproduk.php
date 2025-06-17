<?php
require "session.php";
require "../koneksi.php";

// Ambil data kategori
$queryKategori = mysqli_query($mysqli, "SELECT * FROM kategori");

// Fungsi untuk membuat nama file random
function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $characterslength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $characterslength - 1)];
    }
    return $randomString;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <!-- Font Awesome icons -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .no-decoration { text-decoration: none; }
        form div { margin-bottom: 10px; }
        body { background-color: #e7f2f8; }
    </style>
</head>
<body>
    <?php require "navbar.php"; ?> 

    <div class="container mt-5">
        <div class="my-5 col-12 col-md-6">
            <h3>Tambah Produk</h3>

            <form action="" method="post" enctype="multipart/form-data">
                <div>
                    <label for="nama">Nama</label>
                    <input type="text" id="nama" name="nama" class="form-control" autocomplete="off" required>
                </div>
                <div>
                    <label for="kategori">Kategori</label>
                    <select name="kategori" id="kategori" class="form-control" required>
                        <option value="">Pilih Satu</option>
                        <?php while ($data = mysqli_fetch_array($queryKategori)) { ?>
                            <option value="<?php echo $data['id']; ?>"><?php echo $data['nama']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div>
                    <label for="harga">Harga</label>
                    <input type="number" name="harga" id="harga" class="form-control" required>
                </div>
                <div>
                    <label for="stok">Stok</label>
                    <input type="number" name="stok" id="stok" class="form-control" required>
                </div>
                <div>
                    <label for="foto">Foto</label>
                    <input type="file" name="foto" class="form-control" id="foto">
                </div>
                <div>
                    <label for="detail">Detail</label>
                    <textarea name="detail" id="detail" class="form-control" rows="2"></textarea>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary" name="simpan">Simpan</button>
                </div>
            </form>

            <?php
            if (isset($_POST['simpan'])) {
                $nama = htmlspecialchars($_POST['nama']);
                $kategori = htmlspecialchars($_POST['kategori']);
                $harga = htmlspecialchars($_POST['harga']);
                $stok = htmlspecialchars($_POST['stok']);
                $detail = htmlspecialchars($_POST['detail']);

                $target_dir = "../image/";
                $nama_file = basename($_FILES["foto"]["name"]);
                $target_file = $target_dir . $nama_file;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $image_size = $_FILES["foto"]["size"];
                $random_name = generateRandomString();
                $new_name = $random_name . "." . $imageFileType;

                if ($nama == '' || $kategori == '' || $harga == '' || $stok == '') {
                    echo "<script>Swal.fire('Gagal', 'Nama, Kategori, Harga, dan Stok wajib diisi', 'warning');</script>";
                } else {
                    if ($nama_file != '') {
                        if ($image_size > 500000) {
                            echo "<script>Swal.fire('Gagal', 'File tidak boleh lebih dari 500kb', 'warning');</script>";
                            exit;
                        }
                        if (!in_array($imageFileType, ['jpg', 'png', 'gif'])) {
                            echo "<script>Swal.fire('Gagal', 'File wajib bertipe jpg, png, atau gif', 'warning');</script>";
                            exit;
                        }
                        move_uploaded_file($_FILES["foto"]["tmp_name"], $target_dir . $new_name);
                    } else {
                        $new_name = '';
                    }

                    $queryTambah = mysqli_query($mysqli, "INSERT INTO produk (kategori_id, nama, harga, stok, foto, detail)
                        VALUES ('$kategori','$nama','$harga','$stok','$new_name','$detail')");

                    if ($queryTambah) {
                        echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Produk berhasil ditambahkan',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href = 'produk.php';
                            });
                        </script>";
                    } else {
                        echo "<script>Swal.fire('Error', '" . mysqli_error($mysqli) . "', 'error');</script>";
                    }
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
