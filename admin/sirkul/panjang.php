<?php

if(isset($_GET['kode'])){
    $id_sk = $_GET['kode'];
    
    // Ambil tanggal kembali saat ini
    $sql_cek = "SELECT tgl_kembali FROM tb_sirkulasi WHERE id_sk='$id_sk'";
    $query_cek = mysqli_query($koneksi, $sql_cek);
    $data_cek = mysqli_fetch_array($query_cek,MYSQLI_BOTH);
    
    $tgl_kembali_lama = $data_cek['tgl_kembali'];
    $tgl_sekarang = date('Y-m-d');
    
    // Perbaikan Defect 3: Cek apakah sudah lewat jatuh tempo
    if (strtotime($tgl_sekarang) > strtotime($tgl_kembali_lama)) {
        // Gagal Perpanjang karena sudah lewat jatuh tempo
        echo "<script>
        Swal.fire({title: 'Perpanjang Gagal', text: 'Buku sudah melewati tanggal jatuh tempo ($tgl_kembali_lama). Silakan kembalikan buku terlebih dahulu.', icon: 'error', confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=data_sirkul';
            }
        })</script>";
        exit; // Hentikan eksekusi kode selanjutnya
    }

    // Jika belum jatuh tempo, lanjutkan proses perpanjangan
    // Tanggal pinjam yang baru adalah tanggal jatuh tempo lama (tgl_kembali_lama)
    $tgl_pp = $tgl_kembali_lama; 
    
    // Tanggal kembali yang baru adalah tgl_pp + 7 hari
    $tgl_kk = date('Y-m-d', strtotime('+7 days', strtotime($tgl_pp)));

    $sql_ubah = "UPDATE tb_sirkulasi SET
        tgl_pinjam='$tgl_pp',
        tgl_kembali='$tgl_kk'
        WHERE id_sk='$id_sk'";
    $query_ubah = mysqli_query($koneksi, $sql_ubah);

    if ($query_ubah) {
        echo "<script>
        Swal.fire({title: 'Perpanjang Berhasil',text: 'Tanggal kembali diperbarui menjadi $tgl_kk',icon: 'success',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=data_sirkul';
            }
        })</script>";
    }else{
        echo "<script>
        Swal.fire({title: 'Perpanjang Gagal',text: 'Terjadi kesalahan saat menyimpan data perpanjangan.',icon: 'error',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=data_sirkul';
            }
        })</script>";
    }
} else {
    // Handle jika parameter kode tidak ditemukan
    echo "<script>
    Swal.fire({title: 'Error',text: 'ID Sirkulasi tidak ditemukan.',icon: 'error',confirmButtonText: 'OK'
    }).then((result) => {
        if (result.value) {
            window.location = 'index.php?page=data_sirkul';
        }
    })</script>";
}
?>