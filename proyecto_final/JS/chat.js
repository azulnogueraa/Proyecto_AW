let lastIds = {}; // ids de los ultimos mensajes recibidos por cursos

window.onload = function () {
    // Enviamos el mensaje cuando se presione enter
    let mensaje = document.querySelector("#mensaje");
    mensaje.addEventListener("keyup", function(event){
        if(event.key === "Enter"){
            enviarMensaje();
        }
    });

    // Enviamos el mensaje cuando se haga click en el boton
    let validar = document.querySelector("#validar");
    validar.addEventListener("click", enviarMensaje)

    // Cargamos los mensjaes todos los segundos
    setInterval(cargarMensajes, 1000);
}

/**
 * Envia los mensajes en ajax a Mensaje.php
 */
function enviarMensaje() {
    let mensaje = document.querySelector("#mensaje").value
    if(mensaje != "") {
        // Creamos el objeto JS
        let datos = {}
        datos["mensaje"] = mensaje
        datos["nombre_curso"] = obtenerNombreCurso()

        // Convertimos el objeto JS a JSON
        let dataJson = JSON.stringify(datos)

        // Enviamos los datos en POST por Ajax
        let xmlhttp = new XMLHttpRequest()
        xmlhttp.onreadystatechange = function() {
            if(this.readyState == 4) {
                if(this.status == 201) {
                    // Limpiamos el campo de texto
                    document.querySelector("#mensaje").value = ""
                } else {
                    let respuesta = JSON.parse(this.response);
                    alert(respuesta.mensaje);
                }
            }
        }
        xmlhttp.open("POST", `AJAX/anadirMensaje.php`)
        xmlhttp.send(dataJson)
    }
}

/**
 * Carga los ultimos mensajes en Ajax
 */
function cargarMensajes() {
    let nombre_curso = obtenerNombreCurso()
    let lastId = lastIds[nombre_curso] || 0
    let xmlhttp = new XMLHttpRequest()
    xmlhttp.onreadystatechange = function() {
        if(this.readyState == 4) {
            if (this.status == 200) {
                // Convertimos la respuesta en un objeto JS
                let mensajes = JSON.parse(this.response)

                let chat = document.querySelector("#chat")
                for(let mensaje of mensajes) {
                    let fechaMensaje = new Date(mensaje.created_at)
                    chat.insertAdjacentHTML("beforeend", `<p>${mensaje.nombre_usuario} ha escrito el ${fechaMensaje.toLocaleString()} : ${mensaje.mensaje}</p>`)
                    
                    // Hacemos scroll para que se vea el ultimo mensaje
                    let objDiv = document.getElementById("chat")
                    objDiv.scrollTop = objDiv.scrollHeight

                    lastIds[nombre_curso] = mensaje.id
                }
            } else {
                let respuesta = JSON.parse(this.response)
                alert(respuesta.mensaje)
            }
        }
    }
    xmlhttp.open("GET", "AJAX/cargarMensaje.php?nombre_curso="+nombre_curso+"&lastId="+lastId)
    xmlhttp.send()
}

/**
 * Devuelve el id del curso que se encuentra en la URL
 */
function obtenerNombreCurso() {
    let url = new URL(window.location.href)
    return url.searchParams.get("nombre_curso")
}