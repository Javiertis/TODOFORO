<?php
require_once 'conexionBD.php';
$conexion=conectarBD(HOST, USUARIO, CONTRASE�A,'');
if($conexion){
    $fichero=file_get_contents('forofinal.sql');
    if ($fichero){
        $script=$conexion->query($fichero);
        if ($script){
            echo "Se ha ejecutado correctamente."?> <a href=index.php>Inicio</a> <?php ;
        }else{
            echo "Ha habido algun error en la ejecuci�n del script de isntalaci�n.";
        }
    }else{
        echo "No existe el fichero.";
    }
}?>