<?php
require_once 'funciones.php';
session_start();
if(isset($_SESSION['nickname'])&&($_SERVER['REMOTE_ADDR']==$_SESSION['miip'])){
    header("Location:perfil.php");
}
if(isset($_REQUEST['login'])&&($noerror=valida_usuario($_REQUEST['nickname'],$_REQUEST['pass'])))
{
    require_once 'conexionBD.php';
    $conexion=conectarBD(HOST,USUARIO,CONTRASEÑA,BD);
        $_SESSION['nickname']=$_POST['nickname'];
        $_SESSION['idsesion']=session_id();
        $_SESSION['miip']=$_SERVER['REMOTE_ADDR'];
        $_SESSION['fotoperfil']=$_SESSION['nickname'].'_profile.jpg';
        
        if($conexion)
        {
            $consulta=$conexion->query("SELECT nombre,nivelacceso,email,pass FROM usuarios WHERE nickname='$_POST[nickname]'");
            $resultado=$consulta->fetch_row();
            $_SESSION['nombre']=$resultado[0];
            $_SESSION['nivelacceso']=$resultado[1];
            $_SESSION['email']=$resultado[2];
            $_SESSION['pass']=$resultado[3];
            
        }
        
        header("Location:index.php");
}
else
    {?>
    <?php include_once 'head.php';?>
    <div class="tema">
    <h3>Formulario de Logeo</h3>
    <form  method="POST" action="">
        <table>
            <?php 
            if(isset($noerror))
            {
            ?>
            <tr>
            	<td colspan="2">
            		<font color="red"><b>El usuario no existe o contraseña invalida</b></font>
            	</td>
            </tr>
            <?php }?>   
			<tr>
            	<td>
            		Nickname:
            	</td>
             	<td>
             		<input type="text" name="nickname" <?php if(isset($_POST['nickname'])){echo 'value="'.$_POST['nickname'].'"';}?>>
             	</td>
            </tr>
            <tr>
             	<td>
             		Contraseña:
             	</td>
             	<td>
             		<input type="password" name="pass">
             	</td>
            </tr>
            <tr height=15px>
            </tr>
            <tr>
            	<td align="left">
            		<a href="index.php"><input class="boton" type="button" value="Volver al foro"  name="volver" style="height: 25px;width: 90px;"></a>
            	</td>
             	<td align="right">
             		<input class="boton" type="submit" value="Login" name="login" style="height: 25px">
             	</td>
            </tr>
            <tr>
             	<td>
             		<br>
             	</td>
            </tr>
            <tr>
             	<td colspan=2 align=center style="font-size:12px">
             		¿Aún no estás registrado? <a href="register.php">Registrarte</a>
             	</td>
            </tr>
         </table>
    </form>
    </div>
    </div>
</body>
</html>   
   <?php  
}
?>