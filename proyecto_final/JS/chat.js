// Función para realizar la búsqueda de cursos mediante AJAX
// Función para realizar la búsqueda de cursos mediante AJAX
function searchCourses() {
    var contenido = document.getElementById('message').value;
    var curso_nombre = Ncurso;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('contenedor-cursos').innerHTML += this.responseText;
        }
    };
    xhttp.open("GET", "./showmsg.php?q=" + contenido + "&curso=" + curso_nombre, true);
    xhttp.send(); 
}

// Vincula el evento de búsqueda al campo de entrada
document.getElementById('sendmsg').addEventListener('click', searchCourses);