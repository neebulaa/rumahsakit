<?php 
$GLOBALS['title'] = 'EHealt | Tabel Dokter';
require_once "../functions.php";

EnsureUserAuth($conn, "php/dokter");

$datas = query("SELECT * FROM `tb_dokter`");
$total_datas = count($datas);

$limit = 10;
$current_page = (int) ($_GET['page'] ?? 1);
$start = ($current_page - 1) * $limit;
$total_pages = (int) ceil(count($datas) / $limit);

if(isset($_GET['search'])){
    $keyword = $_GET['search'];
    $datas = search($keyword, 'tb_dokter');
    $total_pages = (int) ceil(count($datas) / $limit);
    $datas = searchWithLimit($keyword, $start, $limit, 'tb_dokter');
}else{
    $datas = query("SELECT * FROM `tb_dokter` LIMIT $start, $limit");
}




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
                    <input type="text" name="search-value" id="search-input" class="form-control" data-table="tb_dokter">
                    <button type="submit" name="search-btn" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
            </form>
            <a href="./tambah.php" class="btn btn-outline-primary">Tambah</a>
        </div>

        <?php if(isset($_SESSION['process-success'])): ?>
            <div class="alert alert-success mt-4" role="alert" style="max-width: 480px;">
                <?= $_SESSION['process-success']?>

                <?php unset($_SESSION['process-success']); ?>
            </div>
        <?php endif; ?>


        <?php if(isset($_SESSION['process-failed'])): ?>
            <div class="alert alert-danger mt-4" role="alert" style="max-width: 480px;">
                <?= $_SESSION['process-failed']?>

                <?php unset($_SESSION['process-failed']); ?>
            </div>
        <?php endif; ?>


        <?php if(count($datas) > 0): ?>
        <div class="table-responsive" id="table-element">
            <form action="" method="post" id="checked_form">
                <table class="table table-striped table-bordered caption-top">
                    <caption>Saat ini <?= $total_datas?> dokter.</caption>
                    <tr>
                        <th>
                            <input type="checkbox" class="form-check-input mx-auto d-block" id="select-all-checks" style="width: 20px; height: 20px;">
                        </th>
                        <th>No</th>
                        <th>Nama Dokter</th>
                        <th>Spesialis</th>
                        <th>Alamat</th>
                        <th>No Telp</th>
                    </tr>

                    <?php $i = $start + 1; foreach($datas as $data): ?>
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input checks mx-auto d-block" style="width: 20px; height: 20px;" name="<?= "select_single_$i"?>" value="<?= $data['id']?>">
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
            <nav aria-label="Page navigation example">
                <ul class="pagination gap-0">
                    <li class="page-item <?= $current_page == 1 ? 'disabled' : ''?>">
                        <a class="page-link" href="?<?= isset($keyword) ? "search=$keyword&" : ''?>page=<?= $current_page - 1?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>

                    <?php for($j = 0; $j < $total_pages; $j++): ?>
                            <li class="page-item <?= $j + 1 == $current_page ? 'active' : '' ?>"><a class="page-link" href="?<?= isset($keyword) ? "search=$keyword&" : ''?>page=<?= $j + 1 ?>"><?= $j + 1?></a></li>
                    <?php endfor; ?>
                    
                    <li class="page-item <?= $current_page == $total_pages ? 'disabled' : ''?>">
                        <a class="page-link" href="?<?= isset($keyword) ? "search=$keyword&" : ''?>page=<?= $current_page + 1?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-warning edit">Edit</button>
            <button class="btn btn-danger hapus">Hapus</button>
        </div>

        <?php else: ?>
            <h4 class="mt-3 mb-4">Saat ini tidak ada data.</h4>
        <?php endif; ?>
    </div>
</div>

<script src="<?= "$base_url/assets/js/checks.js"?>"></script>
<script src="<?= "$base_url/assets/js/ajax.js"?>"></script>
<?php require_once "../partials/footer.php" ?>