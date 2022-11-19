<?php 
$GLOBALS['title'] = 'EHealt | Tabel Dokter';
require_once "../functions.php";

$datas = query("SELECT * FROM `tb_dokter`");

?>

<?php require_once "../partials/header.php" ?>

<div class="d-flex">
    <?php require_once "../partials/sidebar.php" ?>
    <div class="container-fluid py-5 mx-5" style="overflow-y: auto; max-height: 100vh;">
        <h1>Data Dokter</h1>
        
        <p class="text-muted">Table managemen dokter EHealth</p>
        <div class="custom-underline w-100"></div>

        <div class="d-flex align-items-center justify-content-between mb-3" style="margin-top: 4rem;">
            <!-- search -->
            <form action="" method="post" style="width: 100%; max-width: 480px;">
                <div class="input-group">
                    <input type="text" name="search-value" id="search" class="form-control">
                    <button type="submit" name="search-btn" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
            </form>
            <a href="./tambah.php" class="btn btn-outline-primary">Tambah</a>
        </div>

        <?php if(count($datas) > 0): ?>
        <div class="table-responsive">
            <form action="" method="get" id="checked_form">
                <table class="table table-striped table-bordered">
                    <tr>
                        <th>
                            <input type="checkbox" class="form-check-input" id="select-all-checks" style="width: 20px; height: 20px;">
                        </th>
                        <th>No</th>
                        <th>Nama Dokter</th>
                        <th>Spesialis</th>
                        <th>Alamat</th>
                        <th>No Telp</th>
                    </tr>

                    <?php $i = 1; foreach($datas as $data): ?>
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input checks" style="width: 20px; height: 20px;" name="<?= "select_single_$i"?>" value="<?= $data['id']?>">
                            </td>
                            <td><?= $i?></td>
                            <td><?= $data['nama_dokter']?></td>
                            <td><?= $data['spesialis']?></td>
                            <td><?= $data['alamat']?></td>
                            <td><?= $data['no_telp']?></td>
                        </tr>
                    <?php $i++; endforeach; ?>
                </table>
            </form>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-warning edit">Edit</button>
            <button class="btn btn-danger hapus">Hapus</button>
        </div>

        <?php else: ?>
            <h4>Saat ini tidak ada data.</h4>
        <?php endif; ?>
    </div>
</div>

<script src="<?= "$base_url/assets/js/checks.js"?>"></script>
<?php require_once "../partials/footer.php" ?>