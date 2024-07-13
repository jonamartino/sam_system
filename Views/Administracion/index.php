<?php include "Views/Templates/header.php"; ?>
<div>
<ol class="breadcrumb mb-4">
  <li class="breadcrumb-item active" aria-current="page">Datos del Sistema</li>
</ol>
    <div class="card-body">
        <form id="frmSistema">
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
            </div>
            
            <div class="form-group">
                <label for="mensaje">Mensaje</label>
                <textarea id="mensaje" class="form-control" rows="3" name="mensaje" value="<?php echo $data['mensaje'] ?>"></textarea>
            </div>
            <button class="btn btn-primary" type="button" onclick="modificarSistema()">Modificar</button>
        </form>
    </div>
</div>


<?php include "Views/Templates/footer.php"; ?>
