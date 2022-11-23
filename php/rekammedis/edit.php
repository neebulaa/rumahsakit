<?php 
$GLOBALS['title'] = "EHealth | Edit Rekam Medis";
require_once "../functions.php";
EnsureUserAuth($conn, 'php/rekammedis/edit.php');

$current_table = 'tb_rekammedis';

if(count($_POST) <= 0 || $_SERVER['REQUEST_METHOD'] === "GET") {
    $_SESSION['process-failed'] = "Silakan pilih data terlebih dahulu!";
    header("Location: ./index.php");
    exit();
}


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
    if(isset($_POST['edit'])){
        $form_count = $_POST['edit'];
        $old = $_POST;
        $formatted_data = formatMultipleFormData($_POST, $form_count);
        $datas = normalizeKeys($formatted_data); //we need to make datas again so it can loop if errors happen
        $result = editRekamMedis($formatted_data, $current_table);

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
    }else{
        // just once time; when we go to this page
        $query = "SELECT * FROM `$current_table` WHERE ";
        $query_rm_obat = "SELECT * FROM `tb_rekammedis_obat` WHERE ";

        $ids = array_values($_POST);
        [$tbId, $tbFields] = getTableFields($current_table);
        foreach($ids as $index => $id){
            $query .= "`$tbId` = $id";
            $query_rm_obat .= "`id_rekammedis` = $id";

            if($index != count($ids) - 1){
                $query .= " OR ";
                $query_rm_obat .= " OR ";
            }
        }

        $datas = query($query);
        $obat_rekammedis = query($query_rm_obat);

        // $obat_rekammedis_id = array_map(function($orm){
        //     return [$orm['id_obat']];
        // }, $obat_rekammedis);

        $formatted_obat_rekammedis = [];
        foreach($obat_rekammedis as $orm){
            $cur_rm_obat_id = $orm['id_rekammedis'];
            $formatted_obat_rekammedis[$cur_rm_obat_id][] = $orm['id_obat'];
        }

        $obat_rekammedis_id = [];
        foreach($formatted_obat_rekammedis as $for){
            $obat_rekammedis_id[] = array_values($for);
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
            Edit Rekam Medis
        </h1>

        <p class="text-muted">Edit rekam medis EHealth.</p>

        <div class="custom-underline w-100"></div>

        <?php if(isset($error_process)): ?>
            <div class="alert alert-danger mt-4" role="alert">
                <?= $error_process?>
            </div>
        <?php endif; ?>

        <form action="" method="post" class="mt-5">
            <div class="d-flex gap-4 flex-wrap">

                <?php foreach($datas as $i => $data): ?>
                <?php $i = $i + 1 ?>
                <div class="form-group p-4 position-relative" style="border: 1px solid #999; width: 100%; max-width: 500px;" >
                    <input type="hidden" name="id--<?= $i?>" value="<?= $data['id'] ?>">
                    <h5 class="position-absolute text-primary form-counter" style="opacity: .9"><?= $i?></h5>

                    <div class="form-group mb-3">
                        <label for="id_pasien--<?= $i?>" class="form-label text-muted fs-6 mb-1">Pasien</label>
                    
                        <select class="form-select" name="id_pasien--<?= $i?>" id="id_pasien--<?= $i?>">
                            <?php foreach($dataToShow['tb_pasien'] as $opt): ?>
                            <option value="<?= $opt['id']?>" <?= ($old["id_pasien--$i"] ?? $data['id_pasien']) === $opt['id'] ? 'selected' : ''?>><?= $opt['nama_pasien']?> (<?= $opt['id']?>)</option>
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
                        <input type="text" name="keluhan--<?= $i?>" id="keluhan--<?= $i?>" class="form-control" value="<?= $old["keluhan--$i"] ?? $data['keluhan']?>">
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
                            <option value="<?= $opt['id']?>" <?= ($old["id_dokter--$i"] ?? $data['id_dokter']) === $opt['id'] ? 'selected' : ''?>><?= $opt['nama_dokter']?> (<?= $opt['id']?>)</option>
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
                        <input type="text" name="diagnosa--<?= $i?>" id="diagnosa--<?= $i?>" class="form-control" value="<?= $old["diagnosa--$i"] ?? $data['diagnosa']?>">
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
                            <option value="<?= $opt['id']?>" <?= ($old["id_poliklinik--$i"] ?? $data['id_poliklinik']) === $opt['id'] ? 'selected' : ''?>><?= $opt['nama_poliklinik']?> (<?= $opt['id']?>)</option>
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
                        <input type="date" name="tgl_periksa--<?= $i?>" id="tgl_periksa--<?= $i?>" class="form-control" value="<?= $old["tgl_periksa--$i"] ?? $data['tgl_periksa']?>">
                        <?php if(isset($errorCredentials["tgl_periksa--$i"])): ?>
                            <div class="text-danger" style="font-size: .9rem">
                                <?php foreach($errorCredentials["tgl_periksa--$i"] as $err): ?>
                                    <p class="mb-0"><?= $err?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>


                    <?php

                        // if user change value in the devtools

                        $strict = array_filter($old["id_obat--$i"] ?? [], function($id) use ($dataToShow){ 
                            return in_array($id, array_map(fn($dts) => $dts['id'], $dataToShow['tb_obat']));
                        });

                        $notStrict = count($strict) != count($old["id_obat--$i"] ?? []);

                    ?>
                    
                    <div class="form-group mb-3">
                        <label for="id_obat--<?= $i?>" class="form-label text-muted fs-6 mb-1">Obat</label>
                    
                        <select multiple class="form-select" name="id_obat--<?= $i?>[]" id="id_obat--<?= $i?>" style="min-height: 10rem">
                            <option value="" <?= (count($old["id_obat--$i"] ?? []) == 0 || $notStrict) && !isset($obat_rekammedis_id[$i - 1]) ? 'selected' : ''?>>-- Select --</option>

                            <?php foreach($dataToShow['tb_obat'] as $opt): ?>
                                <option value="<?= $opt['id']?>" <?= in_array($opt['id'], ($old["id_obat--$i"] ?? $obat_rekammedis_id[$i - 1] )) ? 'selected' : ''?>><?= $opt['nama_obat']?> (<?= $opt['id']?>)</option>
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
                <?php endforeach; ?>

            </div>
            <button type="submit" name="edit" class="btn btn-warning mt-4" value="<?= $i?>">Edit</button>
        </form>
    </div>
</div>

<?php require_once "../partials/footer.php" ?>
