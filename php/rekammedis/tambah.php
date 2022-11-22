<?php 
$GLOBALS['title'] = "EHealth | Tambah Rekam Medis";
require_once "../functions.php";
EnsureUserAuth($conn, 'php/rekammedis/tambah.php');

$current_table = 'tb_rekammedis';
$form_count = 1;


$tbRelation = $tableRelations[$current_table] ?? false;

if($tbRelation){
    $tbsToRelate = array_keys($tbRelation);
    $tbsToRelate[] = 'tb_obat';

    $dataToShow = [];
    foreach($tbsToRelate as $tIdx => $tableToRelate){
        [$tbId] = getTableFields($tableToRelate);
        // nama_dokter, nama_pasien, nama_poliklinik
        $_nama = explode('tb_', $tableToRelate)[1];

        $data = query("SELECT `$tableToRelate`.`$tbId`, `$tableToRelate`.`nama_$_nama` FROM `$tableToRelate`");
        $dataToShow[$tableToRelate] = $data;
    }

}




if($_SERVER['REQUEST_METHOD'] === "POST"){
    if(isset($_POST['create_form'])){
        $form_count = (int) $_POST['form_count'];
    }

    if(isset($_POST['add'])){
        // format data
        $form_count = $_POST['add'];
        $old = $_POST;
        // var_dump($_POST['id_obat--1']);
        // die();
        $formatted_data = formatMultipleFormData($_POST, $form_count);
        $result = addRekamMedis($formatted_data, $current_table);

        if($result instanceof W_ErrorValidator){
            $errorCredentials = $result->getErrors()['forms_error'];
            $mergeErrorCredentials = w_validator_errors_merge($errorCredentials);
            $errorCredentials = $mergeErrorCredentials['errors'];
        }else{
            if($result->status == 'success'){
                $_SESSION['process-success'] = $result->message;
                header('Location: ./index.php');
                exit;
            }else{
                $error_process = $result->message;
            }
        }
    }
}



?>

