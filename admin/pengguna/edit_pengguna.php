<?php
// Asumsi file koneksi (inc/koneksi.php) sudah di-include
include "inc/koneksi.php";

$data_cek = []; // Inisialisasi variabel data_cek
$kode_pengguna = '';

if (isset($_GET['kode'])) {
    $kode_pengguna = $_GET['kode'];
    
    // PERBAIKAN: Gunakan Prepared Statement untuk SELECT/Cek Data
    $stmt_cek = $koneksi->prepare("SELECT id_pengguna, nama_pengguna, username, level FROM tb_pengguna WHERE id_pengguna = ?");
    
    if ($stmt_cek) {
        $stmt_cek->bind_param("s", $kode_pengguna);
        $stmt_cek->execute();
        $result_cek = $stmt_cek->get_result();
        
        if ($result_cek->num_rows > 0) {
            $data_cek = $result_cek->fetch_assoc();
        } else {
            // Jika kode tidak ditemukan, arahkan kembali
            header("location: index.php?page=MyApp/data_pengguna");
            exit;
        }
        $stmt_cek->close();
    }
} else {
    // Jika tidak ada kode di URL, arahkan kembali
    header("location: index.php?page=MyApp/data_pengguna");
    exit;
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
                    <input type="hidden" name="id_pengguna" value="<?php echo htmlspecialchars($data_cek['id_pengguna']); ?>"/>
                    <input type="hidden" name="username_lama" value="<?php echo htmlspecialchars($data_cek['username']); ?>"/>


                    <div class="form-group">
                        <label>Nama Pengguna</label>
                        <input class="form-control" name="nama_pengguna" value="<?php echo htmlspecialchars($data_cek['nama_pengguna']); ?>" required maxlength="50"/>
                    </div>

                    <div class="form-group">
                        <label>Username</label>
                        <input class="form-control" name="username" value="<?php echo htmlspecialchars($data_cek['username']); ?>" required maxlength="30"/>
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
// ====================================================================
// PROSES UPDATE DATA (Diperbaiki: Prepared Statements & Validasi)
// ====================================================================

if (isset($_POST['Ubah'])) {
    
    // Ambil data
    $id_pengguna     = $_POST['id_pengguna'];
    $nama_pengguna   = $_POST['nama_pengguna'];
    $username        = $_POST['username'];
    $username_lama   = $_POST['username_lama'];
    $password_baru   = $_POST['password']; // Password tidak di-sanitasi, akan di-hash
    $level           = $_POST['level'];

    // 1. Validasi Server-Side (Defect 02/03)
    if (empty($nama_pengguna) || empty($username) || empty($level)) {
        echo "<script>
        Swal.fire({title: 'Data Tidak Lengkap',text: 'Nama Pengguna, Username, dan Level wajib diisi!',icon: 'warning',confirmButtonText: 'OK'
        }).then(() => {
            window.location = 'index.php?page=MyApp/edit_pengguna&kode=$id_pengguna';
        });
        </script>";
        exit;
    }

    // 2. Cek Duplikasi Username (Defect 01/03) - hanya jika username berubah
    $is_duplicate = false;
    if ($username != $username_lama) {
        // Gunakan Prepared Statement untuk cek duplikasi
        $stmt_check_dup = $koneksi->prepare("SELECT id_pengguna FROM tb_pengguna WHERE username = ? AND id_pengguna != ?");
        
        if ($stmt_check_dup) {
            $stmt_check_dup->bind_param("ss", $username, $id_pengguna);
            $stmt_check_dup->execute();
            $stmt_check_dup->store_result();
            if ($stmt_check_dup->num_rows > 0) {
                $is_duplicate = true;
            }
            $stmt_check_dup->close();
        }
    }

    if ($is_duplicate) {
        echo "<script>
        Swal.fire({title: 'Ubah Data Gagal',text: 'Username sudah digunakan oleh pengguna lain!',icon: 'error',confirmButtonText: 'OK'
        }).then(() => {
            window.location = 'index.php?page=MyApp/edit_pengguna&kode=$id_pengguna';
        });
        </script>";
    } else {
        // 3. Persiapkan Query UPDATE
        $query_ubah = false;
        
        if (!empty($password_baru)) {
            // Update dengan Password baru (MD5)
            $hashed_password = md5($password_baru);
            $stmt_ubah = $koneksi->prepare("UPDATE tb_pengguna SET nama_pengguna=?, username=?, password=?, level=? WHERE id_pengguna=?");
            if ($stmt_ubah) {
                $stmt_ubah->bind_param("sssss", $nama_pengguna, $username, $hashed_password, $level, $id_pengguna);
                $query_ubah = $stmt_ubah->execute();
                $stmt_ubah->close();
            }
        } else {
            // Update tanpa mengubah Password
            $stmt_ubah = $koneksi->prepare("UPDATE tb_pengguna SET nama_pengguna=?, username=?, level=? WHERE id_pengguna=?");
            if ($stmt_ubah) {
                $stmt_ubah->bind_param("ssss", $nama_pengguna, $username, $level, $id_pengguna);
                $query_ubah = $stmt_ubah->execute();
                $stmt_ubah->close();
            }
        }

        // 4. Feedback ke User
        if ($query_ubah) {
            echo "<script>
                Swal.fire({title: 'Ubah Data Berhasil', icon: 'success'}).then(() => {
                    window.location = 'index.php?page=MyApp/data_pengguna';
                });
            </script>";
        } else {
            // Tampilkan pesan error database hanya jika prepared statement berhasil tapi execute gagal
            $error_msg = $koneksi->error ? "Terjadi kesalahan database: ". $koneksi->error : "Terjadi kesalahan saat mengupdate data.";
            echo "<script>
                Swal.fire({title: 'Ubah Data Gagal', text: '$error_msg', icon: 'error'}).then(() => {
                    window.location = 'index.php?page=MyApp/data_pengguna';
                });
            </script>";
        }
    }
}
?>