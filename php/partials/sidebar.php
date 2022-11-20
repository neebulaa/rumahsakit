<?php 
$dir = __DIR__;


$page = getPage();

?>


<div class="d-flex flex-column flex-shrink-0 p-3 custom-sidebar" style="width: 280px; min-height: 100vh; box-shadow: 5px 0 5px rgba(0,0,0,.05)" >
    <div class="toggle-sidebar"><i class="fa-solid fa-arrow-right"></i></div>
    <a href="<?= $base_url?>" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
        <i class="fa-solid fa-notes-medical me-2 fs-4"></i>
        <span class="fs-4 logo">E<span class="text-primary">Health</span></span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
        <a href="<?= $base_url?>" class="nav-link <?= $page == '/' ? 'active' : 'link-dark'?>" aria-current="page">
        <i class="fa-solid fa-house"></i>
            <span class="ms-2">Beranda</span>
        </a>
        </li>
        <li>
        <a href="<?= "$base_url/php/dokter/index.php"?>" class="nav-link <?= str_contains($page,'/php/dokter') ? 'active' : 'link-dark'?>">
        <i class="fa-solid fa-stethoscope"></i>
            <span class="ms-2">Dokter</span>
        </a>
        </li>
        <li>
        <a href="<?= "$base_url/php/pasien/index.php"?>" class="nav-link <?= str_contains($page,'/php/pasien') ? 'active' : 'link-dark'?>">
        <i class="fa-solid fa-bed"></i>
            <span class="ms-2">Pasien</span>
        </a>
        </li>
        <li>
        <a href="<?= "$base_url/php/obat/index.php"?>" class="nav-link <?= str_contains($page,'/php/obat') ? 'active' : 'link-dark'?>">
        <i class="fa-solid fa-prescription-bottle-medical"></i>
            <span class="ms-2">Obat</span>
        </a>
        </li>
        <li>
        <a href="<?= "$base_url/php/poliklinik/index.php"?>" class="nav-link <?= str_contains($page,'/php/poliklinik') ? 'active' : 'link-dark'?>">
        <i class="fa-solid fa-house-chimney-medical"></i>
            <span class="ms-2">Poliklinik</span>
        </a>
        </li>
        <li>
        <a href="<?= "$base_url/php/rekammedis/index.php"?>" class="nav-link <?= str_contains($page,'/php/rekammedis') ? 'active' : 'link-dark'?>">
        <i class="fa-solid fa-clipboard"></i>
            <span class="ms-2">Rekam Medis</span>
        </a>
        </li>
    </ul>
    <hr>
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <strong style="max-width: 200px; display: block; overflow: hidden; text-overflow:ellipsis;">Welcome, <?= $_SESSION['user']['nama']?></strong>
        </a>
        <ul class="dropdown-menu text-small shadow">
        <li><a class="dropdown-item" href="#">Profile</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="<?= "$base_url/php/logout.php"?>">Sign out</a></li>
        </ul>
    </div>
</div>

<script src="<?= "$base_url/assets/js/sidebar.js"?>" defer></script>