<?php

require_once __DIR__ . '/../includes/app.php';

use Controllers\AdminController;
use Controllers\APIController;
use Controllers\CitaController;
use Controllers\LoginController;
use Controllers\ServicioController;
use MVC\Router;

$router = new Router();

//Vid 443, Iniciar Sesión
$router->get('/', [LoginController::class, 'login']);
$router->post('/', [LoginController::class, 'login']);
$router->get('/logout', [LoginController::class, 'logout']);

//Vid 443,Recuperar Password
$router->get('/olvide', [LoginController::class, 'olvide']);
$router->post('/olvide', [LoginController::class, 'olvide']);
$router->get('/recuperar', [LoginController::class, 'recuperar']);
$router->post('/recuperar', [LoginController::class, 'recuperar']);

//Vid 443, Crear Cuenta
$router->get('/crear-cuenta', [LoginController::class, 'crear']);
$router->post('/crear-cuenta', [LoginController::class, 'crear']);

//Vid 468, Confirmar cuenta
$router->get('/confirmar-cuenta', [LoginController::class, 'confirmar']);
//Vid 469
$router->get('/mensaje', [LoginController::class, 'mensaje']);

// AREA PRIVADA
$router->get('/cita', [CitaController::class, 'index']);
$router->get('/admin', [AdminController::class, 'index']);

// API de Citas
$router->get('/api/servicios', [APIController::class, 'index']);
//Vid515
$router->post('/api/citas', [APIController::class, 'guardar']);
//Vid 539
$router->post('/api/eliminar', [APIController::class, 'eliminar']);

//Vid 542,CRUD de Servicios
$router->get('/servicios', [ServicioController::class, 'index']);
$router->get('/servicios/crear', [ServicioController::class, 'crear']);
$router->post('/servicios/crear', [ServicioController::class, 'crear']);
$router->get('/servicios/actualizar', [ServicioController::class, 'actualizar']);
$router->post('/servicios/actualizar', [ServicioController::class, 'actualizar']);
$router->post('/servicios/eliminar', [ServicioController::class, 'eliminar']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();
