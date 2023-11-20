let tblUsuarios;
document.addEventListener("DOMContentLoaded", function() {
    tblUsuarios = $('#tblUsuarios').DataTable( {
        ajax: {
            url: base_url + "Usuarios/listar",
            dataSrc: ''
        },
        columns: [
            {
            'data' : 'id'
            },
            {
            'data' : 'usuario'
            },
            {
            'data' : 'legajo'
            },
            {        
            'data' : 'nombre'
            },
            {        
            'data' : 'apellido'
            },
            {        
            'data' : 'estado'
            },
            {        
            'data' : 'acciones'
            }
        ] 
    });
})
function frmLogin(e) {
    e.preventDefault();
    const usuario = document.getElementById("usuario");
    const clave = document.getElementById("clave");
    if (usuario.value==""){
        clave.classList.remove("is-invalid");
        usuario.classList.add("is-invalid");
        usuario.focus();
    }else if(clave.value==""){
        usuario.classList.remove("is-invalid");
        clave.classList.add("is-invalid");
        clave.focus();
    } else{
        const url = base_url + "Usuarios/validar";
        const frm = document.getElementById("frmLogin");
        const http = new XMLHttpRequest();
        http.open("POST", url, true);
        http.send(new FormData(frm));
        http.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200) {
                const res = JSON.parse(this.responseText);
                if(res == "ok") {
                    window.location = base_url + "Usuarios";
                }else{
                    document.getElementById("alerta").classList.remove("d-none");
                    document.getElementById("alerta").innerHTML = res;
                }
            }  
        }
    }
}
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
    const clave = document.getElementById("clave");
    const legajo = document.getElementById("legajo");
    const confirmar = document.getElementById("confirmar");
    
    
    if (usuario.value=="" ||  legajo.value ==""){
        Swal.fire({
            position: 'top-center',
            icon: 'error',
            title: 'Todos los campos son obligatorios',
            showConfirmButton: false,
            timer: 3000
          })
    } else{
        const url = base_url + "Usuarios/registrar";
        const frm = document.getElementById("frmUsuario");
        const http = new XMLHttpRequest();
        http.open("POST", url, true);
        http.send(new FormData(frm));
        http.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);
                const res = JSON.parse(this.responseText);
                if(res == "si"){
                    Swal.fire({
                        position: 'top-center',
                        icon: 'success',
                        title: 'Usuario registrado con éxito',
                        showConfirmButton: false,
                        timer: 3000
                      })
                      frm.reset();
                      $("#nuevo-usuario").modal("hide");
                      tblUsuarios.ajax.reload();
                } else if (res == "modificado") {
                    Swal.fire({
                        position: 'top-center',
                        icon: 'success',
                        title: 'Usuario modificado con éxito',
                        showConfirmButton: false,
                        timer: 3000
                      })
                      frm.reset();
                      $("#nuevo-usuario").modal("hide");
                      tblUsuarios.ajax.reload();
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
function btnEditarUser(id) {
    document.getElementById("title").innerHTML = "Actualizar Usuario";
    document.getElementById("btn-accion").innerHTML = "Modificar";
    const url = base_url + "Usuarios/editar/" +id;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            document.getElementById("id").value = res.id;
            document.getElementById("usuario").value = res.usuario;
            document.getElementById("legajo").value = res.id_persona;
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
            const url = base_url + "Usuarios/eliminar/" +id;
            const http = new XMLHttpRequest();
            http.open("GET", url, true);
            http.send();
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    if (res == "ok") {
                        Swal.fire(
                            'Exito',
                            'El usuario ha sido eliminado',
                            'success'
                      )            
                      tblUsuarios.ajax.reload();           
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
            const url = base_url + "Usuarios/reingresar/" +id;
            const http = new XMLHttpRequest();
            http.open("GET", url, true);
            http.send();
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    if (res == "ok") {
                        Swal.fire(
                            'Exito',
                            'El usuario ha sido reingresado',
                            'success'
                      )            
                      tblUsuarios.ajax.reload();           
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