<?php require_once "../partials/header.php" ?>
<div class="d-flex">
    <?php require_once "../partials/sidebar.php" ?>
    <div class="container-fluid py-5 mx-5" style="overflow-y: auto; max-height: 100vh;">
        <h1 class="align-items-center d-flex gap-3">
            <a href="./index.php"><i class="fa-solid fa-arrow-left fs-3"></i></a>
            Tambah Data baru
        </h1>

        <p class="text-muted">Tambah rekam medis EHealth baru.</p>

        <div class="custom-underline w-100"></div>
        <div class="row">
            <div class="col-6">
                <form action="" method="post" class="mt-4">
                    <label for="form_count">Buat berapa form: </label>
                    <div class="input-group">
                        <input type="number" class="form-control" name="form_count" min="1" max="9" pattern="[0-9]" value="1">
                        <button type="submit" name="create_form" class="btn btn-secondary">Buat form</button>
                    </div>
                </form>
            </div>
        </div>

        <?php if(isset($error_process)): ?>
            <div class="alert alert-danger mt-4" role="alert">
                <?= $error_process?>
            </div>
        <?php endif; ?>


        <form action="" method="post" class="mt-5">
            <div class="d-flex gap-4 flex-wrap">

                <?php for($i = 1; $i <= $form_count; $i++): ?>
                <div class="form-group p-4 position-relative" style="border: 1px solid #999; width: 100%; max-width: 500px;" >
                    <h5 class="position-absolute text-primary form-counter" style="opacity: .9"><?= $i?></h5>

                    <div class="form-group mb-3">
                        <label for="id_pasien--<?= $i?>" class="form-label text-muted fs-6 mb-1">Pasien</label>
                    
                        <select class="form-select" name="id_pasien--<?= $i?>" id="id_pasien--<?= $i?>">
                            <?php foreach($dataToShow['tb_pasien'] as $opt): ?>
                            <option value="<?= $opt['id']?>" <?= ($old["id_pasien--$i"] ?? "") === $opt['id'] ? 'selected' : ''?>><?= $opt['nama_pasien']?> (<?= $opt['id']?>)</option>
                            <?php endforeach; ?>
                        </select>

                        <?php if(isset($errorCredentials["id_pasien--$i"])): ?>
                            <div class="text-danger" style="font-size: .9rem">
                                <?php foreach($errorCredentials["id_pasien--$i"] as $err): ?>
                                    <p class="mb-0"><?= $err?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group mb-3">
                        <label for="keluhan--<?= $i?>" class="form-label text-muted fs-6 mb-1">Keluhan</label>
                        <input type="text" name="keluhan--<?= $i?>" id="keluhan--<?= $i?>" class="form-control" value="<?= $old["keluhan--$i"] ?? ''?>">
                        <?php if(isset($errorCredentials["keluhan--$i"])): ?>
                            <div class="text-danger" style="font-size: .9rem">
                                <?php foreach($errorCredentials["keluhan--$i"] as $err): ?>
                                    <p class="mb-0"><?= $err?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>


                    <div class="form-group mb-3">
                        <label for="id_dokter--<?= $i?>" class="form-label text-muted fs-6 mb-1">Dokter</label>
                    
                        <select class="form-select" name="id_dokter--<?= $i?>" id="id_dokter--<?= $i?>">
                            <?php foreach($dataToShow['tb_dokter'] as $opt): ?>
                            <option value="<?= $opt['id']?>" <?= ($old["id_dokter--$i"] ?? "") === $opt['id'] ? 'selected' : ''?>><?= $opt['nama_dokter']?> (<?= $opt['id']?>)</option>
                            <?php endforeach; ?>
                        </select>

                        <?php if(isset($errorCredentials["id_dokter--$i"])): ?>
                            <div class="text-danger" style="font-size: .9rem">
                                <?php foreach($errorCredentials["id_dokter--$i"] as $err): ?>
                                    <p class="mb-0"><?= $err?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group mb-3">
                        <label for="diagnosa--<?= $i?>" class="form-label text-muted fs-6 mb-1">Diagnosa</label>
                        <input type="text" name="diagnosa--<?= $i?>" id="diagnosa--<?= $i?>" class="form-control" value="<?= $old["diagnosa--$i"] ?? ''?>">
                        <?php if(isset($errorCredentials["diagnosa--$i"])): ?>
                            <div class="text-danger" style="font-size: .9rem">
                                <?php foreach($errorCredentials["diagnosa--$i"] as $err): ?>
                                    <p class="mb-0"><?= $err?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group mb-3">
                        <label for="id_poliklinik--<?= $i?>" class="form-label text-muted fs-6 mb-1">Poliklinik</label>
                    
                        <select class="form-select" name="id_poliklinik--<?= $i?>" id="id_poliklinik--<?= $i?>">
                            <?php foreach($dataToShow['tb_poliklinik'] as $opt): ?>
                            <option value="<?= $opt['id']?>" <?= ($old["id_poliklinik--$i"] ?? "") === $opt['id'] ? 'selected' : ''?>><?= $opt['nama_poliklinik']?> (<?= $opt['id']?>)</option>
                            <?php endforeach; ?>
                        </select>

                        <?php if(isset($errorCredentials["id_poliklinik--$i"])): ?>
                            <div class="text-danger" style="font-size: .9rem">
                                <?php foreach($errorCredentials["id_poliklinik--$i"] as $err): ?>
                                    <p class="mb-0"><?= $err?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group mb-3">
                        <label for="tgl_periksa--<?= $i?>" class="form-label text-muted fs-6 mb-1">Tanggal Periksa</label>
                        <input type="date" name="tgl_periksa--<?= $i?>" id="tgl_periksa--<?= $i?>" class="form-control" value="<?= $old["tgl_periksa--$i"] ?? ''?>">
                        <?php if(isset($errorCredentials["tgl_periksa--$i"])): ?>
                            <div class="text-danger" style="font-size: .9rem">
                                <?php foreach($errorCredentials["tgl_periksa--$i"] as $err): ?>
                                    <p class="mb-0"><?= $err?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group mb-3">
                        <label for="id_obat--<?= $i?>" class="form-label text-muted fs-6 mb-1">Obat</label>
                    
                        <select multiple class="form-select" name="id_obat--<?= $i?>[]" id="id_obat--<?= $i?>">
                            <option value="" <?= (!isset($old["id_obat--$i"]) || $old["id_obat--$i"][0] === '') ? 'selected' : ''?>>-- Select --</option>

                            <?php foreach($dataToShow['tb_obat'] as $opt): ?>

                                <option value="<?= $opt['id']?>" <?= in_array($opt['id'], (
                                    $old["id_obat--$i"] ?? [] )) ? 'selected' : ''?>><?= $opt['nama_obat']?> (<?= $opt['id']?>)</option>
                            <?php endforeach; ?>
                        </select>

                        <?php if(isset($errorCredentials["id_obat--$i"])): ?>
                            <div class="text-danger" style="font-size: .9rem">
                                <?php foreach($errorCredentials["id_obat--$i"] as $err): ?>
                                    <p class="mb-0"><?= $err?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
                <?php endfor; ?>

            </div>
            <button type="submit" name="add" class="btn btn-primary mt-4" value="<?= $form_count?>">Tambah</button>
        </form>
    </div>
</div>

<?php require_once "../partials/footer.php" ?>