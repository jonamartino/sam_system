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
  const fecha_programada = document.getElementById("fecha_programada");
  const hora_programada = document.getElementById("hora_programada");
  const selectElement = document.getElementById("id_tarea");
  const descripcion = document.getElementById("descripcion");
  const selectedOptions = selectElement.selectedOptions;
  const selectedValues = Array.from(selectedOptions).map(option => option.value);
  console.log(selectedValues, id_maquina.value, legajo.value, fecha_programada.value, hora_programada.value, descripcion.value);
  if (id_maquina.value == "" || legajo.value == "" || fecha_programada.value == "" ||
    hora_programada.value == "" || descripcion.value == "") {
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
    const url = base_url + "Preventivos/registrar";
    const frm = document.getElementById("frmPreventivo");
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
            title: 'Preventivo registrado con éxito',
            showConfirmButton: false,
            timer: 3000
          })
          frm.reset();
          $("#nuevo-preventivo").modal("hide");
          tblPreventivos.ajax.reload();
        } else if (res == "modificado") {
          Swal.fire({
            position: 'top-center',
            icon: 'success',
            title: 'Preventivo modificado con éxito',
            showConfirmButton: false,
            timer: 3000
          })
          frm.reset();
          $("#nuevo-preventivo").modal("hide");
          tblPreventivos.ajax.reload();
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
function btnEliminarPreventivo(id_preventivo) {
  Swal.fire({
    title: "¿Está seguro de desactivar el preventivo?",
    text: "El mantenimiento preventivo no se eliminará de forma permanente, solo cambiará el estado a inactivo",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Confirmar",
    cancelButtonText: "Cancelar"
  }).then((result) => {
    if (result.isConfirmed) {
      const url = base_url + "Preventivos/eliminar/" + id_preventivo;
      const http = new XMLHttpRequest();
      http.open("GET", url, true);
      http.send();
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          if (res == "ok") {
            Swal.fire(
              'Exito',
              'El mantenimiento preventivo ha sido desactivado',
              'success'
            )
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
function btnReingresarPreventivo(id_preventivo) {
  Swal.fire({
    title: "¿Está seguro de activar el mantenimiento preventivo?",
    text: "El mantenimiento preventivo pasará a estado 'activo' ",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Confirmar",
    cancelButtonText: "Cancelar"
  }).then((result) => {
    if (result.isConfirmed) {
      const url = base_url + "Preventivos/reingresar/" + id_preventivo;
      const http = new XMLHttpRequest();
      http.open("GET", url, true);
      http.send();
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          if (res == "ok") {
            Swal.fire(
              'Exito',
              'El mantenimiento preventivo ha sido activado',
              'success'
            )
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