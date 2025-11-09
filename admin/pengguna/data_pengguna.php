<section class="content-header">
    <h1>
        Pengguna Sistem
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="index.php">
                <i class="fa fa-home"></i>
                <b>Si Tabsis</b>
            </a>
        </li>
    </ol>
</section>

<section class="content">
    <div class="box box-primary">
        <div class="box-header">
            <a href="?page=MyApp/add_pengguna" class="btn btn-primary">
                <i class="glyphicon glyphicon-plus"></i> Tambah Data</a>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Level</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        // ASUMSI: $koneksi (objek mysqli) sudah didefinisikan/di-include
                        $no = 1;
                        
                        // Gunakan metode prepare jika ada variabel, namun untuk SELECT * Sederhana, query() cukup.
                        $sql = $koneksi->query("SELECT * FROM tb_pengguna");
                        
                        // Tambahkan pengecekan error untuk query SELECT
                        if ($sql === FALSE) {
                            echo "<tr><td colspan='5'>Error saat mengambil data: " . $koneksi->error . "</td></tr>";
                        } else {
                            while ($data = $sql->fetch_assoc()) {
                        ?>

                        <tr>
                            <td>
                                <?php echo $no++; ?>
                            </td>
                            <td>
                                <?php echo $data['nama_pengguna']; ?>
                            </td>
                            <td>
                                <?php echo $data['username']; ?>
                            </td>
                            <td>
                                <?php echo $data['level']; ?>
                            </td>
                            <td>
                                <a href="?page=MyApp/edit_pengguna&kode=<?php echo $data['id_pengguna']; ?>"
                                    title="Ubah" class="btn btn-success btn-sm">
                                    <i class="glyphicon glyphicon-edit"></i>
                                </a>
                                <a href="?page=MyApp/data_pengguna&kode=<?php echo $data['id_pengguna']; ?>"
                                    onclick="return confirm('Apakah anda yakin hapus data ini ?')" title="Hapus" class="btn btn-danger btn-sm">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php
                            }
                            $sql->free(); // Membebaskan hasil query
                        }
                        ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</section>

<?php
// Pastikan kode ini berada di akhir file
if(isset($_GET['kode'])){
    $kode_hapus = $_GET['kode']; // Ambil kode dari URL

    // PERBAIKAN KRITIS: Menggunakan Prepared Statement untuk DELETE
    $stmt = $koneksi->prepare("DELETE FROM tb_pengguna WHERE id_pengguna = ?");
    
    // Periksa apakah prepared statement berhasil
    if ($stmt === false) {
        // Handle error jika prepare gagal
        $query_hapus = false;
        error_log("Gagal menyiapkan statement: " . $koneksi->error);
    } else {
        // Bind parameter: "s" berarti string, karena id_pengguna diasumsikan bertipe string/varchar
        $stmt->bind_param("s", $kode_hapus);
        
        // Eksekusi statement
        $query_hapus = $stmt->execute();
        
        // Tutup statement
        $stmt->close();
    }


    if ($query_hapus) {
        // Jika penghapusan berhasil
        echo "<script>
        Swal.fire({title: 'Hapus Data Berhasil',text: '',icon: 'success',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=MyApp/data_pengguna';
            }
        })</script>";
        }else{
        // Jika penghapusan gagal
        echo "<script>
        Swal.fire({title: 'Hapus Data Gagal',text: 'Terjadi kesalahan saat menghapus data.',icon: 'error',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=MyApp/data_pengguna';
            }
        })</script>";
        }
}
?>