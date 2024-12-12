<?php

namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;

//Vid 495
class APIController
{
    public static function index()
    {
        $servicios = Servicio::all();
        echo json_encode($servicios);
    }

    public static function guardar()
    {

        //Vid 519, Almacena la Cita y devuelve el ID
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();

        $id = $resultado['id'];

        // Almacena la Cita y el Servicio

        //Vid 523 Almacena los Servicios con el ID de la Cita
        $idServicios = explode(",", $_POST['servicios']);
        foreach ($idServicios as $idServicio) {
            //arreglo asociativo
            $args = [
                'citaId' => $id,
                'servicioId' => $idServicio
            ];
            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }

        echo json_encode(['resultado' => $resultado]);
    }

    //Vid 539
    public static function eliminar()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $cita = Cita::find($id);
            $cita->eliminar();
            header('Location:' . $_SERVER['HTTP_REFERER']);
        }
    }
}
