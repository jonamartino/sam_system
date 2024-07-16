<?php include "Views/Templates/header.php"; ?>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active" aria-current="page">Tareas</li>
</ol>                 
<button class="btn btn-info mb-2" type="button" onclick="frmTareas();">Nueva Tarea</button>                    

<div class="table-responsive-xl">
    <table class="table table-hover w-100 table-sm" data-page-length='15' id="tblTareas">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>  
                <th>Nombre Tarea</th>
                <th>Tiempo</th>
                <th>Tipo Maquina</th>   
                <th></th> 
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<div id="nueva-tarea" class="modal fade" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-white" id="title">Tareas</h5>
                <button class="close text-white" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="frmTareas">
                    <div class="form-group">
                        <label for="id_tipo">Tipo de máquina</label>
                        <select id="id_tipo" selected class="form-control" type="text" name="id_tipo" placeholder="Tipo">
                        <option value="" disabled selected>Selecciona un tipo de máquina</option>
                            <?php foreach ($data['tipos'] as $row) { ?>
                                <option value="<?php echo $row['id_tipo']; ?>"><?php echo $row['id_tipo']," - ",$row['nombre']; ?> </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nombre_tarea">Nombre de la tarea</label>
                        <input  name="id_tarea" id="id_tarea" type="hidden">
                        <textarea id="nombre_tarea" class="form-control" type="text" name="nombre_tarea" rows="2" placeholder="Nombre descriptivo de la tarea" multiple></textarea>
                    </div>
                    <div class="form-group">
                        <label for="tiempo">Tiempo de realización</label>
                        <input type="number" id="tiempo" name="tiempo" step="1" min="0" max="20" placeholder="0" class="form-control">
                    </div>  
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-info mt-2" type="button" onclick="registrarTareaMaquina(event);" id="btn-accion">Guardar</button>
                <button class="btn btn-secondary mt-2" type="button" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<?php include "Views/Templates/footer.php"; ?>