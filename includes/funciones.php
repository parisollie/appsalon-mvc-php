<?php

function debuguear($variable): string
{
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html): string
{
    $s = htmlspecialchars($html);
    return $s;
}

function iniciarSession()
{
    if (!isset($_SESSION)) {
        //iniciarSession();
        session_start();
    }
}

//Vid 524, Función que revisa que el usuario este autenticado
function isAuth(): void
{
    if (!isset($_SESSION['login'])) {
        header('Location: /');
    }
}
//Vid 534
function esUltimo(string $actual, string $proximo): bool
{

    if ($actual !== $proximo) {
        return true;
    }
    return false;
}


//Vid 540,proteccion de rutas
function isAdmin(): void
{
    if (!isset($_SESSION['admin'])) {
        header('Location: /');
    }
}
