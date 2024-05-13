<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Panel de Administración</title>
        <link href="<?php echo base_url;?>Assets/css/styles.css" rel="stylesheet" />
        <link href="<?php echo base_url;?>Assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
        <link href="<?php echo base_url;?>Assets/css/bootstrap.min.css" rel="stylesheet">
        <script src="<?php echo base_url;?>Assets/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="<?php echo base_url; ?>">
                <img src="Assets/img/SAM3.png" alt="Logo de SAM" height="140">
            </a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar-->
            <ul class="navbar-nav ml-auto ">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#!">Perfil</a></li>
                        <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="<?php echo base_url;?>Usuarios/salir">Cerrar Sesion</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#modulo-seguridad" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-key"></i></div>
                                Modulo de Seguridad
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="modulo-seguridad" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="<?php echo base_url;?>Usuarios"><i class= "fas fa-user mr-2"></i>Usuarios</a>
                                    <a class="nav-link" href="<?php echo base_url;?>Personas"><i class="fa-solid fa-users mr-2"></i>Personas</a>
                                    <a class="nav-link" href="#"><i class="fa-solid fa-unlock-keyhole mr-2"></i>Permisos</a>
                                    <a class="nav-link" href="#"><i class="fa-solid fa-user-group mr-2"></i>Roles</a>
                                </nav>
                            </div>
                            
                            <a class="nav-link" href="<?php echo base_url; ?>Maquinas">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-gears"></i></div>
                                Maquinas
                            </a>
                            
                            <a class="nav-link" href="<?php echo base_url; ?>Preventivos">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-wrench"></i></div>
                                Preventivos
                            </a>
                            <a class="nav-link" href="<?php echo base_url; ?>Ordenes">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-pen-to-square"></i></div>
                                Orden Mantenimiento
                            </a>

                            
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        Start Bootstrap
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid mt-2">



