<?php
$id_sk = $_GET['kode'];

// Ambil tanggal pinjam dulu
$getTgl = $koneksi->query("SELECT tgl_pinjam FROM tb_sirkulasi WHERE id_sk='$id_sk'");
$dataTgl = $getTgl->fetch_assoc();

// Hitung tgl dikembalikan otomatis +7 hari dari tgl_pinjam
$tgl_dikembalikan = date('Y-m-d', strtotime($dataTgl['tgl_pinjam'] . ' +7 days'));

$update = $koneksi->query("
    UPDATE tb_sirkulasi
    SET status = 'KEM', tgl_dikembalikan = '$tgl_dikembalikan'
    WHERE id_sk = '$id_sk'
");

if ($update) {
    echo "<script>
        Swal.fire({title:'Buku berhasil dikembalikan',icon:'success',confirmButtonText:'OK'})
        .then(()=>{window.location='index.php?page=log_kembali';});
    </script>";
}
?>
