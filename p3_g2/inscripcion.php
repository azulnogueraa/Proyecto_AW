<?php
//Inicio del procesamiento
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Inscripción</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/logo.jpg" type="image/png">
    <link rel="stylesheet" href="CSS/login_registro.css">
    <link rel="stylesheet" href="CSS/topBar.css">
    <script>
        let pasoActual = 1;
        const totalPasos = 2;

        function mostrarSeccion(seccion) {
            document.querySelectorAll('.formulario-seccion').forEach(function(seccion) {
                seccion.style.display = 'none';
            });
            document.getElementById(seccion).style.display = 'block';

            // Actualizar indicador de paso
            document.getElementById('indicador-paso').innerText = `Paso ${pasoActual} de ${totalPasos}`;
        }
    </script>
</head>
<body>
    <?php require "includes/vistas/comun/topbar.php"; ?>
    <main>
        <article>
            <h1>Formulario de Inscripción</h1>
            <div class="formulario-seccion" id="datos-seccion">
                <form action="procesarInscripcion.php" method="POST">
                    <!-- Campo oculto para almacenar el nombre del curso -->    
                    <input type="hidden" name="nombre_curso" value="<?= isset($_GET['nombre_curso']) ? $_GET['nombre_curso'] : ''; ?>">
                    
                    <fieldset>
                        <div class="legenda">
                            <legend>1. Datos para la inscripcion</legend>
                        </div>
                        <div>
                            <label for="nombre_usuario">Nombre de usuario:</label>
                            <input id="nombre_usuario" type="text" name="nombre_usuario" required value="<?= isset($_SESSION['nombre_usuario']) ? $_SESSION['nombre_usuario'] : ''; ?>"/>
                        </div>
                        <div>
                            <label for="apellido">Apellido:</label>
                            <input id="apellido" type="text" name="apellido" required value="<?= isset($_SESSION['apellido']) ? $_SESSION['apellido'] : ''; ?>"/>
                        </div>
                        <div>
                            <label for="email">Email:</label>
                            <input id="email" type="email" name="email" required 
                            value="<?= isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>" />
                        </div>
                        <div>
                            <input type="checkbox" id="terminos" name="terminos" required>
                            <label for="terminos">Acepto los Términos y Condiciones</label>
                        </div>

                        <div class="boton">
                            <button type="button" onclick="mostrarSeccion('pago-seccion')">Siguiente</button>
                        </div>
                    </fieldset>
                </form>
            </div>

            <div class="formulario-seccion" id="pago-seccion" style="display: none;">
                <fieldset>
                    <div class="legenda">
                            <legend>2. Datos de Pago</legend>
                    </div>
                    <form action="procesarPago.php" method="POST">
                        <div>
                            <label for="metodo_pago">Método de Pago:</label>
                            <select id="metodo_pago" name="metodo_pago" required>
                                <option value="tarjeta">Tarjeta de Crédito</option>
                                <option value="paypal">PayPal</option>
                            </select>
                        </div>
                        <div>
                            <label for="numero_tarjeta">Número de Tarjeta:</label>
                            <input id="numero_tarjeta" type="text" name="numero_tarjeta" required>
                        </div>
                        <div>
                            <label for="fecha_expiracion">Fecha de Expiración:</label>
                            <input id="fecha_expiracion" type="text" name="fecha_expiracion" placeholder="MM/AA" required>
                        </div>

                        <div class="boton">
                            <button type="submit">Confirmar Inscripción</button>
                        </div>
                    </form>
                </fieldset>
            </div>
        </article>
    </main>
</body>
</html>
