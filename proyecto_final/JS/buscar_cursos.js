// Función para realizar la búsqueda de cursos mediante AJAX
// Función para realizar la búsqueda de cursos mediante AJAX
function searchCourses() {
    var searchTerm = document.getElementById('searchInput').value;
    var searchTermPrecio = document.getElementById('searchPrecio').value;
    var filtro_url = '';
    if (searchTermPrecio !== ''){
         filtro_url = '&precio=' + searchTermPrecio;
    }
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('searchResults').innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "./buscador.php?q=" + searchTerm + filtro_url, true);
    xhttp.send();
}
// Vincula el evento de búsqueda al campo de entrada
document.getElementById('searchInput').addEventListener('input', searchCourses);
document.getElementById('buscPrecio').addEventListener('click', searchCourses);

