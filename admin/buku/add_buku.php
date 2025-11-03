<?php
include "inc/koneksi.php";

// Ambil kode terakhir id_buku
$carikode = mysqli_query($koneksi, "SELECT id_buku FROM tb_buku ORDER BY id_buku DESC LIMIT 1");
$datakode = mysqli_fetch_array($carikode);
if ($datakode) {
    $kode = $datakode['id_buku'];
    $urut = (int) substr($kode, 1, 3);
    $tambah = $urut + 1;
} else {
    $tambah = 1;
}
if (strlen($tambah) == 1) {
    $format = "B00" . $tambah;
} else if (strlen($tambah) == 2) {
    $format = "B0" . $tambah;
} else {
    $format = "B" . $tambah;
}
?>

<section class="content-header">
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-home"></i> <b>Si Perpustakaan</b></a></li>
    </ol>
</section>

<section class="content">
    <div class="row"><div class="col-md-12">
        <div class="box box-info">
            <div class="box-header with-border"><h3 class="box-title">Tambah Buku</h3></div>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="box-body">
                    <div class="form-group">
                        <label>ID Buku</label>
                        <input type="text" name="id_buku" class="form-control" value="<?php echo $format; ?>" readonly/>
                    </div>

                    <div class="form-group">
                        <label>Judul Buku</label>
                        <input type="text" name="judul_buku" class="form-control" placeholder="Judul Buku" required>
                    </div>

                    <div class="form-group">
                        <label>Pengarang</label>
                        <input type="text" name="pengarang" class="form-control" placeholder="Nama Pengarang" required>
                    </div>

                    <div class="form-group">
                        <label>Penerbit</label>
                        <input type="text" name="penerbit" id="penerbit" class="form-control" placeholder="Penerbit" required>
                    </div>

                    <div class="form-group">
                        <label>Tahun Terbit</label>
                        <input type="number" name="th_terbit" class="form-control" placeholder="Tahun Terbit" required>
                    </div>
                </div>

                <div class="box-footer">
                    <input type="submit" name="Simpan" value="Simpan" class="btn btn-info">
                    <a href="?page=MyApp/data_buku" class="btn btn-warning">Batal</a>
                </div>
            </form>
        </div>
    </div></div>
</section>

<?php
if (isset($_POST['Simpan'])) {
    $id_buku = mysqli_real_escape_string($koneksi, $_POST['id_buku']);
    $judul_buku = mysqli_real_escape_string($koneksi, $_POST['judul_buku']);
    $pengarang = mysqli_real_escape_string($koneksi, $_POST['pengarang']);
    $penerbit = mysqli_real_escape_string($koneksi, $_POST['penerbit']);
    $th_terbit = mysqli_real_escape_string($koneksi, $_POST['th_terbit']);

    $sql_simpan = "INSERT INTO tb_buku (id_buku,judul_buku,pengarang,penerbit,th_terbit) VALUES (
        '$id_buku','$judul_buku','$pengarang','$penerbit','$th_terbit'
    )";

    $query_simpan = mysqli_query($koneksi, $sql_simpan);

    if ($query_simpan) {
        echo "<script>
            Swal.fire({title: 'Tambah Data Berhasil', icon: 'success'}).then(() => {
                window.location = 'index.php?page=MyApp/data_buku';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({title: 'Tambah Data Gagal', text: '". mysqli_error($koneksi) ."', icon: 'error'}).then(() => {
                window.location = 'index.php?page=MyApp/add_buku';
            });
        </script>";
    }
}
?>
