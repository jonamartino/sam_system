<?php include "Views/Templates/header.php"; ?>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active" aria-current="page">Usuarios</li>
</ol>
<button class="btn btn-primary mb-2 btn-sm" type="button" onclick="frmUsuario();"><i class="fa-solid fa-user-plus"></i> Agregar Usuario</button>
<div class="table-responsive-xl">
    <table class="table table-light w-100" id="tblUsuarios">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Usuario</th>
                <th>legajo</th>
                <th>Nombre y Apellido</th>
                <th>Rol</th>
                <th>Estado</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<div id="nuevo-usuario" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="title">Nuevo Usuario</h5>
                <button class="close text-white" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><form method="POST" id="frmUsuario">
                    <div class="form-group">
                        <label for="usuario">Usuario</label>
                        <input type="hidden" id="id" name="id">
                        <input id="usuario" class="form-control" type="text" name="usuario" placeholder="Usuario">
                    </div>
                    <div class="form-group">
                        <label for="legajo">Legajo</label>
                        <select id="legajo" class="form-control" type="text" name="legajo" placeholder="Legajo">
                        <option value="" disabled selected>Selecciona la persona asociada al usuario</option>
                        <?php foreach ($data['personas'] as $row){ ?>
                            <option value="<?php echo $row['legajo']; ?>"> <?php echo $row['legajo']," - ", $row['nombre'], " ", $row['apellido']; ?></option>
                            <?php } ?>
                        <select>
                    </div>
                    <div class="row" id="claves">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="clave">Contraseña</label>
                                <input id="clave" class="form-control" type="password" name="clave" placeholder="Contraseña">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="confirmar">Confirmar Contraseña</label>
                                <input id="confirmar" class="form-control" type="password" name="confirmar" placeholder="Confirmar Contraseña">
                            </div>
                        </div>
                    </div>     
                    <div class="form-group">
                        <label for="rol">Rol del usuario</label>
                        <select id="rol" selected class="form-control" type="text" name="rol" placeholder="Tipo">
                        <option value="" disabled selected>Selecciona un rol para el usuario</option>
                            <?php foreach ($data['roles'] as $row) { ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['id']," - ",$row['nombre']; ?> </option>
                            <?php } ?>
                        </select>
                    </div>        
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="button" onclick="registrarUser(event);" id="btn-accion">Registrar</button>
                <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<?php include "Views/Templates/footer.php"; ?>
