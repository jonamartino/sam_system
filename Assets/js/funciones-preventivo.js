// Tablas Preventivos
let tblPreventivos;
document.addEventListener("DOMContentLoaded", function () {
  tblPreventivos = $('#tblPreventivos').DataTable({
    ajax: {
      url: base_url + "Preventivos/listar",
      dataSrc: ''
    },
    columns: [
      {
        'data': 'id_preventivo'
      },
      {
        'data': 'maq'
      },
      {
        'data': 'nombre_apellido'
      },
      {
        'data': 'fecha'
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

let tblPreventivosInactivos;
document.addEventListener("DOMContentLoaded", function () {
  tblPreventivosInactivos = $('#tblPreventivosInactivos').DataTable({
    ajax: {
      url: base_url + "Preventivos/listarInactivos",
      dataSrc: ''
    },
    columns: [
      {
        'data': 'id_preventivo'
      },
      {
        'data': 'maq'
      },
      {
        'data': 'orden'
      },
      {
        'data': 'fecha'
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


let tblPreventivosAVencer;
document.addEventListener("DOMContentLoaded", function () {
  tblPreventivosAVencer = $('#tblPreventivosAVencer').DataTable({
    ajax: {
      url: base_url + "Preventivos/listarAVencer",
      dataSrc: ''
    },
    columns: [
      {
        'data': 'id_preventivo'
      },
      {
        'data': 'maq'
      },
      {
        'data': 'orden'
      },
      {
        'data': 'fecha'
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


let tblPreventivosVencidos;
document.addEventListener("DOMContentLoaded", function () {
  tblPreventivosVencidos = $('#tblPreventivosVencidos').DataTable({
    ajax: {
      url: base_url + "Preventivos/listarVencidos",
      dataSrc: ''
    },
    columns: [
      {
        'data': 'id_preventivo'
      },
      {
        'data': 'maq'
      },
      {
        'data': 'orden'
      },
      {
        'data': 'fecha'
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




function frmPreventivo() {
  document.getElementById("title").innerHTML = "Nuevo Preventivo";
  document.getElementById("btn-accion").innerHTML = "Registrar";
  document.getElementById("frmPreventivo").reset();
  document.getElementById("id_maquina").value = "";
  document.getElementById("id_maquina").disabled = false;
  document.getElementById("id_tarea").innerHTML = ""; // Limpiar el contenido actual
  $("#nuevo-preventivo").modal("show");
  document.getElementById("id_preventivo").value = "";
}
// Función para obtener las tareas asociadas a la máquina seleccionada
function getTareas() {
  var id_seleccion = document.getElementById("id_maquina").value;
  var selectTareas = document.getElementById("id_tarea");
  selectTareas.innerHTML = ""; // Limpiar el contenido actual
  console.log(id_seleccion);
  if (id_seleccion) {
    const url = base_url + "Preventivos/listarTareas/" + id_seleccion;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        var response = JSON.parse(this.responseText);
        console.log(response);
        if (response && response.length > 0) {
          // Iterar sobre las tareas recibidas y agregarlas al select
          response.forEach(function (tarea) {
            var option = document.createElement("option");
            option.value = tarea.id_tarea;
            option.textContent = tarea.nombre;
            selectTareas.appendChild(option);
          });
        }
      }
    };
    http.send();
  }
}
function registrarPreventivo(e) {
  e.preventDefault();
  const id_maquina = document.getElementById("id_maquina");
  console.log(id_maquina.value);
  const legajo = document.getElementById("legajo");
  const frecuencia = document.getElementById("frecuencia");
  const fecha_programada = document.getElementById("fecha_programada");
  const hora_programada = document.getElementById("hora_programada");
  const selectElement = document.getElementById("id_tarea");
  const descripcion = document.getElementById("descripcion");
  const selectedOptions = selectElement.selectedOptions;
  const selectedValues = Array.from(selectedOptions).map(option => option.value);
  var fechaIngresada = new Date(fecha_programada.value + 'T' + hora_programada.value);
  var fechaActual = new Date();

  console.log("fecha correcta?: ", fechaIngresada <= fechaActual, selectedValues, id_maquina.value, legajo.value, fecha_programada.value, hora_programada.value, descripcion.value, frecuencia.value);
  if (id_maquina.value == "" || legajo.value == "" || fecha_programada.value == "" ||
    hora_programada.value == "" || legajo.value == "" || descripcion.value == "") {
    alertas('Todos los campos son obligatorios', 'warning');
  } else if (selectElement.value == "") {
    alertas('Seleccione al menos una tarea', 'warning');
  } else if (fechaIngresada <= fechaActual) {
    alertas('La fecha debe ser posterior a la actual', 'warning');
  } else {
    const url = base_url + "Preventivos/registrar";
    const frm = document.getElementById("frmPreventivo");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        const res = JSON.parse(this.responseText);
        $("#nuevo-preventivo").modal("hide");
        tblPreventivos.ajax.reload();
        alertas(res.msg, res.icono);
      }
    }
  }
}
function btnEditarPreventivo(id_preventivo) {
  document.getElementById("title").innerHTML = "Actualizar Preventivo";
  document.getElementById("btn-accion").innerHTML = "Modificar";
  const url = base_url + "Preventivos/editar/" + id_preventivo;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      document.getElementById("id_tarea").innerHTML = ""; // Limpiar el contenido actual
      document.getElementById("id_preventivo").value = res.id_preventivo;
      document.getElementById("id_maquina").value = res.id_maquina;
      document.getElementById("legajo").value = res.legajo;
      document.getElementById("frecuencia").value = res.frecuencia;
      document.getElementById("fecha_programada").value = res.fecha_programada;
      document.getElementById("hora_programada").value = res.hora_programada;
      document.getElementById("descripcion").value = res.descripcion;
      // Obtener el elemento select
      var select = document.getElementById("id_tarea");
      // Arreglo para almacenar IDs de tareas seleccionadas
      var idsSeleccionados = [];
      // Recorrer las tareas disponibles
      res['tareas_maquina'].forEach(function (tarea) {
        var opt = tarea.nombre;
        var el = document.createElement("option");
        el.textContent = opt;
        el.value = tarea.id_tarea; // Asignar el ID de la tarea como valor
        // Verificar si la tarea está seleccionada
        if (res['preventivos_tareas'].some(function (selTarea) {
          return selTarea.id_tarea === tarea.id_tarea;
        })) {
          el.selected = true;
          idsSeleccionados.push(tarea.id_tarea); // Agregar ID al arreglo de seleccionados
        }
        // Agregar la opción al select
        select.appendChild(el);
      });
      // Mostrar el modal después de completar la construcción del select
      $("#nuevo-preventivo").modal("show");
      // Aquí puedes usar 'idsSeleccionados' para manejar los IDs seleccionados según sea necesario
      console.log("IDs de tareas seleccionadas:", idsSeleccionados);
    }
  }
}
function btnRevisarPreventivo(id_preventivo) {// Crear y agregar el botón "Aprobar" si no existe
  document.getElementById("title").innerHTML = "Revisar Preventivo";
  let btnAprobar = document.getElementById("btn-accion1");
  if (!btnAprobar) {
    btnAprobar = document.createElement("button");
    btnAprobar.id = "btn-accion1";
    btnAprobar.className = "btn btn-success me-2"; // Añadir margen a la derecha para espaciar los botones
    btnAprobar.innerHTML = "Aprobar";
    btnAprobar.addEventListener("click", function(event) {
      btnAprobarPreventivo(event, id_preventivo);
    }); // Asignar función para manejar clic de aprobar
    // Insertar el botón "Aprobar" antes del botón "Rechazar"
    document.getElementById("btn-accion").parentNode.insertBefore(btnAprobar, document.getElementById("btn-accion"));
  } else {
    btnAprobar.addEventListener("click", function(event) {
      btnAprobarClick(event, id_preventivo);
    }); // Asignar función para manejar clic de aprobar
  }

  var btnRechazar = document.getElementById("btn-accion");
  btnRechazar.innerHTML = "Rechazar";
  btnRechazar.classList.remove("btn-primary"); // Remover la clase btn-primary
  btnRechazar.classList.add("btn-danger"); // Agregar la clase btn-danger
  btnRechazar.setAttribute("onclick", "btnRechazarPreventivo(" + id_preventivo + ")"); // Cambiar función a btnRechazar

  const url = base_url + "Preventivos/editar/" + id_preventivo;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      document.getElementById("id_tarea").innerHTML = ""; // Limpiar el contenido actual
      document.getElementById("id_preventivo").value = res.id_preventivo;
      document.getElementById("id_maquina").value = res.id_maquina;
      document.getElementById("id_maquina").disabled = true; // Deshabilitar el campo de máquina
      document.getElementById("legajo").value = res.legajo;
      document.getElementById("legajo").disabled = true; // Deshabilitar el campo de legajo
      document.getElementById("frecuencia").value = res.frecuencia;
      document.getElementById("frecuencia").disabled = true; // Deshabilitar el campo de frecuencia
      document.getElementById("fecha_programada").value = res.fecha_programada;
      document.getElementById("fecha_programada").disabled = true; // Deshabilitar el campo de fecha programada
      document.getElementById("hora_programada").value = res.hora_programada;
      document.getElementById("hora_programada").disabled = true; // Deshabilitar el campo de hora programada
      document.getElementById("descripcion").value = res.descripcion;
      document.getElementById("descripcion").disabled = true; // Deshabilitar el campo de descripción
      // Obtener el elemento select
      var select = document.getElementById("id_tarea");
      select.disabled = true; // Deshabilitar el select de tareas
      // Arreglo para almacenar IDs de tareas seleccionadas
      var idsSeleccionados = [];
      // Recorrer las tareas disponibles
      res['tareas_maquina'].forEach(function(tarea) {
        var opt = tarea.nombre;
        var el = document.createElement("option");
        el.textContent = opt;
        el.value = tarea.id_tarea; // Asignar el ID de la tarea como valor
        // Verificar si la tarea está seleccionada
        if (res['preventivos_tareas'].some(function(selTarea) {
          return selTarea.id_tarea === tarea.id_tarea;
        })) {
          el.selected = true;
          idsSeleccionados.push(tarea.id_tarea); // Agregar ID al arreglo de seleccionados
        }
        // Agregar la opción al select
        select.appendChild(el);
      });
      // Mostrar el modal después de completar la construcción del select
      $("#nuevo-preventivo").modal("show");
      // Aquí puedes usar 'idsSeleccionados' para manejar los IDs seleccionados según sea necesario
      console.log("IDs de tareas seleccionadas:", idsSeleccionados);
    }
  }
}
function btnRechazarPreventivo(id_preventivo) {
  Swal.fire({
    title: "¿Está seguro de rechazar el preventivo?",
    text: "El mantenimiento preventivo pasara a estado rechazado para su revisión",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Confirmar",
    cancelButtonText: "Cancelar"
  }).then((result) => {
    if (result.isConfirmed) {
      const url = base_url + "Preventivos/rechazar/" + id_preventivo;
      const http = new XMLHttpRequest();
      http.open("GET", url, true);
      http.send();
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          tblPreventivos.ajax.reload();
          alertas(res.msg, res.icono);
        }
      }
      $('#nuevo-preventivo').modal('hide');
    }
  });
}
function btnAprobarPreventivo(e, id_preventivo) {
  e.preventDefault(); // Prevenir el comportamiento predeterminado del botón (recargar página)
  Swal.fire({
    title: "¿Está seguro de aprobar el mantenimiento preventivo?",
    text: "El mantenimiento preventivo pasará a estado 'activo' ",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Confirmar",
    cancelButtonText: "Cancelar"
  }).then((result) => {
    if (result.isConfirmed) {
      const url = base_url + "Preventivos/aprobar/" + id_preventivo;
      const http = new XMLHttpRequest();
      http.open("GET", url, true);
      http.send();
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          tblPreventivos.ajax.reload();
          alertas(res.msg, res.icono);
        }
      }
      $('#nuevo-preventivo').modal('hide');
    }
  });
}
function btnCargarOrden(id_preventivo) {
  Swal.fire({
    title: "¿Cargar la Orden de Manteniemiento?",
    text: "El mantenimiento preventivo pasará a estado 'en curso' ",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Confirmar",
    cancelButtonText: "Cancelar"
  }).then((result) => {
    if (result.isConfirmed) {
      const url = base_url + "Preventivos/cargarOrden/" + id_preventivo;
      const http = new XMLHttpRequest();
      http.open("GET", url, true);
      http.send();
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          console.log(this.responseText);
          const res = JSON.parse(this.responseText);
          if (res == "ok") {
            Swal.fire({
              position: 'top-center',
              icon: 'success',
              title: 'Orden de Mantenimietno generada con éxito',
              showConfirmButton: false,
              timer: 3000
            })
            console.log("recargar");
            tblPreventivos.ajax.reload();
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

function btnCancelarPreventivo(id_preventivo) {
  Swal.fire({
    title: "¿Está seguro de cancelar el preventivo?",
    text: "El mantenimiento preventivo pasara a estado cancelado y no podrá ser activado de nuevo",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Confirmar",
    cancelButtonText: "Cancelar"
  }).then((result) => {
    if (result.isConfirmed) {
      const url = base_url + "Preventivos/cancelar/" + id_preventivo;
      const http = new XMLHttpRequest();
      http.open("GET", url, true);
      http.send();
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          tblPreventivosInactivos.ajax.reload();
          alertas(res.msg, res.icono);
        }
      }
    }
  });
}


