<?php include "Views/Templates/header.php"; ?>
<ol class="breadcrumb mb-4">
  <li class="breadcrumb-item active" aria-current="page">Preventivos</li>
</ol>
<button class="btn btn-primary mb-2" type="button" onclick="frmPreventivo();"><i class="fa-solid fa-file-circle-plus"></i></button>
<table class="table table-light" id="tblPreventivos">
  <thead class="thead-dark">
    <tr>
      <th>#</th>
      <th>Maquina</th>
      <th>Tecnico</th>
      <th>Fecha Programada</th>
      <th>Descripcion</th>
      <th>Estado</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>
<div id="nuevo-preventivo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="title">Nuevo Empleado</h5>
                <button class="close text-white" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><form method="POST" id="frmPreventivo">
                    <div class="form-group">
                        <label for="id_maquina">Maquina</label>
                        <input  name="id_preventivo" id="id_preventivo" type="hidden">
                        <input  name="id_seleccion" id="id_seleccion" type="hidden">
                        <select id="id_maquina" class="form-control" type="text" name="id_maquina" onchange="getTareas();">
                        <option value="" disabled selected>Selecciona una Máquina</option>
                        <?php foreach ($data['maquinas'] as $row) { ?>
                            <option value="<?php echo $row['id_maquina']; ?>"><?php echo $row['id_maquina']," - ",$row['nombre']; ?> </option>
                        <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="legajo">Tecnico</label>
                        <select id="legajo" class="form-control" type="text" name="legajo" placeholder="Tecnico">
                        <option value="" disabled selected>Selecciona un Tecnico</option>
                        <?php foreach ($data['personas'] as $row) { ?>
                            <option value="<?php echo $row['legajo']; ?>"><?php echo $row['legajo']," - ",$row['nombre_apellido']; ?> </option>
                        <?php } ?>
                        </select>
                    </div>
                    <div class="row" id="fecha_hora">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fecha_programada">Fecha Programada</label>
                                <input  name="fecha_programada" id="fecha_programada" class="form-control" type="date" placeholder="dd-mm-yyyy">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="hora_programada">Hora Programada</label>
                                <input  name="hora_programada" id="hora_programada" class="form-control" type="time" placeholder="hh-mm">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripcion</label>
                        <textarea id="descripcion" class="form-control" type="text" name="descripcion" rows="3" placeholder="Descripcion breve del preventivo" multiple></textarea>
                    </div>     
                    <div class="form-group">
                        <label for="id_tarea">Tareas</label>
                        <select id="id_tarea" class="form-control" type="text" name="id_tarea[]" placeholder="Tarea" multiple>
                        <label>Tarea</label>
                        <?php foreach ($data['tareas'] as $row) { ?>
                            <option value="<?php echo $row['id_tarea']; ?>"> <?php echo $row['id_tarea']," - ",$row['nombre']; ?> </option>
                        <?php } ?>
                        </select>
                        <input id="id_tarea1" name="id_tarea1[]" type="hidden">
                    </div>
                <button class="btn btn-primary" type="button" onclick="registrarPreventivo(event);" id="btn-accion">Registrar</button>
                <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Cancelar</button>
                </form>
            </div>
        </div>
    </div>
</div>


<?php include "Views/Templates/footer.php"; ?>