<?php 
$GLOBALS['title'] = "EHealth | Tambah Obat";
require_once "../functions.php";
EnsureUserAuth($conn, 'php/obat/tambah.php');

$current_table = 'tb_obat';

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

        <p class="text-muted">Tambah obat EHealth baru.</p>

        <div class="custom-underline w-100"></div>
        <div class="row">
            <div class="col-6">
                <form action="" method="post" class="mt-4">
                    <label for="form_count">Buat berapa form: </label>
                    <div class="input-group">
                        <input type="number" class="form-control" name="form_count" min="1" max="9" pattern="[0-9]">
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
                        <label for="nama_obat--<?= $i?>" class="form-label text-muted fs-6 mb-1">Nama Obat</label>
                        <input type="text" name="nama_obat--<?= $i?>" id="nama_obat--<?= $i?>" class="form-control" value="<?= $old["nama_obat--$i"] ?? ''?>">
                        <?php if(isset($errorCredentials["nama_obat--$i"])): ?>
                            <div class="text-danger" style="font-size: .9rem">
                                <?php foreach($errorCredentials["nama_obat--$i"] as $err): ?>
                                    <p class="mb-0"><?= $err?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group mb-3">
                        <label for="ket_obat--<?= $i?>" class="form-label text-muted fs-6 mb-1">Keterangan Obat</label>

                        <textarea name="ket_obat--<?= $i?>" id="ket_obat--<?= $i?>" class="form-control" style="resize: none; min-height: 10rem;"><?= $old["ket_obat--$i"] ?? ''?></textarea>
                        <?php if(isset($errorCredentials["ket_obat--$i"])): ?>
                            <div class="text-danger" style="font-size: .9rem">
                                <?php foreach($errorCredentials["ket_obat--$i"] as $err): ?>
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