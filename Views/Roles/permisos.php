<?php include "Views/Templates/header.php"; ?>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active" aria-current="page">Permisos</li>
</ol>

<button class="btn btn-primary mb-2 btn-sm" type="button" onclick="frmPermiso();">Nuevo Permiso</button>
<div class="table-responsive-xl">
<table class="table table-hover w-100 table-sm" data-page-length='14' id="tblPermisos">

        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<div id="nuevo-permiso" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="title">Nuevo Permiso</h5>
                <button class="close text-white" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="frmPermiso">
                    <div class="form-group">
                        <label for="id_permiso">ID</label>
                        <input id="id_permiso" class="form-control" type="text" name="id_permiso" readonly >
                    </div>
                    <div class="form-group">
                        <label for="nombre_permiso">Nombre del Permiso</label>
                        <input type="hidden" id="id" name="id">
                        <input id="nombre_permiso" class="form-control" type="text" name="nombre_permiso" placeholder="Nombre del Permiso">
                    </div>
                    <div class="form-group">
                        <label for="descripcion_permiso">Descripción del Permiso</label>
                        <input id="descripcion_permiso" class="form-control" type="text" name="descripcion_permiso" placeholder="Descripción del Permiso">
                    </div>
                    <button class="btn btn-primary" type="button" onclick="registrarPermiso(event);" id="btn-accion">Registrar</button>
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Cancelar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include "Views/Templates/footer.php"; ?>
