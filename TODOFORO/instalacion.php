<?php
require_once 'conexionBD.php';
$conexion=conectarBD(HOST, USUARIO, CONTRASEÑA,'');
if($conexion){
    $fichero=file_get_contents('forofinal.sql');
    if ($fichero){
        $script=$conexion->query($fichero);
        if ($script){
            echo "Se ha ejecutado correctamente."?> <a href=index.php>Inicio</a> <?php ;
        }else{
            echo "Ha habido algun error en la ejecución del script de isntalación.";
        }
    }else{
        echo "No existe el fichero.";
    }
}?>