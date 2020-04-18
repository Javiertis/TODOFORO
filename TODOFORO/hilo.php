<?php
require_once 'funciones.php';
session_start();
if(isset($_SESSION['nickname'])&&($_SERVER['REMOTE_ADDR']==$_SESSION['miip'])){
    $haysesion=true;
}
else{
    $haysesion=false;
}
cerrarHilo();

$datoshilo=infoHilo($_GET['id']);
$idhilo=$datoshilo[0];
//$creador=$datoshilo[1];
$idtema=$datoshilo[2];
$nombrehilo=$datoshilo[3];
//$fechacreacionhilo=$datoshilo[4];
$descripcionilo=$datoshilo[5];
$cerrado=$datoshilo[6];

$datostema=infoTema($idtema);
$idtema=$datostema[0];
$nombretema=$datostema[1];
//$fechacreaciontema=$datos[2];
//$descripciontema=$datos[3];

if(isset($_POST['publicarcomentario']))
{
    $conexion=conectarBD(HOST,USUARIO,CONTRASEÑA,BD);
    if($conexion)
    {
        $nickname=$_SESSION['nickname'];
        $comentario=$_POST['comentario'];
        $idhilo=$_GET['id'];
        $consulta="INSERT INTO comentarios VALUES(NULL,$idhilo,'$nickname',current_timestamp(),?)";
        $preparada=$conexion->prepare($consulta);
        $preparada->bind_param('s',$comentario);
        if($preparada->execute())
        {
            $creacioncomentariook=true;
        }
        else
        {
            $creacioncomentariook=false;
        }
    }
}
//$creacioncomentariook=publicarComentario($idhilo);
?>
	<?php
	include 'head.php';
	if(infohilo($_GET['id']))
	{
	?>
	
	<h1 class="hilos"><?php echo '<a href="tema.php?id='.$idtema.'" class="hilos">'.$nombretema.'</a> / '.strtoupper($nombrehilo).'';?></h1>
	<h3><?php echo '<span class="descripcion">'.$descripcionilo.'</span>';?></h3>
	
	
<?php 
	}
$nobotoncomentario=false;
if(!lista_comentarios($_GET['id']))
{
    if(!infohilo($_GET['id']))
    {
        echo 'Esta página no existe, debes entrar desde un enlace válido, y si ya lo hiciste contacta con los administradores.<br><br>';
        echo '<a href="index.php"><input type="button" class="boton" value="Volver al índice"  name="volver">';
        
    }
    else
    {
        echo 'Aun no hay comentarios, introduce el primero.';
        ?>
    
    <form action="" method=POST>
    	<table>
    		<tr>
    			<td>Comentario:</td>
    			<td>
    				<textarea rows="10" cols="40" maxlength="400" name="comentario"></textarea>
    			</td>
    		</tr>
    		<tr>
    			<td colspan=2>
    				<input type="submit" class="boton" name="publicarcomentario" value="Publicar">
    			</td>
    		</tr>
    	</table>
    </form>
    
    <?php
   $nobotoncomentario=true;
    }
}

if(infohilo($_GET['id']))
{
    if($haysesion)
    {
        if($_SESSION['nivelacceso']=='Admin'||$_SESSION['nivelacceso']=='Modder')
        {
            echo '<form action="" method="POST"><br>';
            if($cerrado=='N')
            {
                echo '<input type="submit" class="boton" name="cerrarhilo" value="Cerrar Hilo"/>  ';
            }
            else
            {
                echo '<input type="submit" class="boton" name="abrirhilo" value="Abrir Hilo"/>';
            }
            echo '<br></form>';
        }
    }
}
echo '<br>';
if(isset($_POST['añadiruncomentario']))
{
    if(!$haysesion)
    {
        echo '<p>Para contribuir en el hilo necesitas entrar con tu cuenta, si no tienes create una. Puedes acceder en el menú de arriba.</p>';
    }
    else
    {
        ?>
    <form action="" method=POST>
    	<table>
    		<tr>
    			<td>Comentario:</td>
    			<td>
    				<textarea rows="10" cols="40" maxlength="400" name="comentario"></textarea>
    			</td>
    		</tr>
    		<tr>
    			<td colspan=2>
    				<input type="submit" class="boton" name="publicarcomentario" value="Publicar">
    			</td>
    		</tr>
    	</table>
    </form>
    
    <?php
    }
}
else
{
    if(infohilo($_GET['id']))
    {
        if($cerrado=='N')
        {
            if(isset($creacioncomentariook))
            {
                if($creacioncomentariook)
                {
                    echo '<p>El comentario se creó correctamente</p>';
                }
                else
                {
                    echo '<p>Se produjo un error al intentar añadir el comentario</p>';
                }
            }
            if(!$nobotoncomentario)
            {
                echo '<form action="" method=POST>';
                echo '<input type="submit" class="boton" name="añadiruncomentario" value="Comentar">';
                echo '</form>';
            }
        }
        else
        {
            echo '<p>Un administrador ha cerrado el hilo, no se pueden publicar nuevos comentarios.</p>';
        }
    }
}
?>


<br>
<br>
<br>
<br>
<?php 
if(infohilo($_GET['id']))
{
?>
<a href="javascript:window.history.go(-1);"><input type="button" class="boton" value="Volver"  name="volver"></a>
<?php }?>
</div>
</body>
</html>