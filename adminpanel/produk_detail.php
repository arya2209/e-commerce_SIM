<?php
require "session.php";
require "../koneksi.php";

$id = $_GET['p'];

$query = mysqli_query($mysqli, "SELECT a.*, b.nama AS nama_kategori FROM produk a JOIN kategori b ON a.kategori_id=b.id WHERE a.id='$id'");
$data = mysqli_fetch_array($query);

$queryKategori = mysqli_query($mysqli, "SELECT * FROM kategori WHERE id!='$data[kategori_id]'");

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk</title>
    <style>
        form div { margin-bottom: 10px; }
        body { background-color: #e7f2f8; }
    </style>
</head>

<body>
<?php require "navbar.php"; ?>
<div class="container mt-5">
    <h2>Detail Produk</h2>
    <div class="my-5 col-12 col-md-6 mb-5">
        <form action="" method="post" enctype="multipart/form-data">
            <div>
                <label for="nama">Nama</label>
                <input type="text" id="nama" name="nama" value="<?= $data['nama']; ?>" class="form-control" required>
            </div>
            <div>
                <label for="kategori">Kategori</label>
                <select name="kategori" id="kategori" class="form-control" required>
                    <option value="<?= $data['kategori_id']; ?>"><?= $data['nama_kategori']; ?></option>
                    <?php while ($dataKategori = mysqli_fetch_array($queryKategori)) { ?>
                        <option value="<?= $dataKategori['id']; ?>"><?= $dataKategori['nama']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div>
                <label for="harga">Harga</label>
                <input type="number" name="harga" value="<?= $data['harga']; ?>" id="harga" class="form-control" required>
            </div>
            <div>
                <label for="stok">Stok</label>
                <input type="number" name="stok" value="<?= $data['stok']; ?>" id="stok" class="form-control" required>
            </div>
            <div>
                <label for="foto">Foto</label>
                <input type="file" name="foto" class="form-control" id="foto">
            </div>
            <div>
                <label for="currentfoto">Foto Produk</label><br>
                <img src="../image/<?= $data['foto']; ?>" alt="" width="300">
            </div>
            <div>
                <label for="detail">Detail</label>
                <textarea name="detail" id="detail" class="form-control" rows="2"><?= trim($data['detail']); ?></textarea>
            </div>
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary" name="simpan">Simpan</button>
                <button type="button" class="btn btn-danger" id="btnHapus">Hapus</button>
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
            $random_name = generateRandomString(20);
            $new_name = $random_name . "." . $imageFileType;

            if ($nama == '' || $kategori == '' || $harga == '') {
                echo "<script>Swal.fire({ icon: 'warning', title: 'Gagal', text: 'Nama, Kategori dan Harga wajib diisi' });</script>";
            } else {
                mysqli_query($mysqli, "UPDATE produk SET kategori_id='$kategori', nama='$nama', harga='$harga', stok='$stok', detail='$detail' WHERE id='$id'");

                if ($nama_file != '') {
                    if ($image_size > 500000) {
                        echo "<script>Swal.fire({ icon: 'warning', title: 'Ukuran File Terlalu Besar', text: 'File tidak boleh lebih dari 500 KB' });</script>";
                        exit;
                    } elseif (!in_array($imageFileType, ['jpg', 'png', 'gif'])) {
                        echo "<script>Swal.fire({ icon: 'warning', title: 'Format Tidak Didukung', text: 'File wajib JPG, PNG, atau GIF' });</script>";
                        exit;
                    } else {
                        move_uploaded_file($_FILES["foto"]["tmp_name"], $target_dir . $new_name);
                        mysqli_query($mysqli, "UPDATE produk SET foto='$new_name' WHERE id='$id'");
                    }
                }

                echo "<script>
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Produk berhasil diupdate' })
                    .then(() => { window.location.href = 'produk.php'; });
                </script>";
            }
        }

        if (isset($_POST['hapus'])) {
            mysqli_query($mysqli, "DELETE FROM produk WHERE id='$id'");
            echo "<script>
                Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Produk berhasil dihapus' })
                .then(() => { window.location.href = 'produk.php'; });
            </script>";
        }
        ?>
    </div>
</div>

<script>
    document.getElementById("btnHapus").addEventListener("click", function () {
        Swal.fire({
            title: 'Yakin hapus produk ini?',
            text: 'Data tidak bisa dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = this.closest('form');
                const input = document.createElement("input");
                input.type = "hidden";
                input.name = "hapus";
                input.value = "1";
                form.appendChild(input);
                form.submit();
            }
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
