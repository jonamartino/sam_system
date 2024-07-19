<?php include "Views/Templates/header.php"; ?>
<ol class="breadcrumb mb-4">
  <li class="breadcrumb-item active" aria-current="page">Tareas Pendientes Preventivos</li>
</ol>
<div class="table-responsive-xl table-fixed text-nowrap">
  <table class="table table-light table-hover w-100" data-order='[[ 0, "desc" ]]' data-page-length='25' id="tblPreventivosPendientes">
  <caption>Lista de mantenimientos preventivos pendientes de acción.</caption>
    <thead class="thead-dark">
      <tr>
        <th>#</th>
        <th>Máquina</th>
        <th>Ultima Orden</th>
        <th>Próxima Fecha</th>
        <th class="w-25">Descripción</th>
        <th>Estado</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
</div>

<div id="nuevo-preventivo" class="modal fade" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="title">Nuevo Preventivo</h5>
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
                        <label for="frecuencia">Frecuencia</label>
                        <select id="frecuencia" class="form-control" type="text" name="frecuencia" placeholder="Frecuencia">
                        <option value="" disabled selected>Selecciona una Frecuencia</option>
                        <?php foreach ($data['frecuencia'] as $row) { ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']," - ",$row['frecuencia_dias']; ?> </option>
                        <?php } ?>
                        </select>
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
                        <button class="btn btn-info me-2 btn-sm btn-block" type="button" data-bs-target="#staticBackdrop" onclick="frmTarea(id_maquina);">Crear Nueva Tarea</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
            <div class="d-flex justify-content-between">
                        <div>
                            <button class="btn btn-primary me-2" type="button" onclick="registrarPreventivo(event);" id="btn-accion">Rechazar</button>
                        </div>
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancelar</button>
                    </div>
            </div>
        </div>
    </div>
</div>

<div id="nueva-tarea" class="modal fade" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-white" id="title">Crear nueva tarea</h5>
                <button class="close text-white" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="frmTareas">
                    <div class="form-group">
                        <label for="id_tipo">Tipo de máquina</label>
                        <input  name="id_tarea" id="id_tarea" type="hidden">
                        <select id="id_tipo" selected class="form-control" type="text" name="id_tipo" placeholder="Tipo">
                        <option value="" disabled selected>Selecciona un tipo de máquina</option>
                            <?php foreach ($data['tipos'] as $row) { ?>
                                <option value="<?php echo $row['id_tipo']; ?>"><?php echo $row['id_tipo']," - ",$row['nombre']; ?> </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nombre_tarea">Nombre de la tarea</label>
                        <textarea id="nombre_tarea" class="form-control" type="text" name="nombre_tarea" rows="2" placeholder="Nombre descriptivo de la tarea" multiple></textarea>
                    </div>
                    <div class="form-group">
                        <label for="tiempo">Tiempo de realización</label>
                        <input type="number" id="tiempo" name="tiempo" step="1" min="0" max="20" placeholder="0" class="form-control">
                    </div>  
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary mt-2" type="button" onclick="registrarTarea(event);" id="btn-accion-tareas">Guardar</button>
                <button class="btn btn-secondary mt-2" type="button" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
<?php include "Views/Templates/footer.php"; ?>