<?php
include "inc/koneksi.php";

if (isset($_GET['kode'])) {
    $kode = mysqli_real_escape_string($koneksi, $_GET['kode']);
    $sql_cek = "SELECT * FROM tb_pengguna WHERE id_pengguna='$kode'";
    $query_cek = mysqli_query($koneksi, $sql_cek);
    $data_cek = mysqli_fetch_array($query_cek, MYSQLI_BOTH);
}
?>

<section class="content-header">
    <h1>Pengguna Sistem</h1>
</section>

<section class="content">
    <div class="row"><div class="col-md-12">
        <div class="box box-success">
            <div class="box-header with-border"><h3 class="box-title">Ubah Pengguna</h3></div>

            <form action="" method="post">
                <div class="box-body">
                    <input type="hidden" name="id_pengguna" value="<?php echo $data_cek['id_pengguna']; ?>"/>

                    <div class="form-group">
                        <label>Nama Pengguna</label>
                        <input class="form-control" name="nama_pengguna" value="<?php echo $data_cek['nama_pengguna']; ?>" />
                    </div>

                    <div class="form-group">
                        <label>Username</label>
                        <input class="form-control" name="username" value="<?php echo $data_cek['username']; ?>" />
                    </div>

                    <div class="form-group">
                        <label>Password (kosongkan jika tidak ingin mengubah)</label>
                        <input type="password" class="form-control" name="password" id="pass" value="" />
                        <div style="margin-top:8px;">
                            <input id="showPass" type="checkbox"> Lihat Password
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Level</label>
                        <select name="level" class="form-control" required>
                            <option value="">-- Pilih Level --</option>
                            <option value="Administrator" <?php if ($data_cek['level']=="Administrator") echo "selected"; ?>>Administrator</option>
                            <option value="Petugas" <?php if ($data_cek['level']=="Petugas") echo "selected"; ?>>Petugas</option>
                        </select>
                    </div>
                </div>

                <div class="box-footer">
                    <input type="submit" name="Ubah" value="Ubah" class="btn btn-success">
                    <a href="?page=MyApp/data_pengguna" class="btn btn-warning">Batal</a>
                </div>
            </form>
        </div>
    </div></div>
</section>

<script>
document.getElementById('showPass').addEventListener('change', function() {
    var p = document.getElementById('pass');
    if (this.checked) p.type = 'text'; else p.type = 'password';
});
</script>

<?php
if (isset($_POST['Ubah'])) {
    $id_pengguna = mysqli_real_escape_string($koneksi, $_POST['id_pengguna']);
    $nama_pengguna = mysqli_real_escape_string($koneksi, $_POST['nama_pengguna']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $level = mysqli_real_escape_string($koneksi, $_POST['level']);

    // jika password tidak kosong -> update, jika kosong -> jangan ubah password
    if (!empty($_POST['password'])) {
        $password = md5($_POST['password']);
        $sql_ubah = "UPDATE tb_pengguna SET nama_pengguna='$nama_pengguna', username='$username', password='$password', level='$level' WHERE id_pengguna='$id_pengguna'";
    } else {
        $sql_ubah = "UPDATE tb_pengguna SET nama_pengguna='$nama_pengguna', username='$username', level='$level' WHERE id_pengguna='$id_pengguna'";
    }

    $query_ubah = mysqli_query($koneksi, $sql_ubah);

    if ($query_ubah) {
        echo "<script>
            Swal.fire({title: 'Ubah Data Berhasil', icon: 'success'}).then(() => {
                window.location = 'index.php?page=MyApp/data_pengguna';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({title: 'Ubah Data Gagal', text: '". mysqli_error($koneksi) ."', icon: 'error'}).then(() => {
                window.location = 'index.php?page=MyApp/data_pengguna';
            });
        </script>";
    }
}
?>
