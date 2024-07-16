// Función para enviar el formulario de creación de roles
let tblPermisos;
document.addEventListener("DOMContentLoaded", function () {
  tblRoles = $('#tblRoles').DataTable({
    ajax: {
      url: base_url + "Roles/listar",
      dataSrc: ''
    },
    columns: [
      {
        'data': 'id'
      },
      {
        'data': 'nombre'
      },
      {
        'data': 'descripcion'
      },
      {
        'data': 'estado'
      },
      {
        'data': 'acciones'
      }
    ]
  });

})
document.addEventListener("DOMContentLoaded", function () {
  tblPermisos = $('#tblPermisos').DataTable({
    ajax: {
      url: base_url + "Roles/listarPermisos",
      dataSrc: ''
    },
    columns: [
      {
        'data': 'id'
      },
      {
        'data': 'nombre'
      },
      {
        'data': 'descripcion'
      },
      {
        'data': 'acciones'
      }
    ]
  });

})
function frmRol() {
  document.getElementById("title").innerHTML = "Nuevo Rol";
  document.getElementById("btn-accion").innerHTML = "Registrar";
  document.getElementById("frmRol").reset();
  $("#nuevo-rol").modal("show");
  document.getElementById("id").value = "";
}
function frmPermiso() {
  document.getElementById("title").innerHTML = "Nuevo Permiso";
  document.getElementById("btn-accion").innerHTML = "Registrar";
  document.getElementById("frmPermiso").reset();
  $("#nuevo-permiso").modal("show");
  document.getElementById("id").value = "";
}
function registrarRol(e) {
  e.preventDefault();
  const nombre = document.getElementById("nombre");
  const descripcion = document.getElementById("descripcion");
  if (nombre.value == "" || descripcion.value == "") {
    alertas('Todos los campos son obligatorios', 'warning');
  } else {
    const url = base_url + "Roles/registrar";
    const frm = document.getElementById("frmRol");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        console.log(this.responseText);
        const res = JSON.parse(this.responseText);
        $("#nuevo-rol").modal("hide");
        alertas(res.msg, res.icono);
        tblRoles.ajax.reload();
      }
    }
  }
}
function registrarPermiso(e) {
  e.preventDefault();
  const nombre = document.getElementById("nombre_permiso");
  const descripcion = document.getElementById("descripcion_permiso");
  if (nombre.value == "" || descripcion.value == "") {
    alertas('Todos los campos son obligatorios', 'warning');
  } else {
    const url = base_url + "Roles/registrarPermiso";
    const frm = document.getElementById("frmPermiso");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        console.log(this.responseText);
        const res = JSON.parse(this.responseText);
        $("#nuevo-permiso").modal("hide");
        alertas(res.msg, res.icono);
        tblPermisos.ajax.reload();
      }
    }
  }
}
function btnEditarRol(id) {
  document.getElementById("title").innerHTML = "Modificar Rol";
  document.getElementById("btn-accion").innerHTML = "Confirmar";
  const url = base_url + "Roles/editar/" + id;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      document.getElementById("id_rol").value = res.id;
      document.getElementById("nombre").value = res.nombre;
      document.getElementById("descripcion").value = res.descripcion;
      $("#nuevo-rol").modal("show");
    }
  }
}
function btnEditarPermiso(id) {
  document.getElementById("title").innerHTML = "Modificar Permiso";
  document.getElementById("btn-accion").innerHTML = "Confirmar";
  const url = base_url + "Roles/editarPermiso/" + id;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      document.getElementById("id_permiso").value = res.id;
      document.getElementById("nombre_permiso").value = res.nombre;
      document.getElementById("descripcion_permiso").value = res.descripcion;
      $("#nuevo-permiso").modal("show");
    }
  }
}
function frmPermisos(id) {
  document.getElementById("title").innerHTML = "Asignar Permisos";
  document.getElementById("btn-accion-permisos").innerHTML = "Guardar";
  const url = base_url + "Roles/editarRolPermisos/" + id;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      document.getElementById("id_rolpermiso").value = res.id;
      document.getElementById("nombre_rol").value = res.nombre;
      document.getElementById("descripcion_rol").value = res.descripcion;

      // Cargar permisos disponibles y asignados
      const permisosDisponibles = document.getElementById("listPermisosDisponibles");
      const permisosAsignados = document.getElementById("listPermisosAsignados");

      permisosDisponibles.innerHTML = '';
      permisosAsignados.innerHTML = '';

      res.permisosNoAsignados.forEach(permiso => {
        const option = document.createElement("option");
        option.value = permiso.id;
        option.text = permiso.nombre;
        console.log(option.text);
        permisosDisponibles.appendChild(option);
      });
      res.permisosAsignados.forEach(permiso => {
          const option = document.createElement("option");
          option.value = permiso.id;
          option.text = permiso.nombre;
          console.log(option.text);
          permisosAsignados.appendChild(option);
      });

      $("#asignar-permisos").modal("show");
    }
  }
}
function agregarPermiso() {
  const permisosDisponibles = document.getElementById("listPermisosDisponibles");
  const permisosAsignados = document.getElementById("listPermisosAsignados");

  const opcionesSeleccionadas = [...permisosDisponibles.selectedOptions];
  opcionesSeleccionadas.forEach(option => {
    permisosAsignados.appendChild(option);
  });
}
function quitarPermiso() {
  const permisosAsignados = document.getElementById("listPermisosAsignados");

  const opcionesSeleccionadas = [...permisosAsignados.selectedOptions];
  opcionesSeleccionadas.forEach(option => {
    document.getElementById("listPermisosDisponibles").appendChild(option);
  });
}
function btnEliminarPermiso(id) {
  Swal.fire({
      title: "¿Está seguro de eliminar el permiso?",
      text: "Una vez eliminado no podrá ser recuperado",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Confirmar",
      cancelButtonText: "Cancelar"
  }).then((result) => {
      if (result.isConfirmed) {
          const url = base_url + "Roles/eliminarPermiso/" + id;
          const http = new XMLHttpRequest();
          http.open("GET", url, true);
          http.send();
          http.onreadystatechange = function () {
              if (this.readyState == 4 && this.status == 200) {
                  const res = JSON.parse(this.responseText);
                  if (res == "ok") {
                      Swal.fire(
                          'Exito',
                          'El permiso ha sido eliminado',
                          'success'
                      )
                      tblPermisos.ajax.reload();
                  } else {
                      Swal.fire(
                          'Error!',
                          res,
                          'error'
                      );
                  }
              }
          }
      }
  });
}
function guardarPermisos(e) {
  e.preventDefault();
  const permisosAsignados = document.getElementById("listPermisosAsignados").options;
  const permisos = Array.from(permisosAsignados).map(option => option.value);
  const idRol = document.getElementById("id_rolpermiso").value; // Obtener el ID del rol

  const url = base_url + "Roles/guardarPermisos";
  const formData = new FormData();
  formData.append('id_rolpermiso', idRol);
  formData.append('permisos', JSON.stringify(permisos)); // Convertir permisos a JSON y agregar al FormData

  const http = new XMLHttpRequest();
  http.open("POST", url, true);
  http.send(formData);
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      console.log('Permisos asignados:', permisos);
      console.log(this.responseText);
      const res = JSON.parse(this.responseText);
      $("#asignar-permisos").modal("hide");
      alertas(res.msg, res.icono);
      tblRoles.ajax.reload();
    }
  }
}
