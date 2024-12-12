<?php

//VId 483
namespace Controllers;

use MVC\Router;

class CitaController
{
    public static function index(Router $router)
    {

        //Vid 485
        //session_start();
        //debuguear(($_SESSION));

        iniciarSession();
        //debuguear(iniciarSession());
        //Vid 524
        isAuth();
        //Vid 485
        $router->render('cita/index', [
            'nombre' => $_SESSION['nombre'],
            //Vid 520
            'id' => $_SESSION['id']
        ]);
    }
}
