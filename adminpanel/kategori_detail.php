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
    <!-- Font Awesome -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kategori</title>
</head>
<style>
    body {
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
                    <label for="kategori">Kategori</label>
                    <input type="text" name="kategori" id="kategori" class="form-control" value="<?php echo $data['nama']; ?>" required>
                </div>

                <div class="mt-5 d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary" name="editBtn">Edit</button>
                    <button type="submit" class="btn btn-danger" name="deleteBtn">Hapus</button>
                </div>
            </form>

            <?php
            if (isset($_POST['editBtn'])) {
                $kategori = htmlspecialchars($_POST['kategori']);

                if ($data['nama'] == $kategori) {
                    echo "<script>window.location.href = 'kategori.php';</script>";
                } else {
                    $query = mysqli_query($mysqli, "SELECT * FROM kategori WHERE nama='$kategori'");
                    $jumlahData = mysqli_num_rows($query);

                    if ($jumlahData > 0) {
                        echo "<script>
                            Swal.fire({
                                icon: 'warning',
                                title: 'Kategori sudah ada',
                                text: 'Silakan gunakan nama lain.'
                            });
                        </script>";
                    } else {
                        $querysimpan = mysqli_query($mysqli, "UPDATE kategori SET nama='$kategori' WHERE id='$id'");

                        if ($querysimpan) {
                            echo "<script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: 'Kategori berhasil diperbarui.'
                                }).then(() => {
                                    window.location.href = 'kategori.php';
                                });
                            </script>";
                        } else {
                            echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: '" . mysqli_error($mysqli) . "'
                                });
                            </script>";
                        }
                    }
                }
            }

            if (isset($_POST['deleteBtn'])) {
                $queryCheck = mysqli_query($mysqli, "SELECT * FROM produk WHERE kategori_id='$id'");
                $dataCount = mysqli_num_rows($queryCheck);

                if ($dataCount > 0) {
                    echo "<script>
                        Swal.fire({
                            icon: 'warning',
                            title: 'Tidak Bisa Dihapus',
                            text: 'Kategori ini sedang digunakan pada produk.'
                        });
                    </script>";
                    exit();
                }

                $queryDelete = mysqli_query($mysqli, "DELETE FROM kategori WHERE id='$id'");

                if ($queryDelete) {
                    echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Kategori Dihapus',
                            text: 'Kategori berhasil dihapus.'
                        }).then(() => {
                            window.location.href = 'kategori.php';
                        });
                    </script>";
                } else {
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Menghapus',
                            text: '" . mysqli_error($mysqli) . "'
                        });
                    </script>";
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
