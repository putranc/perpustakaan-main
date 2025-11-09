<?php 
// Definisikan batas maksimum pinjam (misalnya: 3 buku per anggota)
$batas_pinjam_maksimal = 3;

// kode otomatis
$carikode = mysqli_query($koneksi, "SELECT id_sk FROM tb_sirkulasi ORDER BY id_sk DESC");
$datakode = mysqli_fetch_array($carikode);
$kode = $datakode['id_sk'];
$urut = substr($kode, 1, 3);
$tambah = (int)$urut + 1; // <-- Ini seharusnya baris 10, atau di dekatnya

if (strlen($tambah) == 1) {
    $format = "S"."00".$tambah;
} else if (strlen($tambah) == 2) {
    $format = "S"."0".$tambah;
} else {
    $format = "S".$tambah;
}
?>

<section class="content-header">
    <h1>Sirkulasi <small>Buku</small></h1>
    <ol class="breadcrumb">
        <li>
            <a href="index.php">
                <i class="fa fa-home"></i> <b>Si Perpustakaan</b>
            </a>
        </li>
    </ol>
</section>

<section class="content">
<div class="row">
<div class="col-md-12">
<div class="box box-info">
<div class="box-header with-border">
    <h3 class="box-title">Tambah Peminjaman</h3>
</div>

<form action="" method="post" enctype="multipart/form-data">
<div class="box-body">
    <div class="form-group">
        <label>ID Sirkulasi</label>
        <input type="text" name="id_sk" value="<?php echo $format; ?>" class="form-control" readonly>
    </div>

    <div class="form-group">
        <label>Nama Peminjam</label>
        <select name="id_anggota" class="form-control select2" style="width:100%;" required>
            <option value="">-- Pilih Anggota --</option>
            <?php
            $hasil = mysqli_query($koneksi, "SELECT * FROM tb_anggota");
            while ($row = mysqli_fetch_array($hasil)) {
                echo "<option value='$row[id_anggota]'>$row[id_anggota] - $row[nama]</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label>Buku</label>
        <select name="id_buku" class="form-control select2" style="width:100%;" required>
            <option value="">-- Pilih Buku --</option>
            <?php
            $hasil = mysqli_query($koneksi, "SELECT * FROM tb_buku");
            while ($row = mysqli_fetch_array($hasil)) {
                echo "<option value='$row[id_buku]'>$row[id_buku] - $row[judul_buku]</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label>Tanggal Pinjam</label>
        <input type="date" name="tgl_pinjam" class="form-control" required max="<?php echo date('Y-m-d'); ?>">
    </div>
</div>

<div class="box-footer">
    <input type="submit" name="Simpan" value="Simpan" class="btn btn-info">
    <a href="?page=data_sirkul" class="btn btn-warning">Batal</a>
</div>
</form>
</div>
</div>
</div>
</section>

<?php
if (isset($_POST['Simpan'])) {
    $tgl_p = $_POST['tgl_pinjam'];
    $id_anggota = $_POST['id_anggota'];
    $tgl_sekarang = date('Y-m-d');
    $error = '';

    // --- Perbaikan Defect 1: Validasi Tanggal Pinjam ---
    if (strtotime($tgl_p) > strtotime($tgl_sekarang)) {
        $error = 'Tanggal Pinjam tidak boleh lebih dari tanggal hari ini.';
    }
    // Logika ini memastikan hanya tanggal hari ini yang valid, mencegah masa lalu
    if (strtotime($tgl_p) < strtotime($tgl_sekarang) && $tgl_p != $tgl_sekarang) {
        $error = 'Tanggal Pinjam tidak boleh tanggal masa lalu.';
    }

    // --- Perbaikan Defect 2: Validasi Batas Pinjam ---
    if (empty($error)) {
        $sql_hitung = "SELECT COUNT(id_sk) AS jumlah_pinjam FROM tb_sirkulasi WHERE id_anggota='$id_anggota' AND status='PIN'";
        $query_hitung = mysqli_query($koneksi, $sql_hitung);
        $data_hitung = mysqli_fetch_assoc($query_hitung);
        $jumlah_pinjam_saat_ini = $data_hitung['jumlah_pinjam'];

        if ($jumlah_pinjam_saat_ini >= $batas_pinjam_maksimal) {
            $error = "Anggota ini sudah meminjam $jumlah_pinjam_saat_ini buku. Batas maksimum pinjam adalah $batas_pinjam_maksimal buku.";
        }
    }

    if (!empty($error)) {
        // Tampilkan pesan error jika ada validasi yang gagal
        echo "<script>
        Swal.fire({title: 'Tambah Data Gagal', text: '$error', icon: 'error', confirmButtonText: 'OK'}).then((r)=>{if(r.value){window.location='index.php?page=add_sirkul';}})
        </script>";
    } else {
        // Logika simpan jika semua validasi berhasil
        $tgl_k = date('Y-m-d', strtotime('+7 days', strtotime($tgl_p)));

        // Menggunakan mysqli_query biasa karena mysqli_multi_query tidak selalu didukung
        // Atau pisahkan menjadi dua query terpisah
        
        $sql_simpan_sirkulasi = "INSERT INTO tb_sirkulasi (id_sk, id_buku, id_anggota, tgl_pinjam, status, tgl_kembali)
                            VALUES ('".$_POST['id_sk']."', '".$_POST['id_buku']."', '".$_POST['id_anggota']."',
                                    '".$_POST['tgl_pinjam']."', 'PIN', '$tgl_k')";
                                    
        $sql_simpan_log = "INSERT INTO log_pinjam (id_buku, id_anggota, tgl_pinjam)
                           VALUES ('".$_POST['id_buku']."', '".$_POST['id_anggota']."', '".$_POST['tgl_pinjam']."')";

        $query_sirkulasi = mysqli_query($koneksi, $sql_simpan_sirkulasi);
        $query_log = mysqli_query($koneksi, $sql_simpan_log);

        if ($query_sirkulasi && $query_log) {
            echo "<script>
            Swal.fire({title: 'Tambah Data Berhasil', icon: 'success', confirmButtonText: 'OK'}).then((r)=>{if(r.value){window.location='index.php?page=data_sirkul';}})
            </script>";
        } else {
            echo "<script>
            Swal.fire({title: 'Tambah Data Gagal', text: 'Error: ".mysqli_error($koneksi)."', icon: 'error', confirmButtonText: 'OK'}).then((r)=>{if(r.value){window.location='index.php?page=add_sirkul';}})
            </script>";
        }
    }
}
?>