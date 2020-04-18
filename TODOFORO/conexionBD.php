<?php
CONST HOST="localhost";
CONST USUARIO="pruebaphp";
CONST CONTRASEÑA="prueba";
CONST BD="foro2";

function conectarBD($host,$usuario,$contraseña,$bd)
{
    //Conectamos con el SGBD
    $conexion=new mysqli($host,$usuario,$contraseña,$bd);
    //Comprobamos que la conexion se ha realizado correctamente
    if ($conexion->connect_error!=NULL)
    {
        $conexion=NULL;
    }
    return $conexion;
}
?>