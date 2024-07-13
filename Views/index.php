<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Iniciar Sesión</title>
        <link href="<?php echo base_url;?>Assets/css/styles.css" rel="stylesheet" />
        <script src="<?php echo base_url;?>Assets/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <style>
            body {
                background-image: url('<?php echo base_url; ?>Assets/img/background.jpg');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                background-attachment: fixed;
            }
        </style>
    <body">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-4">
                                <div class="card shadow-lg border-50 rounded-lg mt-5">
                                    <div class="card-header">
                                        <h3 class="text-center font-weight-light my-4">
                                            <img src="<?php echo base_url;?>Assets/img/SAM6.png" alt="Logo de SAM" height="100">
                                        </h3>
                                </div>
                                    <div class="card-body">
                                        <form method="POST" id="frmLogin">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="usuario" name="usuario" type="text" placeholder="Ingrese Usuario" />
                                                <label for="usuario" >Usuario</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="clave" name="clave" type="password" placeholder="Ingrese Contraseña" />
                                                <label for="clave">Contraseña</label>
                                            </div>
                                            <div class="alert alert-danger text-center d-none" id="alerta" role="alert">
                                                
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <button class="btn btn-primary" type="submit" onclick="frmLogin(event);">Ingresar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Jonathan Martino</div>
                            <div>
                                <a href="#">Github</a>
                                &middot;
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="<?php echo base_url;?>Assets/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script>
            const base_url = "<?php echo base_url; ?>";
        </script>
        <script src="<?php echo base_url;?>Assets/js/login.js"></script>

    </body>
</html>
