<?php include "Views/Templates/header.php"; ?>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active" aria-current="page">Empleados</li>
</ol>
<button class="btn btn-primary mb-2" type="button" onclick="frmPersona();"><i class="fa-solid fa-user-plus"></i></button>
<table class="table table-light" id="tblPersonas">
    <thead class="thead-dark">
        <tr>
            <th>Legajo</th>
            <th>Nombre y Apellido</th>  
            <th>Dni</th>
            <th>Mail</th>  
            <th>Estado</th>  
            <th></th> 
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<div id="nueva-persona" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="title">Nuevo Empleado</h5>
                <button class="close text-white" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><form method="POST" id="frmPersona">
                    <div class="form-group">
                        <label for="dni">Numero de Documento de Identidad</label>
                        <input  name="legajo" id="legajo" type="hidden">
                        <input  name="dni" id="dni" class="form-control" type="text" placeholder="Numero de DNI">
                    </div>
                    <div class="row" id="nombre_apellido">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input name="nombre" id="id_maquina" type="hidden">
                                <input name="nombre" id="nombre" class="form-control" type="text"  placeholder="Nombre del empleado">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="apellido">Apellido</label>
                                <input name="apellido" id="apellido" class="form-control" type="text"  placeholder="Apellido del empleado">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="mail">Mail</label>
                        <input  name="mail" id="mail" class="form-control" type="text" placeholder="usuario@dominio.com">
                    </div>
                    <div class="form-group">
                        <label for="celular">Celular</label>
                        <input  name="celular" id="celular" class="form-control" type="text" placeholder="Numero de celular">
                    </div>
                    <div class="form-group">
                        <label for="id_turno">Turno</label>
                        <select id="id_turno" class="form-control" type="text" name="id_turno" placeholder="Turno">
                        <label>Turno</label>
                        <?php foreach ($data['turnos'] as $row) { ?>
                            <option value="<?php echo $row['id_turno']; ?>"> <?php echo $row['id_turno']," - ",$row['descripcion']; ?> </option>
                        <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fecha_nacimiento">Fecha de nacimiento</label>
                        <input  name="fecha_nacimiento" id="fecha_nacimiento" class="form-control" type="date" placeholder="dd-mm-yyyy">
                    </div>
                    <div class="form-group">
                    <input class="Discriminator" type="checkbox" value="" id="discriminator" onclick="myCheck()">
                    <label class="form-check-label" for="Discriminator">Tecnico</label>
                    </div>
                    <div id="form-tecnico" style="display:none">
                        <div class="form-group">
                            <label for="especialidad">Especialidad</label>
                            <select id="especialidad" class="form-control" type="text" name="especialidad" placeholder="Especialidad">
                            <label>Especialidad</label>
                                <option value="" selected>Elegir opcion</option>
                                <option value="Electricista">Electricista</option>
                                <option value="Mecanico">Mecanico</option>
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="button" onclick="registrarPersona(event);" id="btn-accion">Registrar</button>
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Cancelar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include "Views/Templates/footer.php"; ?>
