<?php

namespace Controllers;

use Model\Servicio;
use MVC\Router;

class ServicioController
{
    public static function index(Router $router)
    {
        session_start();
        //Vid 551
        isAdmin();
        //Vid 546, vemos todos los servicios
        $servicios = Servicio::all();

        //Vid 542
        $router->render('servicios/index', [
            'nombre' => $_SESSION['nombre'],
            'servicios' => $servicios
        ]);
    }

    //Vid 545
    public static function crear(Router $router)
    {
        //Vid 548
        session_start();
        //Vid 551
        isAdmin();
        $servicio = new Servicio;
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servicio->sincronizar($_POST);

            $alertas = $servicio->validar();

            if (empty($alertas)) {
                $servicio->guardar();
                header('Location: /servicios');
            }
        }

        $router->render('servicios/crear', [
            //Vid 548
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function actualizar(Router $router)
    {
        session_start();
        //Vid 551
        isAdmin();
        //Vid 548
        if (!is_numeric($_GET['id'])) return;
        //Vid 549
        $servicio = Servicio::find($_GET['id']);
        $alertas = [];


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Vid 549
            $servicio->sincronizar($_POST);

            $alertas = $servicio->validar();

            if (empty($alertas)) {
                //guardamos en la base de datos
                $servicio->guardar();
                header('Location: /servicios');
            }
        }

        $router->render('servicios/actualizar', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    //Vid 550
    public static function eliminar()
    {
        session_start();
        //Vid 551
        isAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $servicio = Servicio::find($id);
            $servicio->eliminar();
            header('Location: /servicios');
        }
    }
}
