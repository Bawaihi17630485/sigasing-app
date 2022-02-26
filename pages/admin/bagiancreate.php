<?php 
if (isset($_POST ['button_create'])) {

    $database = new Database();
    $db = $database->getConnection();

    $validateSql = "SELECT B.*, K.nama_lengkap nama_kepala_bagian, L.nama_lokasi nama_lokasi_bagian FROM bagian B
                                LEFT JOIN karyawan K ON B.karyawan_id = K.id
                                LEFT JOIN lokasi L ON B.lokasi_id = L.id 
                                WHERE nama_bagian = ?";
    $stmt = $db->prepare($validateSql);
    $stmt->bindParam(1, $_POST['nama_bagian']);
    $stmt->execute();
    if ($stmt->rowCount() > 0){
    ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
            <h5><i class="icon fas fa-ban"></i> Gagal</h5>
            Nama bagian sudah ada
        </div>
    <?php
    }else {
        $insertSql = "INSERT INTO bagian SET nama_bagian = ?, karyawan_id = ?, lokasi_id = ?";
        $stmt = $db->prepare($insertSql);
        $stmt->bindParam(1, $_POST['nama_bagian']);
        $stmt->bindParam(2, $_POST['nama_kepala_bagian']);
        $stmt->bindParam(3, $_POST['nama_lokasi_bagian']);
        if ($stmt->execute()){
            $_SESSION['hasil'] = true;
            $_SESSION['pesan'] = "Simpan Data Berhasil";
        }else {
            $_SESSION['hasil'] = false;
            $_SESSION['pesan'] = "Gagal Simpan Data";
    }
    echo "<meta http-equiv='refresh' content='0;url=?page=bagianread'>";
    }
}
?>
<!-- Content Header (Page header) -->
<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Tambah Data bagian</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="?page=home">Home</a></li>
                <li class="breadcrumb-item"><a href="?page=bagianread">bagian</a></li>
                <li class="breadcrumb-item active">Tambah Data</li>
            </ol>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
</section>
<!-- / content header -->

    <!-- Main content -->
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data bagian</h3> 
            </div>
        <div class="card-body">
                <form method="POST">
                    <div class="form-group">
                        <label for="nama_bagian">Nama bagian</label>
                        <input type="text" class="form-control" name="nama_bagian">        
                    </div>
                    <div class="form-group">
                        <label for="nama_kepala_bagian">Nama Kepala Bagian</label>
                        <select class="form-control" name="nama_kepala_bagian"> 
                            <option value="">-- Pilih Kepala Bagian --</option>
                            <?php
                                $database = new Database();
                                $db = $database->getConnection();
                                $selectSQLKaryawan = "SELECT * FROM karyawan";
                                $stmt_karyawan = $db->prepare($selectSQLKaryawan);
                                $stmt_karyawan->execute();

                                while ($row_karyawan = $stmt_karyawan->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value=\"" . $row_karyawan["id"] . "\">" . $row_karyawan["nama_lengkap"] . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nama_lokasi_bagian">Nama Lokasi Bagian</label>
                        <select class="form-control" name="nama_lokasi_bagian"> 
                            <option value="">-- Pilih Lokasi Bagian --</option>
                            <?php
                                $selectSQLlokasi = "SELECT * FROM lokasi";
                                $stmt_lokasi = $db->prepare($selectSQLlokasi);
                                $stmt_lokasi->execute();

                                while ($row_lokasi = $stmt_lokasi->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value=\"" . $row_lokasi["id"] . "\">" . $row_lokasi["nama_lokasi"] . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <a href="?page=bagianread" class="btn btn-danger btn-sm float-right">
                        <i class="fa fa-times"></i> Batal 
                    </a> 
                    <button type="submit" name="button_create" class="btn btn-success btn-sm float-right">
                        <i class="fa fa-save"></i> Simpan
                    </button>   
                </form> 
            </div>
            </div>
    </section>
    <!-- /.content -->

    <?php include "partials/scripts.php" ?>
    <script>
        $(function() {
            $('#mytable').DataTable()
        });
    </script>