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
if (isset($_POST['actualizarnivelacceso']))
{
    $conexion=conectarBD(HOST,USUARIO,CONTRASEÑA,BD);
    if($conexion)
    {
        $cambiarnivelacceso=false;
        $nuevonivelacceso=$_POST['nivelacceso'];
        $consulta="UPDATE usuarios SET nivelacceso=? WHERE nickname='$_POST[nickuser]'";
        $preparada=$conexion->prepare($consulta);
        $preparada->bind_param('s',$nuevonivelacceso);
        if($preparada->execute())
        {
            $cambiarnivelacceso=true;
        }
    }
    $conexion->close();
}
$conexion=conectarBD(HOST,USUARIO,CONTRASEÑA,BD);
if($conexion)
{
    $consulta="SELECT email,nombre,nickname,nivelacceso,creado,descripcion FROM usuarios WHERE nickname=?";
    $preparada=$conexion->prepare($consulta);
    $preparada->bind_param('s',$_GET['nickname']);
    if($preparada->execute())
    {
        $datosuser=array();
        $preparada->bind_result($datosuser[],$datosuser[],$datosuser[],$datosuser[],$datosuser[],$datosuser[]);
        if($preparada->fetch())
        {
            
            
	include 'head.php';?>
	<h2>Perfil de <?php echo $datosuser[2]?></h2>
            <div class="tema">
            <table>
            	<tr>
            		<td>
            			<img src="fotosperfil/<?php echo $datosuser[2].'_profile.jpg'?>" height="40px" width="40px">
            		</td>
            	</tr>
            	<tr>
            		<td>
            			<h3><?php echo $datosuser[1]?></h3>
            		</td>
            	</tr>
            	<tr>
            		<td>
            			Descripción:
            		</td>
            	</tr>
            	<tr>
            		<td>
            			<?php echo $datosuser[5]?>
            		</td>
            	</tr>
            	<tr height=15px>
            	</tr>
            	<tr>
            		<td>
            			Usuario desde: <?php echo substr($datosuser[4],0,-9)?>
            		</td>
            	</tr>
            	<tr height=15px>
            	</tr>
            	<tr>
            		<td>
            			Nivel de acceso: <?php echo $datosuser[3]?>
            		</td>
            		<td>
            			<?php 
            			if($haysesion)
            			{
            			    if($_SESSION['nivelacceso']=='Admin')
            			    {
            			        echo '<form action="" method="POST">';
            			        echo '<select name="nivelacceso">';
            			        echo '<option value="Miembro">Miembro</option>';
            			        echo '<option value="Admin">Admin</option>';
            			        echo '<option value="Modder">Modder</option>';
            			        echo '</select>';
            			        echo '<input type="submit" class="boton" name="actualizarnivelacceso" value="Actualizar">';
            			        echo '<input type="hidden" name="nickuser" value="'.$datosuser[0].'">';
            			        echo '</form>';
            			    }elseif($_SESSION['nivelacceso']=='Modder'){
								echo '<form action="" method="POST">';
            			        echo '<select name="nivelacceso">';
            			        echo '<option value="Miembro">Miembro</option>';
            			        echo '<option value="Modder">Modder</option>';
            			        echo '</select>';
            			        echo '<input type="submit" class="boton" name="actualizarnivelacceso" value="Actualizar">';
            			        echo '<input type="hidden" name="nickuser" value="'.$datosuser[0].'">';
            			        echo '</form>';
							}
            			    if(isset($cambiarnivelacceso))
            			    {
            			        if($cambiarnivelacceso)
            			        {
            			            echo '(Se actualizó correctamente)';
            			        }
            			    }
            			}
            			
            			?>
            		</td>
            	</tr>
            	<tr height=15px>
            	</tr>
            </table>
            <?php
            if($haysesion)
            {
                echo '<a href="perfil.php"><input type="button" class="boton" name="perfilpriv" value="Editar perfil"></a>';
            }
            ?>
            </div>
            <br>
            <br>
            <br>
            <a href="javascript:window.history.go(-1);"><input type="button" class="boton" value="Volver"  name="volver"></a>
            
            
            <?php
        }
        else
        {
            header("Location:index.php");
        }
    }
}
$conexion->close();



?>