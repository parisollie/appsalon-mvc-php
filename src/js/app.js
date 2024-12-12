//Vid 489
let paso = 1;
//Vid 493
const pasoInicial = 1;
const pasoFinal = 3;

//Vid 499
const cita = {
    //Vid 520,ponemos el id
    id: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}

//Vid 489
document.addEventListener('DOMContentLoaded', function () {
    iniciarApp();
});

function iniciarApp() {
    //Vid 491
    mostrarSeccion(); // Muestra y oculta las secciones
    tabs(); // Cambia la sección cuando se presionen los tabs
    //Vid 492
    botonesPaginador(); // Agrega o quita los botones del paginador
    //Vid 493
    paginaSiguiente();
    paginaAnterior();

    consultarAPI(); // Consulta la API en el backend de PHP
    //Vid 520
    idCliente();
    //Vid 502
    nombreCliente(); // Añade el nombre del cliente al objeto de cita
    //Vid 503
    seleccionarFecha(); // Añade la fecha de la cita en el objeto
    //Vid 506
    seleccionarHora(); // Añade la hora de la cita en el objeto
    //Vid 507
    mostrarResumen(); // Muestra el resumen de la cita
}

//Vid 490
function mostrarSeccion() {

    // Ocultar la sección que tenga la clase de mostrar
    const seccionAnterior = document.querySelector('.mostrar');
    if (seccionAnterior) {
        seccionAnterior.classList.remove('mostrar');
    }

    // Seleccionar la sección con el paso...
    const pasoSelector = `#paso-${paso}`;
    const seccion = document.querySelector(pasoSelector);
    seccion.classList.add('mostrar');

    //Vid 491, Quita la clase de actual al tab anterior
    const tabAnterior = document.querySelector('.actual');
    if (tabAnterior) {
        tabAnterior.classList.remove('actual');
    }

    //Vid 491, Resalta el tab actual
    const tab = document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add('actual');
}

//Vid 489
function tabs() {

    // Agrega y cambia la variable de paso según el tab seleccionado
    const botones = document.querySelectorAll('.tabs button');
    botones.forEach(boton => {
        boton.addEventListener('click', function (e) {
            e.preventDefault();

            paso = parseInt(e.target.dataset.paso);
            mostrarSeccion();
            //Vid 492
            botonesPaginador();
        });
    });
}

//Vid 492
function botonesPaginador() {
    const paginaAnterior = document.querySelector('#anterior');
    const paginaSiguiente = document.querySelector('#siguiente');

    if (paso === 1) {
        paginaAnterior.classList.add('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    } else if (paso === 3) {
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.add('ocultar');
        //Vid 507
        mostrarResumen();
    } else {
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    }
    //Vid 493
    mostrarSeccion();
}
//Vid 493
function paginaAnterior() {
    const paginaAnterior = document.querySelector('#anterior');
    paginaAnterior.addEventListener('click', function () {

        if (paso <= pasoInicial) return;
        paso--;

        botonesPaginador();
    })
}
//Vid 493
function paginaSiguiente() {
    const paginaSiguiente = document.querySelector('#siguiente');
    paginaSiguiente.addEventListener('click', function () {

        if (paso >= pasoFinal) return;
        paso++;

        botonesPaginador();
    })
}

async function consultarAPI() {

    try {
        //Vid 554
        //const url = `${location.origin}/api/servicios`;
        const url = '/api/servicios';
        const resultado = await fetch(url);
        const servicios = await resultado.json();
        //Vid 497
        mostrarServicios(servicios);

    } catch (error) {
        console.log(error);
    }
}
//Vid 497
function mostrarServicios(servicios) {
    servicios.forEach(servicio => {
        const { id, nombre, precio } = servicio;

        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `$${precio}`;

        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id;
        servicioDiv.onclick = function () {
            seleccionarServicio(servicio);
        }

        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);

        //Vid 499
        document.querySelector('#servicios').appendChild(servicioDiv);

    });
}

