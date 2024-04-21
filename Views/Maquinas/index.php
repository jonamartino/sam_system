<?php include "Views/Templates/header.php"; ?>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active" aria-current="page">Maquinas</li>
</ol>
<button class="btn btn-primary mb-2" type="button" onclick="frmMaquina();"><i class="fa-solid fa-user-plus"></i></button>
<table class="table table-light" id="tblMaquinas">
    <thead class="thead-dark">
        <tr>
            <th>id</th>
            <th>Numero Maquina</th>  
            <th>Maquina</th>
            <th>Celda</th>
            <th>Estado</th>   
            <th></th> 
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<div id="nueva-maquina" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="title">Nueva Maquina</h5>
                <button class="close text-white" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><form method="POST" id="frmMaquina">
                    <div class="form-group">
                        <label for="maq_nombre">Nombre de Maquina</label>
                        <input name="id_maquina" id="id_maquina" type="hidden">
                        <input name="maq_nombre" id="maq_nombre" class="form-control" type="text"  placeholder="Nombre del equipo">
                    </div>
                    <div class="form-group">
                        <label for="id_local">Numero Identificador de Maquina</label>
                        <input  name="id_local" id="id_local" class="form-control" type="text" placeholder="Numero unico de identifiacion">
                    </div>
                    <div class="form-group">
                        <label for="celda_nombre">Celda</label>
                        <select id="celda_nombre" class="form-control" type="text" name="celda_nombre" placeholder="Celda">
                        <label>Celda</label>
                        <?php foreach ($data['celdas'] as $row) { ?>
                            <option value="<?php echo $row['id_celda']; ?>"> <?php echo $row['id_celda']," - ",$row['celda_nombre']; ?> </option>
                        <?php } ?>
                        <select>
                    </div>
                    <button class="btn btn-primary" type="button" onclick="registrarMaquina(event);" id="btn-accion">Registrar</button>
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Cancelar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include "Views/Templates/footer.php"; ?>
