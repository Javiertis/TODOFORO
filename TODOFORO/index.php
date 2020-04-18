<?php
require_once 'funciones.php';
require_once 'conexionBD.php';
session_start();
if(isset($_SESSION['nickname'])&&($_SERVER['REMOTE_ADDR']==$_SESSION['miip'])){
    $haysesion=true;
}
else{
    $haysesion=false;
}

if(isset($_POST['crearuntema']))
{
    $conexion=conectarBD(HOST,USUARIO,CONTRASEÑA,BD);
    if($conexion)
    {
        $nombretema=$_POST['nombretema'];
        $descripciontema=$_POST['descripciontema'];
        $consulta="INSERT INTO temas VALUES(NULL,?,current_timestamp(),?)";
        $preparada=$conexion->prepare($consulta);
        $preparada->bind_param('ss',$nombretema,$descripciontema);
        if($preparada->execute())
        {
            $creaciontema=true;
        }
        else
        {
            $creaciontema=false;
        }
    }
    $conexion->close();
}

?>
	<?php
	include 'head.php';
   lista_temas();
if($haysesion)
{
    if($_SESSION['nivelacceso']=='Admin')
    {
        echo '<br><br>Eres Administrador, ¿Quires añadir un nuevo tema?';
        ?>
        <form action="" method="POST">
        	<table>
        		<tr>
        			<td>
        				Nombre del Tema:
        			</td>
        			<td>
        				<input type="text" name="nombretema">
        			</td>
        		</tr>
        		<tr>
        			<td>
        				Descripcion del Tema:
        			</td>
        			<td>
        				<input type="text" name="descripciontema">
        			</td>
        		</tr>
        		<tr>
        			<td colspan="2">
        				<input type="submit" class="boton" name="crearuntema" value="Crear">
        			</td>
        		</tr>
        	</table>
        </form>
        <br>
        <br>
        
        <?php 
        
        if(isset($creaciontema))
        {
            if($creaciontema)
            {
                echo 'Se creó el tema';
            }
            else
            {
                echo 'El tema no se creó';
            }
        }
    }
}
?>
</div>
</body>
</html>