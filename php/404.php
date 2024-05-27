<?php
$GLOBALS['title'] = "EHealt | 404 Page";
require "../config.php";
?>


<?php require_once "./partials/header.php" ?>
<div class="bg-primary text-white text-center" style="padding: 5rem">
    <h1>
        404 Not Found
    </h1>
    <a href="<?= $base_url ?>" style="color: inherit; display: block; opacity: .8;">Go to entry point</a>
</div>

<?php require_once "./partials/footer.php" ?>