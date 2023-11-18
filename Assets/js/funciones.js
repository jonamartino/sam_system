let tblUsuarios;
document.addEventListener("DOMContentLoaded",function() {
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
            'data' : 'nombre'
            },
            {        
            'data' : 'dni'
            },
            {        
            'data' : 'estado'
            },
            {        
            'data' : 'acciones'
            }
        ] 
    })
})
function frmLogin(e) {
    e.preventDefault();
    const usuario = document.getElementById("usuario");
    const password = document.getElementById("password");
    if (usuario.value==""){
        password.classList.remove("is-invalid");
        usuario.classList.add("is-invalid");
        usuario.focus();
    }else if(password.value==""){
        usuario.classList.remove("is-invalid");
        password.classList.add("is-invalid");
        password.focus();
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
    $("#nuevo-usuario").modal("show");
}
function registrarUser(e) {
    e.preventDefault();
    const usuario = document.getElementById("usuario");
    const clave = document.getElementById("clave");
    const dni = document.getElementById("dni");
    const nombre = document.getElementById("nombre");
    const apellido = document.getElementById("apellido");
    const confirmar = document.getElementById("confirmar");
    
    
    if (usuario.value=="" || nombre.value =="" || clave.value =="" || apellido.value =="" || dni.value =="" || confirmar.value ==""){
        Swal.fire({
            position: 'top-center',
            icon: 'error',
            title: 'Todos los campos son obligatorios',
            showConfirmButton: false,
            timer: 3000
          })
    }else if(clave.value != confirmar.value){
        Swal.fire({
            position: 'top-center',
            icon: 'error',
            title: 'Las contraseñas deben coincidir',
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
function btnEditarUser() {
    document.getElementById("title").innerHTML = "Actualizar Usuario";
    document.getElementById("btn-accion").innerHTML = "Modificar";
    $("#nuevo-usuario").modal("show");

}