<?php 
$GLOBALS['title'] = "EHealth | Edit Poliklinik";
require_once "../functions.php";
EnsureUserAuth($conn, 'php/poliklinik/edit.php');

$current_table = 'tb_poliklinik';

if(count($_POST) <= 0 || $_SERVER['REQUEST_METHOD'] === "GET") {
    $_SESSION['process-failed'] = "Silakan pilih data terlebih dahulu!";
    header("Location: ./index.php");
    exit();
}


if($_SERVER['REQUEST_METHOD'] === "POST"){
    if(isset($_POST['edit'])){
        $form_count = $_POST['edit'];
        $old = $_POST;
        $formatted_data = formatMultipleFormData($_POST, $form_count);
        $datas = normalizeKeys($formatted_data); //we need to make datas again so it can loop if errors happen
        $result = edit($formatted_data, $current_table);

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
        $ids = array_values($_POST);
        [$tbId, $tbFields] = getTableFields($current_table);
        foreach($ids as $index => $id){
            $query .= "`$tbId` = $id";
            if($index != count($ids) - 1){
                $query .= " OR ";
            }
        }
        $datas = query($query);
    }
}


?>
<?php require_once "../partials/header.php" ?>
<div class="d-flex">
    <?php require_once "../partials/sidebar.php" ?>

    <div class="container-fluid py-5 mx-5" style="overflow-y: auto; max-height: 100vh;">
    <h1 class="align-items-center d-flex gap-3">
            <a href="./index.php"><i class="fa-solid fa-arrow-left fs-3"></i></a>
            Edit Poliklinik
        </h1>

        <p class="text-muted">Edit poliklinik EHealth.</p>

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
                        <label for="nama_poliklinik--<?= $i?>" class="form-label text-muted fs-6 mb-1">Nama Poliklinik</label>
                        <input type="text" name="nama_poliklinik--<?= $i?>" id="nama_poliklinik--<?= $i?>" class="form-control" value="<?= $old["nama_poliklinik--$i"] ?? $data['nama_poliklinik']?>">
                        <?php if(isset($errorCredentials["nama_poliklinik--$i"])): ?>
                            <div class="text-danger" style="font-size: .9rem">
                                <?php foreach($errorCredentials["nama_poliklinik--$i"] as $err): ?>
                                    <p class="mb-0"><?= $err?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group mb-3">
                        <label for="gedung--<?= $i?>" class="form-label text-muted fs-6 mb-1">Gedung</label>
                        <input type="text" name="gedung--<?= $i?>" id="gedung--<?= $i?>" class="form-control" value="<?= $old["gedung--$i"] ?? $data['gedung']?>">
                        <?php if(isset($errorCredentials["gedung--$i"])): ?>
                            <div class="text-danger" style="font-size: .9rem">
                                <?php foreach($errorCredentials["gedung--$i"] as $err): ?>
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
