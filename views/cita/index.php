<h1 class="nombre-pagina">Crear Nueva Cita</h1>
<p class="descripcion-pagina">Elige tus servicios y coloca tus datos</p>
<!--Vid 527-->
<?php
include_once __DIR__ . '/../templates/barra.php';
?>

<div id="app">
    <!--Vid 486-->
    <nav class="tabs">
        <button class="actual" type="button" data-paso="1">Servicios</button>
        <button type="button" data-paso="2">Información Cita</button>
        <button type="button" data-paso="3">Resumen</button>
    </nav>

    <div id="paso-1" class="seccion">
        <h2>Servicios</h2>
        <p class="text-center">Elige tus servicios a continuación</p>
        <div id="servicios" class="listado-servicios"></div>
    </div>
    <div id="paso-2" class="seccion">
        <h2>Tus Datos y Cita</h2>
        <p class="text-center">Coloca tus datos y fecha de tu cita</p>

        <form class="formulario">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <!--Vid 485-->
                <input
                    id="nombre"
                    type="text"
                    placeholder="Tu Nombre"
                    value="<?php echo $nombre; ?>"
                    disabled />
            </div>

            <div class="campo">
                <label for="fecha">Fecha</label>
                <!--Vid 505 ,colocar una fecha minima,min-->
                <input
                    id="fecha"
                    type="date"
                    min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" />
            </div>

            <div class="campo">
                <label for="hora">Hora</label>
                <input
                    id="hora"
                    type="time" />
            </div>
            <!--Vid 520, agregamos el id-->
            <input type="hidden" id="id" value="<?php echo $id; ?>">

        </form>
    </div>
    <div id="paso-3" class="seccion contenido-resumen">
        <h2>Resumen</h2>
        <p class="text-center">Verifica que la información sea correcta</p>
    </div>
    <!--Vid 487-->
    <div class="paginacion">
        <button
            id="anterior"
            class="boton">&laquo; Anterior</button>

        <button
            id="siguiente"
            class="boton">Siguiente &raquo;</button>
    </div>
</div>

<!--Vid 488 y vid 521-->
<?php
$script = "
        <script src='//cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script src='build/js/app.js'></script>
    ";
?>