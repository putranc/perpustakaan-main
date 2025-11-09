<?php
// ====================================================================
// Bagian PHP: Fungsi dan Pengambilan Data Statistik
// ====================================================================

// Diasumsikan variabel $koneksi (mysqli object) sudah tersedia di sini.
// Misalnya: require_once 'koneksi.php';

/**
 * Fungsi pembantu untuk menjalankan query COUNT dan menangani error.
 * @param object $koneksi Objek koneksi mysqli.
 * @param string $query Query SQL untuk COUNT.
 * @return int Jumlah hasil count, atau 0 jika gagal.
 */
function getCount($koneksi, $query) {
    // Pengecekan koneksi dasar (jika $koneksi tidak didefinisikan/null)
    if (!isset($koneksi) || $koneksi === null) {
        error_log("Kesalahan: Variabel koneksi database belum didefinisikan.");
        return 0;
    }

    $sql = $koneksi->query($query);

    if ($sql === false) {
        // Log kesalahan query
        error_log("Kesalahan Query: " . $koneksi->error . "\nQuery: " . $query);
        return 0;
    }

    $data = $sql->fetch_assoc();
    
    // Mengambil nilai COUNT (nilai pertama dari array asosiatif)
    // Menggunakan operator null coalescing (??) untuk fallback ke 0 (jika PHP >= 7.0)
    return reset($data) ?? 0;
}

// --- Hitung total data buku ---
$buku = getCount($koneksi, "SELECT COUNT(id_buku) AS buku FROM tb_buku");

// --- Hitung total data anggota ---
$agt = getCount($koneksi, "SELECT COUNT(id_anggota) AS agt FROM tb_anggota");

// --- Hitung jumlah sirkulasi yang sedang dipinjam (status='PIN') ---
$pin = getCount($koneksi, "SELECT COUNT(id_sk) AS pin FROM tb_sirkulasi WHERE status='PIN'");

// --- Hitung jumlah sirkulasi yang sudah dikembalikan (status='KEM') ---
$kem = getCount($koneksi, "SELECT COUNT(id_sk) AS kem FROM tb_sirkulasi WHERE status='KEM'");
?>

<section class="content-header">
    <h1>Dashboard Administrator</h1>
</section>

<section class="content">
    <div class="row">

                <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-blue">
                <div class="inner">
                    <h4><?= $buku; ?></h4>
                    <p>**Buku**</p>
                </div>
                <div class="icon">
                    <i class="ion ion-ios-book"></i>
                </div>
                <a href="?page=MyApp/data_buku" class="small-box-footer">
                    More info <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

                <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h4><?= $agt; ?></h4>
                    <p>**Anggota**</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="?page=MyApp/data_agt" class="small-box-footer">
                    More info <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

                <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
                <div class="inner">
                    <h4><?= $pin; ?></h4>
                    <p>**Sirkulasi Sedang Berjalan**</p>
                </div>
                <div class="icon">
                    <i class="ion ion-refresh"></i>
                </div>
                <a href="?page=data_sirkul" class="small-box-footer">
                    More info <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

                <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-red">
                <div class="inner">
                    <h4><?= $kem; ?></h4>
                    <p>**Laporan Sirkulasi (Kembali)**</p>
                </div>
                <div class="icon">
                    <i class="ion ion-clipboard"></i>
                </div>
                <a href="?page=log_kembali" class="small-box-footer">
                    More info <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

    </div>
</section>