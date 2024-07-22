<?php include "Views/Templates/header.php"; ?>
<div>
    <ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active" aria-current="page">Datos del Sistema</li>
    </ol>
    <div class="card">
        <form id="frmSistema">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input id="id" class="form-control" type="hidden" name="id" value="<?php echo $data['id'] ?>">
                            <label for="razon_social" >Razón Social</label>
                            <input id="razon_social" class="form-control" type="text" name="razon_social" value="<?php echo $data['razon_social'] ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input id="nombre" class="form-control" type="text" name="nombre" value="<?php echo $data['nombre'] ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input id="telefono" class="form-control" type="text" name="telefono" value="<?php echo $data['telefono'] ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                                <label for="direccion">Dirección</label>
                                <input id="direccion" class="form-control" type="text" name="direccion" value="<?php echo $data['direccion'] ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                                <label for="db_name">Nombre Base de Datos</label>
                                <input id="db_name" class="form-control" type="text" name="db_name" value="<?php echo $data['db_name'] ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                                <label for="db_ubicacion">Ubicación Backup</label>
                                <input id="db_ubicacion" class="form-control" type="text" name="db_ubicacion" value="<?php echo $data['db_ubicacion'] ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="mensaje">Mensaje</label>
                        <textarea id="mensaje" class="form-control" rows="3" name="mensaje" value="<?php echo $data['mensaje'] ?>"></textarea>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary" type="button" onclick="modificarSistema()">Modificar</button>
            </div>
        </form>
    </div>
</div>


<?php include "Views/Templates/footer.php"; ?>
