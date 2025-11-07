<?php
    // codigo de conexion
    $servername = "localhost";
    $username = "root";
    $password = "";
    $base_datos = "proyecto_suplementos";

    $conn = mysqli_connect($servername, $username, $password, $base_datos);

    if(!$conn){
        die("Conexion fallida". mysqli_connect_error());
    }
?>