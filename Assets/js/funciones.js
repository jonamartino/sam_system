// Tablas Usuarios, Maquinas
let tblUsuarios, tblMaquinas, tblPersonas, tblTareas;
document.addEventListener("DOMContentLoaded", function () {
    tblUsuarios = $('#tblUsuarios').DataTable({
        ajax: {
            url: base_url + "Usuarios/listar",
            dataSrc: ''
        },
        columns: [
            {
                'data': 'id'
            },
            {
                'data': 'usuario'
            },
            {
                'data': 'legajo'
            },
            {
                'data': 'nombre_completo'
            },
            {
                'data': 'nombre_rol'
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
    tblTareas = $('#tblTareas').DataTable({
        ajax: {
            url: base_url + "Maquinas/listarTareasIndex",
            dataSrc: ''
        },
        columns: [
            {
                'data': 'id_tarea'
            },
            {
                'data': 'nombre_tarea'
            },
            {
                'data': 'tiempo_tarea'
            },
            {
                'data': 'nombre_maquina'
            },
            {
                'data': 'acciones'
            }
        ]
    });

})
document.addEventListener("DOMContentLoaded", function () {
    tblMaquinas = $('#tblMaquinas').DataTable({
        ajax: {
            url: base_url + "Maquinas/listar",
            dataSrc: ''
        },
        columns: [
            {
                'data': 'id_maquina'
            },
            {
                'data': 'nombre'
            },
            {
                'data': 'tipo'
            },
            {
                'data': 'celda_nombre'
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
    tblPersonas = $('#tblPersonas').DataTable({
        ajax: {
            url: base_url + "Personas/listar",
            dataSrc: ''
        },
        columns: [
            {
                'data': 'legajo'
            },
            {
                'data': 'nombre_completo'
            },
            {
                'data': 'dni'
            },
            {
                'data': 'mail'
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
// Usuarios
function frmUsuario() {
    document.getElementById("title").innerHTML = "Nuevo Usuario";
    document.getElementById("btn-accion").innerHTML = "Registrar";
    document.getElementById("claves").classList.remove("d-none");
    document.getElementById("frmUsuario").reset();
    $("#nuevo-usuario").modal("show");
    document.getElementById("id").value = "";
}
function registrarUser(e) {
    e.preventDefault();
    const usuario = document.getElementById("usuario");
    const legajo = document.getElementById("legajo");

    if (usuario.value == "" || legajo.value == "") {
        alertas('Todos los campos son obligatorios', 'warning');
    } else {
        const url = base_url + "Usuarios/registrar";
        const frm = document.getElementById("frmUsuario");
        const http = new XMLHttpRequest();
        http.open("POST", url, true);
        http.send(new FormData(frm));
        http.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);
                const res = JSON.parse(this.responseText);
                $("#nuevo-usuario").modal("hide");
                alertas(res.msg, res.icono);
                tblUsuarios.ajax.reload();
            }
        }
    }
}
function btnEditarUser(id) {
    document.getElementById("title").innerHTML = "Actualizar Usuario";
    document.getElementById("btn-accion").innerHTML = "Modificar";
    const url = base_url + "Usuarios/editar/" + id;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            document.getElementById("id").value = res.id;
            document.getElementById("usuario").value = res.usuario;
            document.getElementById("legajo").value = res.id_persona;
            document.getElementById("rol").value = res.id_rol;
            console.log(document.getElementById("legajo"));
            console.log(res.id_persona);
            document.getElementById("claves").classList.add("d-none");
            $("#nuevo-usuario").modal("show");
        }
    }
}
function btnEliminarUser(id) {
    Swal.fire({
        title: "¿Está seguro de eliminar el usuario?",
        text: "El usuario no se eliminará de forma permanente, solo cambiará el estado a inactivo",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Confirmar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Usuarios/eliminar/" + id;
            const http = new XMLHttpRequest();
            http.open("GET", url, true);
            http.send();
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    tblUsuarios.ajax.reload();
                    alertas(res.msg, res.icono);
                }
            }
        }
    });
}
function btnReingresarUser(id) {
    Swal.fire({
        title: "¿Está seguro de reingresar el usuario?",
        text: "El usuario pasará a estado 'activo' ",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Confirmar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Usuarios/reingresar/" + id;
            const http = new XMLHttpRequest();
            http.open("GET", url, true);
            http.send();
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    tblUsuarios.ajax.reload();
                    alertas(res.msg, res.icono);
                }
            }
        }
    });
}
// Fin Usuarios
// Maquinas
function frmMaquina() {
    document.getElementById("title").innerHTML = "Nueva Maquina";
    document.getElementById("btn-accion").innerHTML = "Registrar";
    document.getElementById("frmMaquina").reset();
    $("#nueva-maquina").modal("show");
    document.getElementById("id_maquina").value = "";

}
function frmTareaEditar(id_tarea) {
    
    console.log(id_tarea);
    document.getElementById("frmTareaEditar").reset();
      console.log(id_tarea.value);
      const url = base_url + "Maquinas/editarTarea/" + id_tarea;
      const http = new XMLHttpRequest();
      http.open("GET", url, true);
      http.send();
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          document.getElementById("id_tarea").value = res.id_tarea;
          document.getElementById("nombre").value = res.nombre;
          document.getElementById("tiempo").value = res.tiempo_tarea;
          $("#editar-tarea").modal("show");
        }
      }
}
function registrarMaquina(e) {
    e.preventDefault();
    const tipo = document.getElementById("tipo");
    const nombre = document.getElementById("nombre");
    const celda_nombre = document.getElementById("celda_nombre");
    console.log(tipo.value, nombre.value, celda_nombre.value);
    if (tipo.value == "" || nombre.value == "" || celda_nombre.value == "") {
        Swal.fire({
            position: 'top-center',
            icon: 'error',
            title: 'Todos los campos son obligatorios',
            showConfirmButton: false,
            timer: 3000
        })
    } else {
        const url = base_url + "Maquinas/registrar";
        const frm = document.getElementById("frmMaquina");
        const http = new XMLHttpRequest();
        http.open("POST", url, true);
        http.send(new FormData(frm));
        http.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                const res = JSON.parse(this.responseText);
                if (res == "si") {
                    Swal.fire({
                        position: 'top-center',
                        icon: 'success',
                        title: 'Maquina registrada con éxito',
                        showConfirmButton: false,
                        timer: 3000
                    })
                    frm.reset();
                    $("#nueva-maquina").modal("hide");
                    tblMaquinas.ajax.reload();
                } else if (res == "modificado") {
                    Swal.fire({
                        position: 'top-center',
                        icon: 'success',
                        title: 'Maquina modificada con éxito',
                        showConfirmButton: false,
                        timer: 3000
                    })
                    frm.reset();
                    $("#nueva-maquina").modal("hide");
                    tblMaquinas.ajax.reload();
                } else {
                    Swal.fire({
                        position: 'top-center',
                        icon: 'error',
                        title: res,
                        showConfirmButton: false,
                        timer: 3000
                    })
                }
            }
        }
    }
}
function getTareasMaquinas() {
    var id_seleccion = document.getElementById("id_tipo").value;
    var selectTareas = document.getElementById("id_tareas");
    console.log(selectTareas.value)
    selectTareas.innerHTML = ''; // Limpiar el contenido actual
    console.log(selectTareas.value);
    if (id_seleccion) {
      const url = base_url + "Maquinas/listarTareas/" + id_seleccion;
      const http = new XMLHttpRequest();
      http.open("GET", url, true);
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          var response = JSON.parse(this.responseText);
          console.log(response);
          if (response && response.length > 0) {
            // Iterar sobre las tareas recibidas y agregarlas al select
            response.forEach(function (tareas) {
              var option = document.createElement("option");
              option.value = tareas.id_tareas;
              option.textContent = tareas.nombre;
              selectTareas.appendChild(option);
            });
          }
        }
      };
      http.send();
    }
}
function frmTareas() {
    document.getElementById("title").innerHTML = "Nueva Tarea";
    document.getElementById("btn-accion").innerHTML = "Registrar";
    document.getElementById("frmTareas").reset();
    document.getElementById("id_tipo").value = '';
    $("#nueva-tarea").modal("show");
}
function registrarTareaMaquina(e) {
    e.preventDefault();
    const id_tipo = document.getElementById("id_tipo");
    const nombre_tarea = document.getElementById("nombre_tarea");
    const tiempo = document.getElementById("tiempo");
  
    console.log(id_tipo.value, nombre_tarea.value, tiempo.value);
    if (id_tipo.value == "" || nombre_tarea.value == "" || tiempo.value == "" ) {
      alertas('Todos los campos son obligatorios', 'warning');
    } else {
      const url = base_url + "Preventivos/registrarTarea";
      const frm = document.getElementById("frmTareas");
      const http = new XMLHttpRequest();
      http.open("POST", url, true);
      http.send(new FormData(frm));
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            const res = JSON.parse(this.responseText);
            $("#nueva-tarea").modal("hide");
            alertas(res.msg, res.icono);
            tblTareas.ajax.reload();
        }
      }
    }
}
function btnEditarTarea(id_tarea) {
document.getElementById("title").innerHTML = "Modificar tarea";
document.getElementById("btn-accion").innerHTML = "Modificar";
const url = base_url + "Maquinas/editarTarea/" + id_tarea;
const http1 = new XMLHttpRequest();
http1.open("GET", url, true);
http1.send();
http1.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
        const res = JSON.parse(this.responseText);
        document.getElementById("id_tipo").value = res.id_tipo;
        document.getElementById("id_tarea").value = res.id_tarea;
        document.getElementById("nombre_tarea").value = res.nombre;
        document.getElementById("tiempo").value = res.tiempo_tarea;
        $("#nueva-tarea").modal("show");

    }
}
}
function btnEliminarTarea(id_tarea) {
    Swal.fire({
        title: "¿Está seguro de eliminar la tarea?",
        text: "Una vez eliminada no podrá ser recuperada",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Confirmar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Maquinas/eliminarTarea/" + id_tarea;
            const http = new XMLHttpRequest();
            http.open("GET", url, true);
            http.send();
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    if (res == "ok") {
                        Swal.fire(
                            'Exito',
                            'La tarea ha sido eliminada',
                            'success'
                        )
                        tblTareas.ajax.reload();
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
function btnEditarMaquina(id_maquina) {
    document.getElementById("title").innerHTML = "Actualizar Maquina";
    document.getElementById("btn-accion").innerHTML = "Modificar";
    const url = base_url + "Maquinas/editar/" + id_maquina;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            document.getElementById("id_maquina").value = res.id_maquina;
            document.getElementById("nombre").value = res.nombre;
            document.getElementById("tipo").value = res.id_tipo;
            document.getElementById("celda_nombre").value = res.id_celda;
            console.log(document.getElementById("celda_nombre"));
            $("#nueva-maquina").modal("show");

        }
    }
}
function btnEliminarMaquina(id_maquina) {
    Swal.fire({
        title: "¿Está seguro de eliminar la maquina?",
        text: "La maquina no se eliminará de forma permanente, solo cambiará el estado a inactivo",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Confirmar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Maquinas/eliminar/" + id_maquina;
            const http = new XMLHttpRequest();
            http.open("GET", url, true);
            http.send();
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    if (res == "ok") {
                        Swal.fire(
                            'Exito',
                            'La maquina ha sido desactivada',
                            'success'
                        )
                        tblMaquinas.ajax.reload();
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
function btnReingresarMaquina(id_maquina) {
    Swal.fire({
        title: "¿Está seguro de reingresar la maquina?",
        text: "La maquina pasará a estado 'activo' ",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Confirmar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Maquinas/reingresar/" + id_maquina;
            const http = new XMLHttpRequest();
            http.open("GET", url, true);
            http.send();
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    if (res == "ok") {
                        Swal.fire(
                            'Exito',
                            'La maquina ha sido reingresada',
                            'success'
                        )
                        tblMaquinas.ajax.reload();
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
// Fin Maquinas

// Personas
function frmPersona() {
    document.getElementById("title").innerHTML = "Nueva Persona";
    document.getElementById("btn-accion").innerHTML = "Registrar";
    document.getElementById("frmPersona").reset();
    $("#nueva-persona").modal("show");
    document.getElementById("legajo").value = "";

}
function myCheck() {
    var checkBox = document.getElementById("discriminator");
    var text = document.getElementById("form-tecnico");
    if (checkBox.checked == true) {
        text.style.display = "block";
    } else {
        text.style.display = "none";
    }
}
function registrarPersona(e) {
    e.preventDefault();
    const legajo = document.getElementById("legajo");
    const dni = document.getElementById("dni");
    const nombre = document.getElementById("nombre");
    const apellido = document.getElementById("apellido");
    const mail = document.getElementById("mail");
    const celular = document.getElementById("celular");
    const id_turno = document.getElementById("id_turno");
    const fecha_nacimiento = document.getElementById("fecha_nacimiento");
    const especialidad = document.getElementById("especialidad");
    const discriminator = document.getElementById("discriminator");
    console.log("discriminator.value= ", discriminator.checked, "legajo= ", legajo.value, ",", dni.value, nombre.value, apellido.value, mail.value, celular.value, id_turno.value, fecha_nacimiento.value);
    if (dni.value == "" || apellido.value == "" || nombre.value == "" ||
        mail.value == "" || celular.value == "" || id_turno.value == "" ||
        fecha_nacimiento.value == "") {
        Swal.fire({
            position: 'top-center',
            icon: 'error',
            title: 'Todos los campos son obligatorios',
            showConfirmButton: false,
            timer: 3000
        })
    } else {
        const url = base_url + "Personas/registrar";
        const frm = document.getElementById("frmPersona");
        const http = new XMLHttpRequest();
        http.open("POST", url, true);
        http.send(new FormData(frm));
        http.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);
                const res = JSON.parse(this.responseText);
                if (res == "si") {
                    Swal.fire({
                        position: 'top-center',
                        icon: 'success',
                        title: 'Empleado registrado con éxito',
                        showConfirmButton: false,
                        timer: 3000
                    })
                    frm.reset();
                    $("#nueva-persona").modal("hide");
                    tblPersonas.ajax.reload();
                } else if (res == "modificado") {
                    Swal.fire({
                        position: 'top-center',
                        icon: 'success',
                        title: 'Empleado modificado con éxito',
                        showConfirmButton: false,
                        timer: 3000
                    })
                    frm.reset();
                    $("#nueva-persona").modal("hide");
                    tblPersonas.ajax.reload();
                } else {
                    Swal.fire({
                        position: 'top-center',
                        icon: 'error',
                        title: res,
                        showConfirmButton: false,
                        timer: 3000
                    })
                }
            }
        }
    }
}
function btnEditarPersona(legajo) {
    document.getElementById("title").innerHTML = "Actualizar Empleado";
    document.getElementById("btn-accion").innerHTML = "Modificar";
    const url = base_url + "Personas/editar/" + legajo;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            console.log(res);
            document.getElementById("legajo").value = res.legajo;
            document.getElementById("dni").value = res.dni;
            document.getElementById("nombre").value = res.nombre;
            document.getElementById("apellido").value = res.apellido;
            document.getElementById("mail").value = res.mail;
            document.getElementById("celular").value = res.celular;
            document.getElementById("id_turno").value = res.id_turno;
            document.getElementById("fecha_nacimiento").value = res.fecha_nacimiento;
            $("#nueva-persona").modal("show");

        }
    }
}
function btnEliminarPersona(legajo) {
    Swal.fire({
        title: "¿Está seguro de desactivar el empleado?",
        text: "El empleado no se eliminará de forma permanente, solo cambiará el estado a inactivo",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Confirmar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Personas/eliminar/" + legajo;
            const http = new XMLHttpRequest();
            http.open("GET", url, true);
            http.send();
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    if (res == "ok") {
                        Swal.fire(
                            'Exito',
                            'El empleado ha sido desactivada',
                            'success'
                        )
                        tblPersonas.ajax.reload();
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
function btnReingresarPersona(legajo) {
    Swal.fire({
        title: "¿Está seguro de activar el empleado?",
        text: "El empleado pasará a estado 'activo' ",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Confirmar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Personas/reingresar/" + legajo;
            const http = new XMLHttpRequest();
            http.open("GET", url, true);
            http.send();
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    if (res == "ok") {
                        Swal.fire(
                            'Exito',
                            'El empleado ha sido activado',
                            'success'
                        )
                        tblPersonas.ajax.reload();
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

function alertas(mensaje, icono) {
    Swal.fire({
        position: 'top-center',
        icon: icono,
        title: mensaje,
        showConfirmButton: false,
        timer: 3000
    })
}
function modificarSistema(){
    const frm = document.getElementById('frmSistema');
    const url = base_url + "Administracion/modificar";
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        const res = JSON.parse(this.responseText);
        alertas(res.msg, res.icono);
      }
    }
}
document.addEventListener('DOMContentLoaded', function() {
    // Función para cargar la configuración y llenar el modal
    function loadConfig() {
        fetch(base_url + "Administracion/getConfig")
            .then(response => response.json())
            .then(config => {
                // Rellenar los campos del modal con la configuración
                document.getElementById('databaseName').value = config.db_name;
                document.getElementById('location').value = config.db_ubicacion;
                document.getElementById('defaultLocation').textContent = config.db_ubicacion;
                
            })
            .catch(error => console.error('Error:', error));
    }

    // Mostrar u ocultar los campos según la opción seleccionada
    document.querySelectorAll('input[name="backupRestoreOption"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            const isRestore = document.getElementById('restoreOption').checked;
            document.getElementById('backupLocationDiv').style.display = isRestore ? 'none' : 'block';
            document.getElementById('restoreFileDiv').style.display = isRestore ? 'block' : 'none';
            document.getElementById('backupButton').style.display = isRestore ? 'none' : 'block';
            document.getElementById('restoreButton').style.display = isRestore ? 'block' : 'none';
        });
    });

    // Evento para el botón de backup
    document.getElementById('backupButton').addEventListener('click', function() {
        const location = document.getElementById('location').value;
        const databaseName = document.getElementById('databaseName').value;

        fetch(base_url + "Administracion/backup", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `location=${encodeURIComponent(location)}&databaseName=${encodeURIComponent(databaseName)}`
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
        })
        .catch(error => console.error('Error:', error));
    });

    // Evento para el botón de restore
    document.getElementById('restoreButton').addEventListener('click', function() {
        const fileInput = document.getElementById('restoreFile');
        const databaseName = document.getElementById('databaseName').value;

        if (!fileInput.files.length) {
            alert('Por favor, selecciona un archivo para restaurar.');
            return;
        }

        const file = fileInput.files[0];
        const formData = new FormData();
        formData.append('databaseName', databaseName);
        formData.append('restoreFile', file);

        // Mostrar indicador de carga
        document.getElementById('loadingIndicator').style.display = 'block';

        fetch(base_url + "Administracion/restore", {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            // Ocultar indicador de carga
            document.getElementById('loadingIndicator').style.display = 'none';
        })
        .catch(error => {
            console.error('Error:', error);
            // Ocultar indicador de carga
            document.getElementById('loadingIndicator').style.display = 'none';
        });
    });

    // Cargar configuración al mostrar el modal
    var backupRestoreModal = document.getElementById('backupRestoreModal');
    backupRestoreModal.addEventListener('show.bs.modal', function (event) {
        loadConfig();
    });
});


