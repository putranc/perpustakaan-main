<?php
$id_sk = $_GET['kode'];

// Ambil data sirkulasi yang akan dikembalikan
$getSirkulasi = $koneksi->query("SELECT id_buku, id_anggota, tgl_pinjam FROM tb_sirkulasi WHERE id_sk='$id_sk'");
$dataSirkulasi = $getSirkulasi->fetch_assoc();

if (!$dataSirkulasi) {
    // Handle jika ID Sirkulasi tidak ditemukan
    echo "<script>
        Swal.fire({title: 'Pengembalian Gagal',text:'Data Sirkulasi tidak ditemukan.',icon:'error',confirmButtonText:'OK'})
        .then(()=>{window.location='index.php?page=data_sirkul';});
    </script>";
    exit;
}

// Tanggal hari ini digunakan sebagai tanggal dikembalikan yang sebenarnya
$tgl_dikembalikan_sebenarnya = date('Y-m-d'); 

// Update status sirkulasi menjadi 'KEM' (Kembali) dan simpan tanggal dikembalikan
$update = $koneksi->query("
    UPDATE tb_sirkulasi
    SET status = 'KEM', tgl_dikembalikan = '$tgl_dikembalikan_sebenarnya'
    WHERE id_sk = '$id_sk'
");
// Baris 22 seharusnya berada di sekitar penutup query di atas atau baris berikutnya

if ($update) {
    // Tambahkan logika untuk menambah stok buku yang dikembalikan (jika ada)
    // Walaupun tidak ada di kode yang Anda berikan, ini adalah langkah penting setelah pengembalian.
    // $koneksi->query("UPDATE tb_buku SET stok_buku = stok_buku + 1 WHERE id_buku = '{$dataSirkulasi['id_buku']}'");
    
    echo "<script>
        Swal.fire({title:'Buku berhasil dikembalikan',icon:'success',confirmButtonText:'OK'})
        .then(()=>{window.location='index.php?page=log_kembali';});
    </script>";
} else {
    echo "<script>
        Swal.fire({title:'Pengembalian Gagal',text:'Gagal mengupdate status sirkulasi.',icon:'error',confirmButtonText:'OK'})
        .then(()=>{window.location='index.php?page=data_sirkul';});
    </script>";
}
?>