<?php 
$GLOBALS['title'] = 'EHealt | Register';
require_once "./functions.php";

EnsureUserAuth($conn, 'register');

if($_SERVER['REQUEST_METHOD'] === "POST"){
    if(isset($_POST['register'])){
        $otp = random_int(100000, 999999);
        $_POST['verification_code'] = $otp;

        $result = register($_POST);
        $old = $_POST;

        if($result instanceof W_ErrorValidator){
            $errorCredentials = $result->getErrors();
            // $old = $result->old();
        }else{
            if($result > 0){
                $email_sended = sendMail($_POST['email'], $otp);

                if($email_sended){
                    $_SESSION['process-success'] = "You successfully registered. Check your email for verification code!";
                    echo "
                        <script>
                            document.location.href = './verify.php';
                        </script>
                    ";
                }
            }else{
                echo "
                    <script>
                        alert('Anda gagal register!');
                    </script>
                ";
            }
        }
    }
}

?>

 
<?php require_once "./partials/header.php";?>

<div class="page" style="min-height: 100vh; display: grid; place-items: center;">
    <div class="row" style="width: 100%;">
        <div class="col-10 col-md-5 col-lg-4 col-xl-3  mx-auto border rounded" style="padding: 2rem;">
            <h2>Register Page</h2>
            <form action="" method="post">

                <div class="form-group my-3">
                    <label for="nama" class="mb-1">Nama</label>
                    <input type="text" id="nama" name="nama" class="form-control" value="<?= $old['nama'] ?? ''?>"/>

                    <?php if(isset($errorCredentials['nama'])): ?>
                        <div class="text-danger" style="font-size: .9rem">
                            <?php foreach($errorCredentials['nama'] as $err): ?>
                                <p class="mb-0"><?= $err?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group my-3">
                    <label for="email" class="mb-1">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?= $old['email'] ?? ''?>"/>

                    <?php if(isset($errorCredentials['email'])): ?>
                        <div class="text-danger" style="font-size: .9rem">
                            <?php foreach($errorCredentials['email'] as $err): ?>
                                <p class="mb-0"><?= $err?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group my-3">
                    <label for="password" class="mb-1">Password</label>
                    <input type="password" id="password" name="password" class="form-control"/>

                    <?php if(isset($errorCredentials['password'])): ?>
                        <div class="text-danger" style="font-size: .9rem">
                            <?php foreach($errorCredentials['password'] as $err): ?>
                                <p class="mb-0"><?= $err?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group my-3">
                    <label for="konfirmasi_password" class="mb-1">Konfirmasi Password</label>
                    <input type="password" id="konfirmasi_password" name="konfirmasi_password" class="form-control"/>

                    <?php if(isset($errorCredentials['konfirmasi_password'])): ?>
                        <div class="text-danger" style="font-size: .9rem">
                            <?php foreach($errorCredentials['konfirmasi_password'] as $err): ?>
                                <p class="mb-0"><?= $err?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>


                <div class="mt-4">
                    <button type="submit" class="btn btn-primary" name="register">Register</button>
                </div>
                <a href="./login.php" class="d-block mt-2">Sudah punya akun? Login disini!</a>

            </form>
        </div>
    </div>
</div>

<?php require_once "./partials/footer.php";?>

