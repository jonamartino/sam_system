
// Usuarios
let tblUsuarios, tblMaquinas;
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
                'data': 'nombre'
            },
            {
                'data': 'apellido'
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
                'data': 'id_local'
            },
            {
                'data': 'maq_nombre'
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

function frmLogin(e) {
    e.preventDefault();
    const usuario = document.getElementById("usuario");
    const clave = document.getElementById("clave");
    if (usuario.value == "") {
        clave.classList.remove("is-invalid");
        usuario.classList.add("is-invalid");
        usuario.focus();
    } else if (clave.value == "") {
        usuario.classList.remove("is-invalid");
        clave.classList.add("is-invalid");
        clave.focus();
    } else {
        const url = base_url + "Usuarios/validar";
        const frm = document.getElementById("frmLogin");
        const http = new XMLHttpRequest();
        http.open("POST", url, true);
        http.send(new FormData(frm));
        http.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                const res = JSON.parse(this.responseText);
                if (res == "ok") {
                    window.location = base_url + "Usuarios";
                } else {
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


    if (usuario.value == "" || legajo.value == "") {
        Swal.fire({
            position: 'top-center',
            icon: 'error',
            title: 'Todos los campos son obligatorios',
            showConfirmButton: false,
            timer: 3000
        })
    } else {
        const url = base_url + "Usuarios/registrar";
        const frm = document.getElementById("frmUsuario");
        const http = new XMLHttpRequest();
        console.log(frm);
        http.open("POST", url, true);
        http.send(new FormData(frm));
        http.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                const res = JSON.parse(this.responseText);
                if (res == "si") {
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
            const url = base_url + "Usuarios/reingresar/" + id;
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
// Fin Usuarios

// Maquinas

function frmMaquina() {
    document.getElementById("title").innerHTML = "Nueva Maquina";
    document.getElementById("btn-accion").innerHTML = "Registrar";
    document.getElementById("frmMaquina").reset();
    $("#nueva-maquina").modal("show");
    document.getElementById("id_maquina").value = "";
}
function registrarMaquina(e) {
    e.preventDefault();
    const id_local = document.getElementById("id_local");
    const maq_nombre = document.getElementById("maq_nombre");
    const celda_nombre = document.getElementById("celda_nombre");
    console.log(id_local.value, maq_nombre.value, celda_nombre.value);
    if (id_local.value == "" || maq_nombre.value == "" || celda_nombre.value == "") {
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
                console.log(res);
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
            document.getElementById("maq_nombre").value = res.maq_nombre;
            document.getElementById("id_local").value = res.id_local;
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