<?php
require_once 'funciones.php';
session_start();
if(isset($_SESSION['nickname'])&&($_SERVER['REMOTE_ADDR']==$_SESSION['miip'])){
    $haysesion=true;
}
else{
    $haysesion=false;
}
$datos=infoTema($_GET['id']);
$idtema=$datos[0];
$nombretema=$datos[1];
$descripcion=$datos[3];
$creacionhilook=crearHilo($idtema)

?>
	<?php
	include 'head.php';
	if(infoTema($idtema)){?>
<h1><?php echo $nombretema?></h1>
<h4><?php echo '<span class="descripcion">'.$descripcion?></span></h4>

<?php 
	}
lista_hilos($idtema);

if(!infoTema($idtema)){
    echo 'Esta página no existe, debes entrar desde un enlace válido, y si ya lo hiciste contacta con los administradores.<br><br>';
    echo '<a href="index.php"><input type="button" class="boton" value="Volver al índice"  name="volver">';
    die;
}

echo '<br>';
if(isset($_POST['crearunhilo']))
{
    if(!$haysesion)
    {
        echo '<p>Para poder crear un hilo necesitas estar logueado</p>';
        echo '<p><a href="login.php">Inicia sesión</a> o <a href="register.php">registrate</a></p>';
    }
    else
    {
        
    ?>
    <form action="" method=POST>
    	<table>
    		<tr>
    			<td>Nombre:</td>
    			<td><input type="text" name="nombrehilo" placeholder="Nombre para el hilo"></td>
    		</tr>
    		<tr>
    			<td>Descripcion:</td>
    			<td><input type="text" name="descripcionhilo" maxlength="80" placeholder="Introduce una descripción"></td>
    		</tr>
    		<tr>
    			<td colspan=2>
    				<input type="submit" class="boton" name="crearhilo" value="Publicar">
    			</td>
    		</tr>
    	</table>
    </form>
    
    <?php
    }
}
else
{
    if(isset($creacionhilook))
    {
        if($creacionhilook)
        {
            echo '<p>El hilo se creó correctamente</p>';
        }
        else
        {
            echo '<p>Se produjo un error al intentar crear el hilo</p>';
        }
    }
    echo '<form action="" method=POST>';
    echo '<input type="submit" class="boton" name="crearunhilo" value="Crear un hilo">';
    echo '</form>';
}

?>

<br>
<br>
<br>
<br>
<a href="javascript:window.history.go(-1);"><input type="button" class="boton" value="Volver"  name="volver"></a>
</div>
</body>
</html>
