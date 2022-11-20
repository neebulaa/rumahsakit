<?php 
require_once "../../php/functions.php";
$keyword = $_GET['search'];


$current_table = 'tb_obat';


$datas = search($keyword, $current_table);
$total_datas = count($datas);

// pagination
$limit = 10;
$total_pages = (int) ceil(count($datas) / $limit);
$current_page = (int) ($_GET['page'] ?? 1);
$start = ($current_page - 1) * $limit;

$datas = searchWithLimit($keyword, $start, $limit, $current_table);
?>


<?php if(count($datas) > 0): ?>
<form action="" method="post" id="checked_form">
    <table class="table table-striped table-bordered caption-top">
        <caption>Saat ini <?= $total_datas?> obat.</caption>
        <tr>
            <th>
                <input type="checkbox" class="form-check-input mx-auto d-block" id="select-all-checks" style="width: 20px; height: 20px;">
            </th>
            <th>No</th>
            <th>Nama Obat</th>
            <th>Keterangan Obat</th>
        </tr>

        <?php $i = $start + 1; foreach($datas as $data): ?>
            <tr>
                <td>
                    <input type="checkbox" class="form-check-input checks mx-auto d-block" style="width: 20px; height: 20px;" name="<?= "select_single_$i"?>" value="<?= $data['id']?>">
                </td>
                <td><?= $i?></td>
                <td><?= $data['nama_obat']?></td>
                <td><?= $data['ket_obat']?></td>
            </tr>
        <?php $i++; endforeach; ?>
    </table>
</form>
<nav aria-label="Page navigation example">
    <ul class="pagination gap-0">
        <li class="page-item <?= $current_page == 1 ? 'disabled' : ''?>">
            <a class="page-link" href="?search=<?= $keyword?>&page=<?= $current_page - 1?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>

        <?php for($j = 0; $j < $total_pages; $j++): ?>
            <li class="page-item <?= $j + 1 == $current_page ? 'active' : '' ?>"><a class="page-link" href="?search=<?= $keyword?>&page=<?= $j + 1 ?>"><?= $j + 1?></a></li>
        <?php endfor; ?>
        
        <li class="page-item <?= $current_page == $total_pages ? 'disabled' : ''?>">
            <a class="page-link" href="?search=<?= $keyword?>&page=<?= $current_page + 1?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>
<?php else: ?>
    <h4 class="mt-3 mb-4">Saat ini tidak ada data.</h4>
<?php endif; ?>