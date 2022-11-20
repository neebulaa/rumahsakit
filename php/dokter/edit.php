<?php 
$GLOBALS['title'] = "EHealth | Edit Dokter";
require_once "../functions.php";
EnsureUserAuth($conn, 'php/dokter/edit.php');


if(count($_POST) <= 0 || $_SERVER['REQUEST_METHOD'] === "GET") {
    echo "
        <script>
            alert('Silakan pilih data terlebih dahulu!');
            document.location.href = './index.php';
        </script>
    ";
}


if($_SERVER['REQUEST_METHOD'] === "POST"){
    if(isset($_POST['edit'])){
        $form_count = $_POST['edit'];
        $old = $_POST;
        $formatted_data = formatMultipleFormData($_POST, $form_count);
        $datas = normalizeKeys($formatted_data); //we need to make datas again so it can loop if errors happen
        $result = edit($formatted_data, 'tb_dokter');

        if($result instanceof W_ErrorValidator){
            $errorCredentials = $result->getErrors()['forms_error'];
            $mergeErrorCredentials = w_validator_errors_merge($errorCredentials);
            $errorCredentials = $mergeErrorCredentials['errors'];
        }else{
            if($result->status == 'success'){
                header('Location: ./index.php');
                exit;
            }else{
                $error = $result->message;
            }
        }
    }else{
        // just once time; when we go to this page
        $query = "SELECT * FROM `tb_dokter` WHERE ";
        $ids = array_values($_POST);

        foreach($ids as $index => $id){
            $query .= "id = $id";
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
            Edit Dokter
        </h1>

        <p class="text-muted">Edit dokter EHealth.</p>

        <div class="custom-underline w-100"></div>
        <form action="" method="post" class="mt-5">
            <div class="d-flex gap-4 flex-wrap">

                <?php foreach($datas as $i => $data): ?>
                <?php $i = $i + 1 ?>
                <div class="form-group p-4 position-relative" style="border: 1px solid #999; width: 100%; max-width: 500px;" >
                    <input type="hidden" name="id--<?= $i?>" value="<?= $data['id'] ?>">
                    <h5 class="position-absolute text-primary form-counter" style="opacity: .9"><?= $i?></h5>

                    <div class="form-group mb-3">
                        <label for="nama_dokter--<?= $i?>" class="form-label text-muted fs-6 mb-1">Nama Dokter</label>
                        <input type="text" name="nama_dokter--<?= $i?>" id="nama_dokter--<?= $i?>" class="form-control" value="<?= $old["nama_dokter--$i"] ?? $data['nama_dokter']?>">
                        <?php if(isset($errorCredentials["nama_dokter--$i"])): ?>
                            <div class="text-danger" style="font-size: .9rem">
                                <?php foreach($errorCredentials["nama_dokter--$i"] as $err): ?>
                                    <p class="mb-0"><?= $err?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group mb-3">
                        <label for="spesialis--<?= $i?>" class="form-label text-muted fs-6 mb-1">Spesialis</label>
                        <input type="text" name="spesialis--<?= $i?>" id="spesialis--<?= $i?>" class="form-control" value="<?= $old["spesialis--$i"] ?? $data['spesialis']?>">
                        <?php if(isset($errorCredentials["spesialis--$i"])): ?>
                            <div class="text-danger" style="font-size: .9rem">
                                <?php foreach($errorCredentials["spesialis--$i"] as $err): ?>
                                    <p class="mb-0"><?= $err?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group mb-3">
                        <label for="alamat--<?= $i?>" class="form-label text-muted fs-6 mb-1">Alamat</label>
                        <input type="text" name="alamat--<?= $i?>" id="alamat--<?= $i?>" class="form-control" value="<?= $old["alamat--$i"] ?? $data['alamat']?>">
                        <?php if(isset($errorCredentials["alamat--$i"])): ?>
                            <div class="text-danger" style="font-size: .9rem">
                                <?php foreach($errorCredentials["alamat--$i"] as $err): ?>
                                    <p class="mb-0"><?= $err?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group mb-3">
                        <label for="no_telp--<?= $i?>" class="form-label text-muted fs-6 mb-1">No Telpon</label>
                        <input type="text" name="no_telp--<?= $i?>" id="no_telp--<?= $i?>" class="form-control" value="<?= $old["no_telp--$i"] ?? $data['no_telp']?>">
                        <?php if(isset($errorCredentials["no_telp--$i"])): ?>
                            <div class="text-danger" style="font-size: .9rem">
                                <?php foreach($errorCredentials["no_telp--$i"] as $err): ?>
                                    <p class="mb-0"><?= $err?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>
            <button type="submit" name="edit" class="btn btn-primary mt-4" value="<?= $i?>">Edit</button>
        </form>
    </div>
</div>

<?php require_once "../partials/footer.php" ?>
