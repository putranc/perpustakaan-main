<?php
// Pastikan inc/koneksi.php sudah di-include
include "inc/koneksi.php";

// Logika Kode Otomatis ID Buku (B001, B002, dst.)
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
                        <input type="number" name="th_terbit" class="form-control" placeholder="Tahun Terbit" required min="1000" max="9999" maxlength="4">
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
    $judul_buku = trim(mysqli_real_escape_string($koneksi, $_POST['judul_buku']));
    $pengarang = mysqli_real_escape_string($koneksi, $_POST['pengarang']);
    $penerbit = mysqli_real_escape_string($koneksi, $_POST['penerbit']);
    $th_terbit = mysqli_real_escape_string($koneksi, $_POST['th_terbit']);
    
    // FIX Defect 03: Validasi Judul Kosong
    if (empty($judul_buku)) {
        echo "<script>
            Swal.fire({title: 'Tambah Data Gagal', text: 'Judul buku tidak boleh kosong!', icon: 'error'}).then(() => {
                window.location = 'index.php?page=MyApp/add_buku';
            });
        </script>";
        exit;
    }

    // FIX Defect 02: Validasi Tahun Terbit (4 digit, rentang wajar)
    $tahun_sekarang = date("Y");
    if (!ctype_digit($th_terbit) || strlen($th_terbit) != 4 || $th_terbit < 1000 || $th_terbit > $tahun_sekarang + 1) {
        echo "<script>
            Swal.fire({title: 'Tambah Data Gagal', text: 'Tahun Terbit harus 4 digit angka yang valid!', icon: 'error'}).then(() => {
                window.location = 'index.php?page=MyApp/add_buku';
            });
        </script>";
        exit;
    }

    // FIX Defect 01: Cek Duplikasi Judul Buku
    $cek_duplikasi = mysqli_query($koneksi, "SELECT judul_buku FROM tb_buku WHERE judul_buku = '$judul_buku'");
    if (mysqli_num_rows($cek_duplikasi) > 0) {
        echo "<script>
            Swal.fire({title: 'Tambah Data Gagal', text: 'Data buku dengan judul yang sama sudah ada!', icon: 'error'}).then(() => {
                window.location = 'index.php?page=MyApp/add_buku';
            });
        </script>";
        exit;
    }

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