<?php
include "inc/koneksi.php";

if (isset($_GET['kode'])) {
    $kode = mysqli_real_escape_string($koneksi, $_GET['kode']);

    // FIX Defect 04: Cek apakah buku sedang dipinjam di tabel tb_sirkulasi
    $sql_cek_pinjam = "SELECT id_buku FROM tb_sirkulasi WHERE id_buku='$kode' AND status='PIN'";
    $query_cek_pinjam = mysqli_query($koneksi, $sql_cek_pinjam);

    if (!$query_cek_pinjam) {
        // Handle error jika query gagal
        echo "<script>
            Swal.fire({title: 'Error Database', text: '". mysqli_error($koneksi) ."', icon: 'error'}).then(() => {
                window.location = 'index.php?page=MyApp/data_buku';
            });
        </script>";
        exit;
    }
    
    if (mysqli_num_rows($query_cek_pinjam) > 0) {
        // Buku sedang dipinjam ('PIN'), tidak boleh dihapus
        echo "<script>
            Swal.fire({title: 'Hapus Data Gagal', text: 'Buku tidak dapat dihapus karena sedang dalam status dipinjam!', icon: 'error'}).then(() => {
                window.location = 'index.php?page=MyApp/data_buku';
            });
        </script>";
    } else {
        // Buku aman untuk dihapus
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
}
?>