<?php 
$GLOBALS['title'] = 'EHealt | Login';


?>

 
<?php require_once "./header.php";?>

<div class="page" style="min-height: 100vh; display: grid; place-items: center;">
    <div class="row" style="width: 100%;">
        <div class="col-3 mx-auto border rounded" style="padding: 2rem;">
            <h2>Login Page</h2>
            <form action="" method="post">

                <div class="form-group my-3">
                    <label for="email" class="mb-1">Email</label>
                    <input type="email" id="email" name="email" class="form-control"/>
                </div>

                <div class="form-group my-3">
                    <label for="password" class="mb-1">Password</label>
                    <input type="password" id="password" name="password" class="form-control"/>
                </div>


                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
                <a href="./register.php" class="d-block mt-2">Dont have account? Register here!</a>
            </form>
        </div>
    </div>
</div>

<?php require_once "./footer.php";?>

