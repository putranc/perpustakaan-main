<?php
include "inc/koneksi.php";

if (isset($_GET['kode'])) {
    $kode = mysqli_real_escape_string($koneksi, $_GET['kode']);
    $sql_hapus = "DELETE FROM tb_buku WHERE id_buku='$kode'";
    $query_hapus = mysqli_query($koneksi, $sql_hapus);

    if ($query_hapus) {
        echo "<script>
            Swal.fire({title: 'Hapus Data Berhasil', icon: 'success'}).then(() => {
                window.location = 'index.php?page=MyApp/data_buku';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({title: 'Hapus Data Gagal', text: '". mysqli_error($koneksi) ."', icon: 'error'}).then(() => {
                window.location = 'index.php?page=MyApp/data_buku';
            });
        </script>";
    }
}
?>
