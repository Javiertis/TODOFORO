<?php
session_start();
if (!isset($_SESSION["email"])||($_SERVER['REMOTE_ADDR']!=$_SESSION['miip']))
{
    header("location:index.php");
}
else
{
    if($_GET['confirmado'])
    {
        $idhilo=$_GET["idhilo"];
        $idcoment=$_GET["idcoment"];
        $nickname=$_SESSION["nickname"];
        $pagAnterior='location:hilo.php?id='.$idhilo;
        require_once 'conexionBD.php';
        $conexion=conectarBD(HOST,USUARIO,CONTRASEÑA,BD);
        if($conexion){
            $consulta='DELETE FROM comentarios where idhilo=? and idcomentario=? and creador="'.$nickname.'"';
            $preparada=$conexion->prepare($consulta);
            $preparada->bind_param('ii',$idhilo,$idcoment);
            if($preparada->execute())
            {
               echo '<script>alert("El comentario se ha eliminado correctamente.");</script>';
               header($pagAnterior);
            }else {
               echo '<script>alert("Ha ocurrido algun error");</script>';
               header($pagAnterior);
            }
        }
        $conexion->close();
    }
    else
    {
        $idhilo=$_GET["idhilo"];
        $idcoment=$_GET["idcoment"];
        echo "<p>Realmente quieres borrar el comentario</p>";
        echo '<button><a href="eliminarcomentario.php?idhilo='.$idhilo.'&idcoment='.$idcoment.'&confirmado=true">Sí,eliminar</a>';
        echo '<button><a href="hilo.php?id='.$idhilo.'">No, volver</a>';
    }
}