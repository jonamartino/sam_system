<?php include "Views/Templates/header.php"; ?>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active" aria-current="page">Roles</li>
</ol>

<button class="btn btn-primary mb-2" type="button" onclick="frmRol();"><i class="fa-solid fa-user-plus"></i></button>
<div class="table-responsive-xl">
    <table class="table table-light w-100" id="tblRoles">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<div id="nuevo-rol" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="title">Nuevo Rol</h5>
                <button class="close text-white" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="frmRol">
                    <div class="form-group">
                        <label for="id_rol">ID</label>
                        <input id="id_rol" class="form-control" type="text" name="id_rol" readonly >
                    </div>
                    <div class="form-group">
                        <label for="nombre">Nombre del rol</label>
                        <input type="hidden" id="id" name="id">
                        <input id="nombre" class="form-control" type="text" name="nombre" placeholder="Nombre del rol">
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripción del rol</label>
                        <input id="descripcion" class="form-control" type="text" name="descripcion" placeholder="Descripción del rol">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="button" onclick="registrarRol(event);" id="btn-accion">Registrar</button>
                <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<div id="asignar-permisos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="title">Asignar Permisos</h5>
                <button class="close text-white" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="frmPermisos">
                    <div class="form-group">
                        <label for="id_rolpermiso">ID</label>
                        <input id="id_rolpermiso" class="form-control" type="text" name="id_rolpermiso" readonly>
                    </div>
                    <div class="form-group">
                        <label for="nombre_rol">Nombre del permiso</label>
                        <input id="nombre_rol" class="form-control" type="text" name="nombre" placeholder="Nombre del rol">
                    </div>
                    <div class="form-group">
                        <label for="descripcion_rol">Descripción del permiso</label>
                        <input id="descripcion_rol" class="form-control" type="text" name="descripcion" placeholder="Descripción del rol">
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <label for="listPermisosDisponibles">Permisos disponibles</label>
                            <select multiple class="form-control" id="listPermisosDisponibles">
                            </select>
                        </div>
                        <div class="col-md-2 d-flex flex-column align-items-center justify-content-center">
                            <div style="height: 35px;"></div>
                            <button type="button" class="btn btn-primary mb-2 btn-sm " onclick="agregarPermiso()">></button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="quitarPermiso()"><</button>
                        </div>
                        <div class="col-md-5">
                            <label for="listPermisosAsignados">Permisos asignados</label>
                            <select multiple class="form-control" id="listPermisosAsignados"></select>
                        </div>
                    </div>
                    <button class="btn btn-info me-2 btn-sm btn-block" type="button" data-bs-target="#staticBackdrop" onclick="frmPermiso();">Crear nuevo permiso</button>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary mt-2" type="button" onclick="guardarPermisos(event);" id="btn-accion-permisos">Guardar</button>
                <button class="btn btn-danger mt-2" type="button" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<div id="nuevo-permiso" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
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
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="button" onclick="registrarPermiso(event);" id="btn-accion">Registrar</button>
                <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<?php include "Views/Templates/footer.php"; ?>
