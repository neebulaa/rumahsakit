<?php 
$GLOBALS['title'] = "EHealth | Tambah Pasien";
require_once "../functions.php";
EnsureUserAuth($conn, 'php/pasien/tambah.php');

$current_table = 'tb_pasien';

$form_count = 1;

if($_SERVER['REQUEST_METHOD'] === "POST"){
    if(isset($_POST['create_form'])){
        $form_count = (int) $_POST['form_count'];
    }

    if(isset($_POST['add'])){
        // format data
        $form_count = $_POST['add'];
        $old = $_POST;
        $formatted_data = formatMultipleFormData($_POST, $form_count);
        $result = add($formatted_data, $current_table);

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

        <p class="text-muted">Tambah pasien EHealth baru.</p>

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
                        <label for="nomor_identitas--<?= $i?>" class="form-label text-muted fs-6 mb-1">Nomor Identitas</label>
                        <input type="text" name="nomor_identitas--<?= $i?>" id="nomor_identitas--<?= $i?>" class="form-control" value="<?= $old["nomor_identitas--$i"] ?? ''?>">
                        <?php if(isset($errorCredentials["nomor_identitas--$i"])): ?>
                            <div class="text-danger" style="font-size: .9rem">
                                <?php foreach($errorCredentials["nomor_identitas--$i"] as $err): ?>
                                    <p class="mb-0"><?= $err?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group mb-3">
                        <label for="nama_pasien--<?= $i?>" class="form-label text-muted fs-6 mb-1">Nama Pasien</label>
                        <input type="text" name="nama_pasien--<?= $i?>" id="nama_pasien--<?= $i?>" class="form-control" value="<?= $old["nama_pasien--$i"] ?? ''?>">
                        <?php if(isset($errorCredentials["nama_pasien--$i"])): ?>
                            <div class="text-danger" style="font-size: .9rem">
                                <?php foreach($errorCredentials["nama_pasien--$i"] as $err): ?>
                                    <p class="mb-0"><?= $err?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group mb-3">
                        <label for="jenis_kelamin--<?= $i?>" class="form-label text-muted fs-6 mb-1">Jenis Kelamin</label>
                    
                        <select class="form-select" name="jenis_kelamin--<?= $i?>" id="jenis_kelamin--<?= $i?>">
                            <option value="L" <?= ($old["jenis_kelamin--$i"] ?? "") === "L" ? 'selected' : ''?>>Laki-laki</option>
                            <option value="P" <?= ($old["jenis_kelamin--$i"] ?? "")  === "P" ? 'selected' : ''?>>Perempuan</option>
                        </select>

                        <?php if(isset($errorCredentials["jenis_kelamin--$i"])): ?>
                            <div class="text-danger" style="font-size: .9rem">
                                <?php foreach($errorCredentials["jenis_kelamin--$i"] as $err): ?>
                                    <p class="mb-0"><?= $err?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group mb-3">
                        <label for="alamat--<?= $i?>" class="form-label text-muted fs-6 mb-1">Alamat</label>
                        <input type="text" name="alamat--<?= $i?>" id="alamat--<?= $i?>" class="form-control" value="<?= $old["alamat--$i"] ?? ''?>">
                        <?php if(isset($errorCredentials["alamat--$i"])): ?>
                            <div class="text-danger" style="font-size: .9rem">
                                <?php foreach($errorCredentials["alamat--$i"] as $err): ?>
                                    <p class="mb-0"><?= $err?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group mb-3">
                        <label for="no_telp--<?= $i?>" class="form-label text-muted fs-6 mb-1">No Telp</label>
                        <input type="text" name="no_telp--<?= $i?>" id="no_telp--<?= $i?>" class="form-control" value="<?= $old["no_telp--$i"] ?? ''?>">
                        <?php if(isset($errorCredentials["no_telp--$i"])): ?>
                            <div class="text-danger" style="font-size: .9rem">
                                <?php foreach($errorCredentials["no_telp--$i"] as $err): ?>
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