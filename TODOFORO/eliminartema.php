<?php
session_start();
if (!isset($_SESSION['nickname'])||($_SERVER['REMOTE_ADDR']!=$_SESSION['miip']))
{
    header("location:index.php");
}
elseif($_SESSION['nivelacceso']=='Miembro'||$_SESSION['nivelacceso']=='Modder')
{
    header("location:index.php");
}
else
{
    if($_GET['confirmado'])
    {
        $idtema=$_GET['idtema'];
        require_once 'conexionBD.php';
        $conexion=conectarBD(HOST,USUARIO,CONTRASEÑA,BD);
        if($conexion){
            $consulta="DELETE FROM temas where idtema=?";
            $preparada=$conexion->prepare($consulta);
            $preparada->bind_param('i',$idtema);
            if($preparada->execute())
            {
                echo '<script>alert("El tema se ha eliminado correctamente.");</script>';
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
        $idtema=$_GET["idtema"];
        echo "<p>¿Realmente quieres borrar el tema?</p>";
        echo '<button><a href="eliminartema.php?idtema='.$idtema.'&confirmado=true">Sí, eliminar</a>';
        echo '<button><a href="index.php">No, mejor volver</a>';
    }
}