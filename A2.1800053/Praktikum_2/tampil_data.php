<?php

if (isset($_POST['cari'])) {
    $cari = $_POST['cari'];
} else {
    $cari = "";
}

?>
<div class="row">
    <div class="col-md-12">
        <?php
        if (empty($_GET['alert'])) {
            echo "";
        } elseif ($_GET['alert'] == 1) { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong><i class="fas fa-check-circle"></i> Sukses!</strong> Data siswa berhasil disimpan.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php
        } elseif ($_GET['alert'] == 2) { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong><i class="fas fa-check-circle"></i> Sukses!</strong> Data siswa berhasil diubah.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php
        } elseif ($_GET['alert'] == 3) { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong><i class="fas fa-check-circle"></i> Sukses!</strong> Data siswa berhasil dihapus.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php
        } elseif ($_GET['alert'] == 4) { ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="fas fa-times-circle"></i> Gagal!</strong> NIS <b><?php echo $_GET['nis']; ?></b> sudah ada.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php
        }
        ?>
        <form class="mr-3" action="index.php" method="post">
            <div class="form-row">
                <div class="col-3">
                    <input type="text" class="form-control" name="cari" placeholder="Cari NIS atau Nama Siswa" value="<?php echo $cari; ?>">
                </div>

                <div class="col-8">
                    <button type="submit" class="btn btn-info" id="button-cari">Cari</button>
                </div>

                <div class="col-1">
                    <a class="btn btn-info" href="?page=tambah" id="button-tambah" role="button"><i class="fas fa-plus"></i> Tambah</a>
                </div>
            </div>
        </form>

        <br>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Foto</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Tempat, Tanggal Lahir</th>
                        <th>Jenis Kelamin</th>
                        <th>Agama</th>
                        <th>Alamat</th>
                        <th>No HP</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <?php

                    $batas = 5;
                    if (isset($cari)) {
                        $query_jumlah = mysqli_query($db, "SELECT count(nis) as jumlah FROM tbl_siswa WHERE nis LIKE '%$cari%' OR nama LIKE '%$cari%'") or die('Ada kesalahan pada query siswa: ' . mysqli_error($db));
                    } else {
                        $query = mysqli_query($db, "SELECT count(nis) as jumlah FROM tbl_siswa") or die('Ada kesalahan pada query siswa: ' . mysqli_error($db));
                    }

                    $data_jumlah = mysqli_fetch_assoc($query_jumlah);
                    $jumlah      = $data_jumlah['jumlah'];
                    $halaman     = ceil($jumlah / $batas);
                    $page        = (isset($_GET['hal'])) ? (int) $_GET['hal'] : 1;
                    $mulai       = ($page - 1) * $batas;

                    $no = $mulai + 1;

                    if (isset($cari)) {
                        $query = mysqli_query($db, "SELECT * FROM tbl_siswa WHERE nis LIKE '%$cari%' or nama LIKE '%$cari%' ORDER BY nis DESC LIMIT $mulai, $batas") or die('Ada kesalahan pada query siswa: ' . mysqli_error($db));
                    } else {
                        $query = mysqli_query($db, "SELECT * FROM tbl_siswa ORDER BY nis DESC LIMIT $mulai, $batas") or die('Ada kesalahan pada query siswa: ' . mysqli_error($db));
                    }

                    if($jumlah>0){
                    while ($data = mysqli_fetch_assoc($query)) { ?>
                        <tr>
                            <td width="30" class="center"><?php echo $no; ?></td>
                            <td width="80"><img class="foto-thumbnail" src='foto/<?php echo $data['foto']; ?>' alt="Foto Siswa"></td>
                            <td width="80" class="center"><?php echo $data['nis']; ?></td>
                            <td width="180"><?php echo $data['nama']; ?></td>
                            <td width="200"><?php echo $data['tempat_lahir']; ?>, <?php echo $data['tanggal_lahir'] ?></td>
                            <td width="140"><?php echo $data['jenis_kelamin']; ?></td>
                            <td width="100"><?php echo $data['agama']; ?></td>
                            <td width="180"><?php echo $data['alamat']; ?></td>
                            <td width="70" class="center"><?php echo $data['no_hp']; ?></td>

                            <td>
                                <a title="Ubah" class="button-ubah btn btn-outline-info" href="?page=ubah&nis=<?php echo $data['nis']; ?>"><i class="fas fa-edit"></i></a>
                                <a title="Hapus" class="btn btn-outline-danger" href="proses_hapus.php?nis=<?php echo $data['nis'] ?>" onclick="return confirm('Apakah anda yakin akan menghapus data ini?')"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php
                        $no++;
                    }
                 }else{ ?>
                 <tr>
                     <td colspan="10" class="text-center">Data tidak ditemukan</td>
                 </tr>
                 <?php } ?>
                </tbody>
            </table>
        </div>

        <?php

        if (empty($_GET['hal'])) {
            $halaman_aktif = '1';
        } else {
            $halaman_aktif = $_GET['hal'];
        }
        ?>
        <div class="row">
            <div class="col">
                <a>
                    Halaman <?php echo $halaman_aktif; ?> dari <?php echo $halaman; ?> - Total <?php echo $jumlah; ?> data
                </a>
            </div>
            <div class="col">
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-end">
                        <?php
                        if ($halaman_aktif <= '1') { ?>
                            <li class="page-item disabled"><span class="page-link">Sebelumnya</span></li>
                        <?php
                        } else { ?>
                            <li class="page-item"><a class="page-link" href="?hal=<?php echo $page - 1 ?>">Sebelumnya</a></li>
                        <?php } ?>

                        <?php
                        for ($x = 1; $x <= $halaman; $x++) { ?>
                            <li class="page-item"><a class="page-link" href="?hal=<?php echo $x ?>"><?php echo $x ?></a></li>
                        <?php } ?>

                        <?php

                        if ($halaman_aktif >= $halaman) { ?>
                            <li class="page-item disabled"><span class="page-link">Selanjutnya</span></li>
                        <?php
                        } else { ?>
                            <li class="page-item"><a class="page-link" href="?hal=<?php echo $page + 1 ?>">Selanjutnya</a></li>
                        <?php } ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>