//Vid 499
function seleccionarServicio(servicio) {
    const { id } = servicio;
    const { servicios } = cita;

    //Vid 500, Identificar el elemento al que se le da click
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

    //Vod 501, Comprobar si un servicio ya fue agregado 
    if (servicios.some(agregado => agregado.id === id)) {
        // Eliminarlo
        cita.servicios = servicios.filter(agregado => agregado.id !== id);
        divServicio.classList.remove('seleccionado');
    } else {
        //Vid 499 Agregarlo
        cita.servicios = [...servicios, servicio];
        //Vid 500
        divServicio.classList.add('seleccionado');
    }
    // console.log(cita);
}
//Vid 520
function idCliente() {
    cita.id = document.querySelector('#id').value;
}
//Vid 502
function nombreCliente() {
    cita.nombre = document.querySelector('#nombre').value;
    //console.log(cita.nombre);
}
//Vid 503
function seleccionarFecha() {
    const inputFecha = document.querySelector('#fecha');
    inputFecha.addEventListener('input', function (e) {

        const dia = new Date(e.target.value).getUTCDay();

        if ([6, 0].includes(dia)) {
            e.target.value = '';
            //Vid 504
            mostrarAlerta('Fines de semana no permitidos', 'error', '.formulario');
        } else {
            cita.fecha = e.target.value;
        }

    });
}
//Vid 506
function seleccionarHora() {
    const inputHora = document.querySelector('#hora');
    inputHora.addEventListener('input', function (e) {


        const horaCita = e.target.value;
        const hora = horaCita.split(":")[0];
        if (hora < 10 || hora > 18) {
            e.target.value = '';
            mostrarAlerta('Hora No Válida', 'error', '.formulario');
        } else {
            cita.hora = e.target.value;

            // console.log(cita);
        }
    })
}
//Vid 504
//Vid 508,elemento, desaparece = true
function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {

    // Previene que se generen más de 1 alerta
    const alertaPrevia = document.querySelector('.alerta');
    if (alertaPrevia) {
        alertaPrevia.remove();
    }

    // Scripting para crear la alerta
    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);

    //Vid 508 ,le ponemos el elemento
    const referencia = document.querySelector(elemento);
    referencia.appendChild(alerta);
    //Vid 508
    if (desaparece) {
        // Eliminar la alerta
        setTimeout(() => {
            alerta.remove();
        }, 3000);
    }

}

//Vid 507
function mostrarResumen() {
    const resumen = document.querySelector('.contenido-resumen');

    //Vid 509, Limpiar el Contenido de Resumen
    while (resumen.firstChild) {
        resumen.removeChild(resumen.firstChild);
    }
    //Vid 508
    if (Object.values(cita).includes('') || cita.servicios.length === 0) {
        //Vid 508
        mostrarAlerta('Faltan datos de Servicios, Fecha u Hora', 'error', '.contenido-resumen', false);

        return;
    }

    //Vid 509, Formatear el div de resumen
    const { nombre, fecha, hora, servicios } = cita;

    //Vid 510, Heading para Servicios en Resumen
    const headingServicios = document.createElement('H3');
    headingServicios.textContent = 'Resumen de Servicios';
    resumen.appendChild(headingServicios);

    //Vid 509, Iterando y mostrando los servicios
    servicios.forEach(servicio => {
        const { id, precio, nombre } = servicio;
        const contenedorServicio = document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');

        const textoServicio = document.createElement('P');
        textoServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>Precio:</span> $${precio}`;

        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);

        resumen.appendChild(contenedorServicio);
    });

    //Vid 510, Heading para Cita en Resumen
    const headingCita = document.createElement('H3');
    headingCita.textContent = 'Resumen de Cita';
    resumen.appendChild(headingCita);

    //Vid 509
    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`;

    //Vid 511, Formatear la fecha en español
    const fechaObj = new Date(fecha);
    const mes = fechaObj.getMonth();
    const dia = fechaObj.getDate() + 2;
    const year = fechaObj.getFullYear();

    const fechaUTC = new Date(Date.UTC(year, mes, dia));

    const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }
    const fechaFormateada = fechaUTC.toLocaleDateString('es-MX', opciones);

    //Vid 509
    const fechaCita = document.createElement('P');
    fechaCita.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`;
    //Vid 509
    const horaCita = document.createElement('P');
    horaCita.innerHTML = `<span>Hora:</span> ${hora} Horas`;

    //Vid 512, Boton para Crear una cita
    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent = 'Reservar Cita';
    botonReservar.onclick = reservarCita;

    //Vid 509
    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);
    //Vid 512
    resumen.appendChild(botonReservar);
}

//Vid 514
async function reservarCita() {
    //VID 518, EXTRAEMOS
    //Vid 520, agrego id
    const { nombre, fecha, hora, servicios, id } = cita;

    const idServicios = servicios.map(servicio => servicio.id);
    //console.log(idServicios);
    //return;

    //Vid 514
    const datos = new FormData();

    datos.append('fecha', fecha);
    datos.append('hora', hora);
    datos.append('usuarioId', id);
    datos.append('servicios', idServicios);

    //vid 521
    try {
        //Vid ,515
        // Petición hacia la api
        //Vid 554
        const url = '/api/citas'
        //await se usa cuando la funcion es asincrona
        const respuesta = await fetch(url, {
            method: 'POST',
            //Vid 517
            body: datos
        });

        const resultado = await respuesta.json();
        //Vid 521
        console.log(resultado.resultado);

        //Vid 521
        if (resultado.resultado) {
            Swal.fire({
                icon: 'success',
                title: 'Cita Creada',
                text: 'Tu cita fue creada correctamente',
                button: 'OK'
            }).then(() => {
                setTimeout(() => {
                    //Vid 521
                    window.location.reload();
                }, 3000);
            })
        }

    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al guardar la cita'
        })
    }

}