<?php 
$GLOBALS['title'] = 'EHealt | Rumah Sakit';
require_once "./php/functions.php";

EnsureUserAuth($conn);
$counts = getCounts('tb_dokter', 'tb_pasien', 'tb_obat', 'tb_poliklinik', 'tb_rekammedis')[0];


?>

 
<?php require_once "./php/partials/header.php";?>

<div class="d-flex">
    <?php require_once "./php/partials/sidebar.php" ?>

    <div class="container-fluid py-5 mx-5" style="overflow-y: auto; max-height: 100vh;">
        <span class="text-primary" style="opacity: .8;"><?= date("D, d M Y", time())?></span>
        <h1>Welcome, <span class="text-gradient"><?= $_SESSION['user']['nama']?></span></h1>
        <p class="text-muted">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Fugiat voluptatibus esse minima inventore ea amet officiis cumque libero?</p>  

        <div class="sosmed fs-4">
            <a href="https://github.com/EdwinHendlyEDH/11TKJ1-project1-rumahsakit">
                <i class="fa-brands fa-github"></i>
            </a>
        </div>

        <div class="custom-underline w-100"></div>

        
        <div class="cards mt-5 d-flex gap-4 flex-wrap align-items-start">
            <?php foreach($counts as $table => $count): ?>
            <a href="<?= $base_url . '/php' . '/' . explode('_', $table)[1];// [tb, pasien, count] ?>" class="d-block mb-3 border" style="text-decoration:none; color: inherit; padding: 1.5rem 2rem;flex: 1 1 480px;">
                <div class="d-flex align-items-center flex-wrap" style="gap: 2rem;">
                    <div class="d-flex justify-content-center align-items-center flex-column" style="max-width: max-content;">
                        <div class="record-count text-gradient purple" style="line-height:1; "><?= $count?></div>
                        <span class="text-gradient purple fs-6">RECORDS</span>
                    </div>  
                    <div style="max-width: 70%;">
                        <h4 style="font-weight: 600;"><?= $table?></h4>
                        <p style="font-size: .95rem;">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Perferendis, consequatur.</p>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>

    </div> 
</div>

<?php require_once "./php/partials/footer.php";?>
