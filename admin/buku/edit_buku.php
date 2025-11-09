<?php
include "inc/koneksi.php";

if(isset($_GET['kode'])){
    $kode_buku = mysqli_real_escape_string($koneksi, $_GET['kode']);
    $sql_cek = "SELECT * FROM tb_buku WHERE id_buku='$kode_buku'";
    $query_cek = mysqli_query($koneksi, $sql_cek);
    $data_cek = mysqli_fetch_array($query_cek,MYSQLI_BOTH);
}
?>

<section class="content-header">
    <h1>
        Master Data
        <small>Data Buku</small>
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="index.php">
                <i class="fa fa-home"></i>
                <b>Si Perpustakaan</b>
            </a>
        </li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Ubah buku</h3>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="box-body">

                        <div class="form-group">
                            <label>Id Buku</label>
                            <input type='text' class="form-control" name="id_buku" value="<?php echo $data_cek['id_buku']; ?>"
                            readonly/>
                        </div>

                        <div class="form-group">
                            <label>Judul Buku</label>
                            <input type='text' class="form-control" name="judul_buku" value="<?php echo $data_cek['judul_buku']; ?>" required
                            />
                        </div>

                        <div class="form-group">
                            <label>Pengarang</label>
                            <input type='text' class="form-control" name="pengarang" value="<?php echo $data_cek['pengarang']; ?>" required
                            />
                        </div>

                        <div class="form-group">
                            <label>Penerbit</label>
                            <input class="form-control" name="penerbit" value="<?php echo $data_cek['penerbit']; ?>" required
                            />
                        </div>

                        <div class="form-group">
                            <label>Th Terbit</label>
                            <input type="number" class="form-control" name="th_terbit" value="<?php echo $data_cek['th_terbit']; ?>" required min="1000" max="9999" maxlength="4">
                        </div>

                    </div>
                    <div class="box-footer">
                        <input type="submit" name="Ubah" value="Ubah" class="btn btn-success">
                        <a href="?page=MyApp/data_buku" class="btn btn-warning">Batal</a>
                    </div>
                </form>
            </div>
            </section>

<?php

if (isset ($_POST['Ubah'])){
    $id_buku = mysqli_real_escape_string($koneksi, $_POST['id_buku']);
    $judul_buku = trim(mysqli_real_escape_string($koneksi, $_POST['judul_buku']));
    $pengarang = mysqli_real_escape_string($koneksi, $_POST['pengarang']);
    $penerbit = mysqli_real_escape_string($koneksi, $_POST['penerbit']);
    $th_terbit = mysqli_real_escape_string($koneksi, $_POST['th_terbit']);
    
    // FIX Defect 03: Validasi Judul Kosong
    if (empty($judul_buku)) {
        echo "<script>
            Swal.fire({title: 'Ubah Data Gagal', text: 'Judul buku tidak boleh kosong!', icon: 'error'}).then(() => {
                window.location = 'index.php?page=MyApp/edit_buku&kode=$id_buku';
            });
        </script>";
        exit;
    }
    
    // FIX Defect 02: Validasi Tahun Terbit
    $tahun_sekarang = date("Y");
    if (!ctype_digit($th_terbit) || strlen($th_terbit) != 4 || $th_terbit < 1000 || $th_terbit > $tahun_sekarang + 1) {
        echo "<script>
            Swal.fire({title: 'Ubah Data Gagal', text: 'Tahun Terbit harus 4 digit angka yang valid!', icon: 'error'}).then(() => {
                window.location = 'index.php?page=MyApp/edit_buku&kode=$id_buku';
            });
        </script>";
        exit;
    }

    // FIX Defect 01: Cek Duplikasi Judul Buku (kecuali buku itu sendiri)
    $cek_duplikasi = mysqli_query($koneksi, "SELECT judul_buku FROM tb_buku WHERE judul_buku = '$judul_buku' AND id_buku != '$id_buku'");
    if (mysqli_num_rows($cek_duplikasi) > 0) {
        echo "<script>
            Swal.fire({title: 'Ubah Data Gagal', text: 'Data buku dengan judul yang sama sudah ada!', icon: 'error'}).then(() => {
                window.location = 'index.php?page=MyApp/edit_buku&kode=$id_buku';
            });
        </script>";
        exit;
    }

    //mulai proses ubah
    $sql_ubah = "UPDATE tb_buku SET
        judul_buku='$judul_buku',
        pengarang='$pengarang',
        penerbit='$penerbit',
        th_terbit='$th_terbit'
        WHERE id_buku='$id_buku'";
    
    $query_ubah = mysqli_query($koneksi, $sql_ubah);

    if ($query_ubah) {
        echo "<script>
        Swal.fire({title: 'Ubah Data Berhasil',text: '',icon: 'success',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=MyApp/data_buku';
            }
        })</script>";
        }else{
        echo "<script>
        Swal.fire({title: 'Ubah Data Gagal',text: '". mysqli_error($koneksi) ."',icon: 'error',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=MyApp/data_buku';
            }
        })</script>";
    }
}
?>