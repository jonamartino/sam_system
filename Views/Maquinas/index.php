<?php include "Views/Templates/header.php"; ?>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active" aria-current="page">Maquinas</li>
</ol>
<?php if ($data['verificarAgregar']): ?>
<button class="btn btn-primary mb-2" type="button" onclick="frmMaquina();">Nueva máquina</button>

<?php endif; ?>
<div class="table-responsive-xl">
    <table class="table table-light w-100" id="tblMaquinas">
        <thead class="thead-dark">
            <tr>
                <th>Numero Maquina</th>  
                <th>Maquina</th>
                <th>Tipo</th>
                <th>Celda</th>
                <th class="w-auto">Estado</th>   
                <th></th> 
            </tr>
        </thead>    
        <tbody>
        </tbody>
    </table>
</div>
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
                        <label for="nombre">Nombre Maquina</label>
                        <input  name="id_maquina" id="id_maquina" type="hidden">
                        <input  name="nombre" id="nombre" class="form-control" type="text" placeholder="Nombre o descripcion de la Maquina">
                    </div>
                <div class="form-group">
                        <label for="tipo">Tipo</label>
                        <select id="tipo" class="form-control" type="text" name="tipo" placeholder="tipo">
                        <label>Tipo</label>
                        <option value="" disabled selected >Elegir Tipo</option>
                        <?php foreach ($data['tipo_maquina'] as $row) { ?>
                            <option value="<?php echo $row['id_tipo']; ?>"> <?php echo $row['nombre']; ?> </option>
                        <?php } ?>
                        <select>
                    </div>
                    <div class="form-group">
                        <label for="celda_nombre">Celda</label>
                        <select id="celda_nombre" class="form-control" type="text" name="celda_nombre" placeholder="Celda">
                        <label>Celda</label>
                        <option value="" disabled selected >Elegir Celda</option>
                        <?php foreach ($data['celdas'] as $row) { ?>
                            <option value="<?php echo $row['id_celda']; ?>"> <?php echo $row['id_celda']," - ",$row['celda_nombre']; ?> </option>
                        <?php } ?>
                        <select>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="button" onclick="registrarMaquina(event);" id="btn-accion">Registrar</button>
                <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<?php include "Views/Templates/footer.php"; ?>
