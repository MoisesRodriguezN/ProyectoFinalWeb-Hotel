<?php

session_start();
include_once '../../Model/ReservaHabitacion.php';

if ($_SESSION['logueadoUser'] == true) {
    $usuario = $_SESSION[nombreUser];

    $data['datos'] = ReservaHabitacion::getDatosReservaHab($usuario);
    
    $numeroHabs = count($data);
    
    include_once '../../View/usuario/index.php';
} else {
    header("location:../../Controller/usuario/login.php");
}
    