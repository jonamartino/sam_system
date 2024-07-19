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
        <link href="<?php echo base_url;?>Assets/css/icon.css" rel="stylesheet">
        <link href="<?php echo base_url;?>Assets/css/css2.css" rel="stylesheet"/>
        <script src="<?php echo base_url;?>Assets/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand" href="<?php echo base_url;?>Administracion/home">
                <img src="<?php echo base_url;?>Assets/img/SAM8.png" alt="Logo de SAM" height="40">
            </a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link" id="sidebarToggle" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Navbar-->
            <ul class="navbar-nav ml-auto ">
                <li class="nav-item dropdown">
                    <a class="nav-link me-2" href="#" id="tasksDropdownToggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-inbox"></i>
                        <span id="tasksCount" class="badge bg-danger"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" id="tasksDropdown" aria-labelledby="notificationDropdownToggle">
                    </ul>
                    <ul class="dropdown-menu dropdown-menu-end" id="tasksDropdown1" aria-labelledby="notificationDropdownToggle">
                    </ul>
                    
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link me-2" href="#" id="notificationDropdownToggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-bell"></i>
                        <span id="notificationCount" class="badge bg-danger"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" id="notificationDropdown" aria-labelledby="notificationDropdownToggle">
                        <!-- Las notificaciones se cargarán aquí -->
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle me-2" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
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
                            <a class="nav-link collapsed" href="" data-bs-toggle="collapse" data-bs-target="#modulo-seguridad1" aria-expanded="false" aria-controls="#modulo-seguridad1">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-key"></i></div>
                                Administración
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="modulo-seguridad1" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="<?php echo base_url;?>Usuarios"><i class= "fas fa-user mr-2"></i>Usuarios</a>
                                    <a class="nav-link" href="<?php echo base_url;?>Personas"><i class="fa-solid fa-users mr-2"></i>Personas</a>
                                    <a class="nav-link" href="<?php echo base_url;?>Roles/permisos"><i class="fa-solid fa-key mr-2"></i>Permisos</a>
                                    <a class="nav-link" href="<?php echo base_url;?>Roles"><i class="fa-solid fa-user-group mr-2"></i>Roles</a>
                                    <a class="nav-link" href="<?php echo base_url;?>Administracion"><i class="fa-solid fa-tools mr-2"></i>Configuración</a>
                                </nav>
                            </div>
                            
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#modulo-preventivos" aria-expanded="false" aria-controls="modulo-preventivos">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-gear"></i></div>
                                Modulo Preventivos
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="modulo-preventivos" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="<?php echo base_url; ?>Preventivos"><i class="fa-solid fa-wrench mr-2"></i>Preventivos Activos</a>
                                    <a class="nav-link" href="<?php echo base_url; ?>Preventivos/inactivos"><i class="fa-solid fa-wrench mr-2"></i>Preventivos Inactivos</a>
                                    <a class="nav-link" href="<?php echo base_url; ?>Ordenes"><i class="fa-solid fa-pen-to-square mr-2"></i>Ordenes Activas</a>
                                    <a class="nav-link" href="<?php echo base_url; ?>OrdenesCerradas"><i class="fa-solid fa-pen-to-square mr-2"></i>Ordenes Cerradas</a>
                                </nav>
                            </div>

                            
                            <a class="nav-link" href="<?php echo base_url; ?>Maquinas">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-gears"></i></div>
                                Maquinas
                            </a>
                            <a class="nav-link" href="<?php echo base_url; ?>Maquinas/tareas">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-tasks"></i></div>
                                Tareas
                            </a>


                            
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as: <?php echo $_SESSION['usuario']; ?></div>
                        SAM System
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid mt-2">



