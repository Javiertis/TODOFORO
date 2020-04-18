<?php
session_start();
if (!isset($_SESSION['nickname'])||($_SERVER['REMOTE_ADDR']!=$_SESSION['miip']))
{
    header("location:index.php");
}
elseif($_SESSION['nivelacceso']=='Miembro')
{
    header("location:index.php");
}
else
{
    if($_GET['confirmado'])
    {
        $idhilo=$_GET['idhilo'];
        require_once 'conexionBD.php';
        $conexion=conectarBD(HOST,USUARIO,CONTRASEÑA,BD);
        if($conexion){
            $consulta="DELETE FROM hilos where idhilo=?";
            $preparada=$conexion->prepare($consulta);
            $preparada->bind_param('i',$idhilo);
            if($preparada->execute())
            {
                echo '<script>alert("El hilo se ha eliminado correctamente.");</script>';
                header("Location:index.php");
                echo '<button><a href="index.php">Volver</a>';
            }
            else 
            {
                echo '<script>alert("Ha ocurrido algun error");</script>';
                echo '<button><a href="index.php">Volver</a>';
            }
        }
        $conexion->close();
    }
    else
    {
        $idhilo=$_GET['idhilo'];
        echo "<p>¿Realmente quieres borrar el tema?</p>";
        echo '<button><a href="eliminarhilo.php?idhilo='.$idhilo.'&confirmado=true">Sí, eliminar</a>';
        echo '<button><a href="index.php">No, mejor volver</a>';
    }
}