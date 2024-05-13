// Tablas Preventivos
let tblOrdenes;
document.addEventListener("DOMContentLoaded", function () {
  tblOrdenes = $('#tblOrdenes').DataTable({
    ajax: {
      url: base_url + "Ordenes/listar",
      dataSrc: ''
    },
    columns: [
      {
        'data': 'id_orden'
      },
      {
        'data': 'preventivo'
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
        'data': 'tiempo_estimado'
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

// Función para obtener las tareas asociadas a la orden seleccionada
function getTareasdeOrden() {
  var id_seleccion = document.getElementById("id_orden").value;
  var selectTareas = document.getElementById("id_tarea");
  selectTareas.innerHTML = ""; // Limpiar el contenido actual
  console.log(id_seleccion);
  if (id_seleccion) {
    const url = base_url + "Ordenes/listarTareas/" + id_seleccion;
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

function btnIngresarOrden(id_orden) {
  document.getElementById("title").innerHTML = "Cargar Orden de Mantenimietno";
  document.getElementById("btn-accion").innerHTML = "Cargar Orden";
  const url = base_url + "Ordenes/editar/" + id_orden;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      document.getElementById("id_tarea").innerHTML = ""; // Limpiar el contenido actual
      document.getElementById("id_orden").value = res.id_orden;
      document.getElementById("id_maquina").value = res.maquina;
      document.getElementById("tiempo_total").value = res.tiempo_estimado;
      document.getElementById("legajo").value = res.id_tecnico;
      document.getElementById("fecha").value = res.fecha_programada;
      document.getElementById("hora").value = res.hora_programada;
      // Obtener el elemento select
      var select = document.getElementById("id_tarea");
      // Arreglo para almacenar IDs de tareas seleccionadas
      var idsSeleccionados = [];
      // Recorrer las tareas disponibles
      res['ordenes_tareas'].forEach(function (tarea) {
        var opt = tarea.nombre;
        var el = document.createElement("option");
        el.textContent = opt;
        el.value = tarea.id_tarea; // Asignar el ID de la tarea como valor
        // Verificar si la tarea está seleccionada
        if (res['ordenes_tareas'].some(function (selTarea) {
          return selTarea.id_tarea === tarea.id_tarea;
        })) {
          el.selected = true;
          idsSeleccionados.push(tarea.id_tarea); // Agregar ID al arreglo de seleccionados
        }
        // Agregar la opción al select
        select.appendChild(el);
      });
      // Aquí puedes usar 'idsSeleccionados' para manejar los IDs seleccionados según sea necesarior la construcción del select
      $("#nueva-orden").modal("show");
      // Aquí puedes usar 'idsSeleccionados' para manejar los IDs seleccionados según sea necesario
      console.log("IDs de tareas seleccionadas:", idsSeleccionados);
    }
  }
}

function registrarOrden(e) {
  e.preventDefault();
  const fecha = document.getElementById("fecha");
  const hora = document.getElementById("hora");
  const selectElement = document.getElementById("id_tarea");
  const observaciones = document.getElementById("observaciones");
  const tiempo_total = document.getElementById("tiempo_total");
  const selectedOptions = selectElement.selectedOptions;
  const selectedValues = Array.from(selectedOptions).map(option => option.value);
  console.log(selectedValues, fecha.value, hora.value, observaciones.value, tiempo_total.value);
  if (fecha.value == "" || hora.value == "" || observaciones.value == "" ||
    tiempo_total.value == "") {
    Swal.fire({
      position: 'top-center',
      icon: 'error',
      title: 'Todos los campos son obligatorios',
      showConfirmButton: false,
      timer: 3000
    })
  } else if (selectElement.value == "") {
    Swal.fire({
      position: 'top-center',
      icon: 'error',
      title: 'Seleccione al menos una tarea',
      showConfirmButton: false,
      timer: 3000
    })
  } else {
    const url = base_url + "Ordenes/registrar";
    const frm = document.getElementById("frmOrden");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        console.log(this.responseText);
        const res = JSON.parse(this.responseText);
        if (res == "modificado") {
          Swal.fire({
            position: 'top-center',
            icon: 'success',
            title: 'Preventivo modificado con éxito',
            showConfirmButton: false,
            timer: 3000
          })
          frm.reset();
          $("#nueva-orden").modal("hide");
          tblOrdenes.ajax.reload();
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

function btnAprobarOrden(id_orden) {
  Swal.fire({
    title: "¿Está seguro de aprobar la Orden de Mantenimiento?",
    text: "El mantenimiento preventivo pasará a estado 'aprobado' ",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Confirmar",
    cancelButtonText: "Cancelar"
  }).then((result) => {
    if (result.isConfirmed) {
      const url = base_url + "Ordenes/aprobar/" + id_orden;
      const http = new XMLHttpRequest();
      http.open("GET", url, true);
      http.send();
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          if (res == "ok") {
            Swal.fire(
              'Exito',
              'La Orden de Mantenimiento fue aprobada',
              'success'
            )
            tblOrdenes.ajax.reload();
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

function btnRechazarOrden(id_orden) {
  Swal.fire({
    title: "¿Está seguro de rechazar la Orden de Mantenimiento?",
    text: "El mantenimiento preventivo pasará a estado 'rechazado' ",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Confirmar",
    cancelButtonText: "Cancelar"
  }).then((result) => {
    if (result.isConfirmed) {
      const url = base_url + "Ordenes/rechazar/" + id_orden;
      const http = new XMLHttpRequest();
      http.open("GET", url, true);
      http.send();
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          if (res == "ok") {
            Swal.fire(
              'Exito',
              'La Orden de Mantenimiento fue rechazada',
              'success'
            )
            tblOrdenes.ajax.reload();
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

function btnCompletarOrden(id_orden) {
  Swal.fire({
    title: "¿Está seguro de completar la Orden de Mantenimiento?",
    text: "La Orden de Mantenimiento pasará a estado 'Completada' ",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Confirmar",
    cancelButtonText: "Cancelar"
  }).then((result) => {
    if (result.isConfirmed) {
      const url = base_url + "Ordenes/completar/" + id_orden;
      const http = new XMLHttpRequest();
      http.open("GET", url, true);
      http.send();
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          if (res == "ok") {
            Swal.fire(
              'Exito',
              'La Orden de Mantenimiento fue completada',
              'success'
            )
            tblOrdenes.ajax.reload();
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

function btnCancelarOrden(id_orden) {
  Swal.fire({
    title: "¿Está seguro de cancelar la Orden de Mantenimiento?",
    text: "La Orden de Mantenimiento pasará a estado 'cancelado' de manera definitiva",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Confirmar",
    cancelButtonText: "Cancelar"
  }).then((result) => {
    if (result.isConfirmed) {
      const url = base_url + "Ordenes/cancelar/" + id_orden;
      const http = new XMLHttpRequest();
      http.open("GET", url, true);
      http.send();
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          if (res == "ok") {
            Swal.fire(
              'Exito',
              'La Orden de Mantenimiento fue cancelada',
              'success'
            )
            tblOrdenes.ajax.reload();
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

