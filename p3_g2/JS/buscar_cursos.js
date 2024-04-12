// Función para realizar la búsqueda de cursos mediante AJAX
function searchCourses() {
    var searchTerm = document.getElementById('searchInput').value;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var resultados = JSON.parse(this.responseText);
            displaySearchResults(resultados);
        }
    };
    xhttp.open("GET", "../includes/buscador.php?q=" + searchTerm, true);
    xhttp.send();
}

// Función para mostrar los resultados de búsqueda en la página
function displaySearchResults(resultados) {
    var searchResultsContainer = document.getElementById('searchResults');
    searchResultsContainer.innerHTML = '';
    
    // Verificar si resultados es un array
    if (Array.isArray(resultados)) {
        if (resultados.length > 0) {
            resultados.forEach(function(curso) {
                var cursoHTML = '<div class="curso">';
                cursoHTML += '<h2>' + curso.nombre + '</h2>';
                cursoHTML += '<p>Precio: ' + curso.precio + '</p>';
                cursoHTML += '<a href="' + curso.url + '">Ver más</a>';
                cursoHTML += '</div>';
                searchResultsContainer.innerHTML += cursoHTML;
            });
        } else {
            searchResultsContainer.innerHTML = '<p>No se encontraron resultados.</p>';
        }
    } else {
        searchResultsContainer.innerHTML = '<p>Error: Los resultados no son válidos.</p>';
    }
}

// Vincula el evento de búsqueda al campo de entrada
document.getElementById('searchInput').addEventListener('input', searchCourses);
