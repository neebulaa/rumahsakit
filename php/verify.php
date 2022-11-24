<?php 
$GLOBALS['title'] = 'EHealt | Verify Email';
require_once "./functions.php";
EnsureUserAuth($conn, 'verify');


if($_SERVER['REQUEST_METHOD'] === "POST"){
    if(isset($_POST['verify'])){
        $res = verify($_POST);

        if($res instanceof W_ErrorValidator){
            $errorCredentials = $res->getErrors();
        }else if ($res instanceof W_Message){
            if($res->status == 'failed'){
                $error = $res->message;
            }else{
                header('Location: ../index.php');
                exit;
            }
        }
    }
}

?>

 
<?php require_once "./partials/header.php";?>

<div class="page" style="min-height: 100vh; display: grid; place-items: center;">
    <div class="row" style="width: 100%;">
        <div class="col-10 col-md-5 col-lg-4 col-xl-3 mx-auto border rounded" style="padding: 2rem;">
            <h2>Verify Email</h2>

            <?php if(isset($_SESSION['process-success'])): ?>
                <div class="alert alert-success mt-4" role="alert" style="max-width: 480px;">
                    <?= $_SESSION['process-success']?>

                    <?php unset($_SESSION['process-success']); ?>
                </div>
            <?php endif; ?>

            <?php if(isset($error)): ?>
                <div class="alert alert-danger">
                    <?= $error?>
                </div>
            <?php endif; ?>

            <form action="" method="post">

                <div class="form-group my-3">
                    <label for="verification_code" class="mb-1">Verification Code</label>
                    <input type="text" id="verification_code" name="verification_code" class="form-control" maxlength="6"/>
                    <?php if(isset($errorCredentials['verification_code'])): ?>
                        <div class="text-danger" style="font-size: .9rem">
                            <?php foreach($errorCredentials['verification_code'] as $err): ?>
                                <p class="mb-0"><?= $err?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary" name="verify">Verify</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once "./partials/footer.php";?>

