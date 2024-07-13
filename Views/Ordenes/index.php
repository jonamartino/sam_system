<?php include "Views/Templates/header.php"; ?>
<ol class="breadcrumb mb-4">
  <li class="breadcrumb-item active" aria-current="page">Ordenes de Mantenimiento</li>
</ol>
<div class="table-responsive-xl">
  <table class="table table-light table-hover w-100" data-order='[[ 0, "desc" ]]' data-page-length='25' id="tblOrdenes">
    <thead class="thead-dark">
      <tr>
        <th>Orden</th>
        <th>Preventivo</th>
        <th>Tecnico</th>
        <th>Fecha</th>
        <th>Tiempo</th>
        <th>Estado</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
</div>

<div id="nueva-orden" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
          <h5 class="modal-title text-white" id="title">Nuevo Empleado</h5>
          <button class="close text-white" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <div class="modal-body">
        <p><form method="POST" id="frmOrden">
          <div class="form-group">
              <label for="id_maquina">Maquina</label>
              <input  name="id_orden" id="id_orden" type="hidden">
              <input  name="id_seleccion" id="id_seleccion" type="hidden">
              <select id="id_maquina" disabled selected class="form-control" type="text" name="id_maquina" onchange="getTareasdeOrden();">
              <?php foreach ($data['maquinas'] as $row) { ?>    
                <option value="<?php echo $row['id_maquina']; ?>"><?php echo $row['id_maquina']," - ",$row['nombre']; ?> </option>
              <?php } ?>
              </select>
          </div>
          <div class="form-group">
            <label for="legajo">Tecnico</label>
            <select id="legajo" disabled selected class="form-control" type="text" name="legajo" placeholder="Tecnico">
            <option value="" disabled selected>Selecciona un Tecnico</option>
            <?php foreach ($data['personas'] as $row) { ?>
              <option value="<?php echo $row['legajo']; ?>"><?php echo $row['legajo']," - ",$row['nombre_apellido']; ?> </option>
            <?php } ?>
            </select>
          </div>
          <div class="row" id="fecha_hora">
            <div class="col-md-6">
              <div class="form-group">
                <label for="fecha">Fecha*</label>
                <input  name="fecha" id="fecha" class="form-control" type="date" placeholder="dd-mm-yyyy">
                <small id="horaHelp" class="form-text text-muted">Ingrese la fecha y hora real</small>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="hora">Hora*</label>
                <input  name="hora" id="hora" class="form-control" type="time" placeholder="hh-mm">
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="tiempo_total">Tiempo total*</label>
            <input type="number" id="tiempo_total" name="tiempo_total" step="1" min="0" max="20" placeholder="0" class="form-control">
          </div>  
          <div class="form-group">
            <label for="observaciones">Observaciones*</label>
            <textarea type="text" id="observaciones" name="observaciones" class="form-control" rows="3" placeholder="Ingrese una breve observacion de las tareas realizadas" multiple></textarea>
          </div>
          <div class="form-group">
            <label for="id_tarea">Tareas</label>
            <select id="id_tarea" class="form-control" type="text" name="id_tarea[]" placeholder="Tarea" multiple>
              <label>Tarea</label>
              <option value="none">None</option>
            </select>
            <input id="id_tarea1" name="id_tarea1[]" type="hidden">
          </div>   
          <div class="d-flex justify-content-between">
            <div>
                <button class="btn btn-primary me-2" type="button" onclick="registrarOrden(event);" id="btn-accion">Rechazar</button>
            </div>
            <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Cancelar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include "Views/Templates/footer.php"; ?>