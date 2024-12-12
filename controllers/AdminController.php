<?php
//Vid 527
namespace Controllers;

use Model\AdminCita;
use MVC\Router;

class AdminController
{
    public static function index(Router $router)
    {
        //VID 527
        session_start();

        //Vid 540,proteccion de rutas
        isAdmin();

        //Vid 535
        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        //Vid 538
        $fechas = explode('-', $fecha);

        if (!checkdate($fechas[1], $fechas[2], $fechas[0])) {
            header('Location: /404');
        }

        //VID 531 Consultar la base de datos
        $consulta = "SELECT citas.id, citas.hora, CONCAT( usuarios.nombre, ' ', usuarios.apellido) as cliente, ";
        $consulta .= " usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio  ";
        $consulta .= " FROM citas  ";
        $consulta .= " LEFT OUTER JOIN usuarios ";
        $consulta .= " ON citas.usuarioId=usuarios.id  ";
        $consulta .= " LEFT OUTER JOIN citasServicios ";
        $consulta .= " ON citasServicios.citaId=citas.id ";
        $consulta .= " LEFT OUTER JOIN servicios ";
        $consulta .= " ON servicios.id=citasServicios.servicioId ";
        //Vid 535
        $consulta .= " WHERE fecha =  '{$fecha}' ";

        //VID 531,
        $citas = AdminCita::SQL($consulta);
        //debuguear($citas);

        $router->render('admin/index', [
            'nombre' => $_SESSION['nombre'],
            'citas' => $citas,
            'fecha' => $fecha
        ]);
    }
}
