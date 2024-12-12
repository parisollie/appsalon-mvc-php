<?php

//Vid 443
namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController
{
    //Vid 444
    public static function login(Router $router)
    {

        //Vid 472
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();

            //Vid 473
            if (empty($alertas)) {
                // Comprobar que exista el usuario
                $usuario = Usuario::where('email', $auth->email);

                if ($usuario) {
                    // Verificar el password
                    //$usuario->comprobarPasswordAndVerificado($auth->password);
                    if ($usuario->comprobarPasswordAndVerificado($auth->password)) {
                        //Vid 475 Autenticar el usuario
                        // Autenticar el usuario
                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        // Redireccionamiento
                        if ($usuario->admin === "1") {
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        } else {
                            header('Location: /cita');
                        }
                    }
                } else {
                    Usuario::setAlerta('error', 'Usuario no encontrado');
                }
            }
        }

        //Vid 473
        $alertas = Usuario::getAlertas();

        //Vid 444
        $router->render('auth/login', [
            //Vid 472
            'alertas' => $alertas
        ]);
    }

    //Vid 524
    public static function logout()
    {
        //Vid 526
        iniciarSession();
        //session_start();
        $_SESSION = [];
        header('Location: /');
    }

    //Vid 480
    public static function recuperar(Router $router)
    {
        //Vid 481
        $alertas = [];
        $error = false;

        $token = s($_GET['token']);

        // Buscar usuario por su token
        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            Usuario::setAlerta('error', 'Token No Válido');
            $error = true;
        }

        //VId 481
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Leer el nuevo password y guardarlo

            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();

            if (empty($alertas)) {
                $usuario->password = null;

                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;

                $resultado = $usuario->guardar();
                if ($resultado) {
                    header('Location: /');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    //Vid 477
    public static function olvide(Router $router)
    {

        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if (empty($alertas)) {
                $usuario = Usuario::where('email', $auth->email);

                if ($usuario && $usuario->confirmado === "1") {

                    //Vid 478, Generar un token
                    $usuario->crearToken();
                    $usuario->guardar();

                    //Vid 479  Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    //Vid 479, Alerta de exito
                    Usuario::setAlerta('exito', 'Revisa tu email');
                } else {
                    Usuario::setAlerta('error', 'El Usuario no existe o no esta confirmado');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }

    //Vid 450
    public static function crear(Router $router)
    {
        //Vid 459
        $usuario = new Usuario;
        //Vid 461 Alertas vacias
        $alertas = [];
        //Vid 459
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Vid 459
            $usuario->sincronizar($_POST);
            //Vid 460
            $alertas = $usuario->validarNuevaCuenta();


            //Vid 463,Revisar que alerta este vacio
            if (empty($alertas)) {
                // Verificar que el usuario no este registrado
                $resultado = $usuario->existeUsuario();

                if ($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                } else {
                    //Vid 465, Hashear el Password
                    $usuario->hashPassword();

                    //Vid 466,Generar un Token único
                    $usuario->crearToken();

                    //Vid 467, Enviar el Email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    //Vid 468
                    $email->enviarConfirmacion();

                    //Visd 469, Crear el usuario
                    $resultado = $usuario->guardar();

                    //debuguear($usuario);

                    // debuguear($usuario);
                    if ($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
        }

        //Vid 450
        $router->render('auth/crear-cuenta', [
            //Vid 459
            'usuario' => $usuario,
            //Vid 461
            'alertas' => $alertas
        ]);
    }

    //Vid 469
    public static function mensaje(Router $router)
    {
        $router->render('auth/mensaje');
    }

    //Vid 470
    public static function confirmar(Router $router)
    {
        $alertas = [];
        $token = s($_GET['token']);
        $usuario = Usuario::where('token', $token);

        //Vid 471
        if (empty($usuario)) {
            // Mostrar mensaje de error
            Usuario::setAlerta('error', 'Token No Válido');
        } else {
            // Modificar a usuario confirmado
            $usuario->confirmado = "1";
            $usuario->token = null;

            //debuguear($usuario);
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta Comprobada Correctamente');
        }

        //Vid 471 Obtener alertas
        $alertas = Usuario::getAlertas();

        //Vid 470, Renderizar la vista
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }

    /*public static function confirmar(Router $router)
    {
        $alertas = [];

        //sanitizar y leer token desde la url
        $token = s($_GET['token']);

        $usuario = Usuario::where('token', $token);

        if (empty($usuario) || $usuario->token === '') {

            //mostrar mensaje de error
            Usuario::setAlerta('error', 'Token no válido...');
        } else {

            //cambiar valor de columna confirmado
            $usuario->confirmado = '1';
            //eliminar token
            $usuario->token = '';
            //Guardar y Actualizar 
            $usuario->guardar();
            //mostrar mensaje de exito
            Usuario::setAlerta('exito', 'Cuenta verificada exitosamente...');
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }*/
}
