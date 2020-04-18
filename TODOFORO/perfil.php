<?php
require_once 'funciones.php';
session_start();
if(isset($_SESSION['nickname'])&&($_SERVER['REMOTE_ADDR']==$_SESSION['miip'])){
    $haysesion=true;
}else{
    $haysesion=false;
}
if($haysesion){
    if(isset($_POST['subirfoto'])){
        if(is_uploaded_file($_FILES['fotosubida']['tmp_name']))
        {
            $msg='';
            $carpeta="fotosperfil";
            if(!is_dir($carpeta))
            {
                mkdir($carpeta);
            }
            if ($_FILES['fotosubida']['size']>1000000){
                $msg=$msg.'<span id="error">El archivo es mayor que 1MB, debes reducirlo antes de subirlo</span><br>';
            }
            if (!($_FILES['fotosubida']['type'] =="image/jpeg")){
                $msg=$msg.'<span id="error">Tu archivo tiene que ser JPG. Otros archivos no son permitidos</span><br>';
            }
            $nombrefoto=$carpeta.'/'.$_SESSION['nickname'].'_profile.jpg';
            if(!move_uploaded_file($_FILES['fotosubida']['tmp_name'], $nombrefoto))
            {
                $msg=$msg.'<span id="error">Ocurrió un error al subir la foto de perfil.</span>';
            }
            else
            {
                $msg=$msg."--Se a actualizado la foto de perfil satisfactoriamente--";
            }
        }
    }
    if(isset($_POST['actualizardatos'])){
        require_once 'conexionBD.php';
        if(trim($_POST['cambiarnombre'])!='') {
            $conexion=conectarBD(HOST,USUARIO,CONTRASEÑA,BD);
            if($conexion){
                $cambiarnombre=false;
                $nuevonombre=$_POST['cambiarnombre'];
                $consulta="UPDATE usuarios SET nombre=? WHERE nickname='$_SESSION[nickname]'";
                $preparada=$conexion->prepare($consulta);
                $preparada->bind_param('s',$nuevonombre);
                if($preparada->execute()){
                    $cambiarnombre=true;
                }
            }
            $conexion->close();
        }
        /*
        if(trim($_POST['cambiarnick'])!='')
        {
            $conexion=conectarBD(HOST,USUARIO,CONTRASEÑA,BD);
            if($conexion)
            {
                $cambiarnick=false;
                $nuevonick=$_POST['cambiarnick'];
                $consulta="UPDATE usuarios SET nickname=? WHERE email='$_SESSION[email]'";
                $preparada=$conexion->prepare($consulta);
                $preparada->bind_param('s',$nuevonick);
                if($preparada->execute())
                {
                    $cambiarnick=true;
                }
            }
            $conexion->close();
        }
        */
        if(trim($_POST['cambiarcontraseña'])!=''){
            $conexion=conectarBD(HOST,USUARIO,CONTRASEÑA,BD);
            if($conexion){
                $cambiarcontraseña=false;
                $nuevacontraseña=password_hash($_POST['cambiarcontraseña'], PASSWORD_BCRYPT);
                $consulta="UPDATE usuarios SET pass=? WHERE nickname='$_SESSION[nickname]'";
                $preparada=$conexion->prepare($consulta);
                $preparada->bind_param('s',$nuevacontraseña);
                if($preparada->execute())
                {
                    $cambiarcontraseña=true;
                }
            }
            $conexion->close();
        }
        if(trim($_POST['cambiardescripcion'])!=''){
            $conexion=conectarBD(HOST,USUARIO,CONTRASEÑA,BD);
            if($conexion){
                $cambiardescripcion=false;
                $nuevadescripcion=$_POST['cambiardescripcion'];
                $consulta="UPDATE usuarios SET descripcion=? WHERE nickname='$_SESSION[nickname]'";
                $preparada=$conexion->prepare($consulta);
                $preparada->bind_param('s',$nuevadescripcion);
                if($preparada->execute()){
                    $cambiardescripcion=true;
                }
            }
            $conexion->close();
        }
    }
    if(isset($_POST['borrarcuentadef']))
    {
        require_once 'conexionBD.php';
        $conexion=conectarBD(HOST,USUARIO,CONTRASEÑA,BD);
        if($conexion){
            if($conexion->query("DELETE FROM usuarios WHERE nickname='$_SESSION[nickname]'")){
                header("Location:logout.php");
            }
            $conexion->close();
        }
    }
}

include_once 'head.php';
if(!$haysesion){
    echo '<p>Para entrar a tu perfil necesitas estar logueado. Inicia sesión o registrate clicando en el menú de arriba.</p>';
    //echo '<p><a href="login.php">Inicia sesión</a> o <a href="register.php">registrate</a></p>';
}else{
    echo '<div class="tema"><h3>Bienvenido de nuevo '.$_SESSION['nombre'].'</h3>';
    echo '<p>Si lo deseas <a href="mostrarperfil.php?nickname='.$_SESSION['nickname'].'">aquí</a> puedes ver tu perfil público</p>';
?>
<form action="" enctype="multipart/form-data" method="POST">
    <table>
    	<tr>
    		<td>
    			Tu foto actual:
    		</td>
    		<td>
    			<?php echo '<img src="fotosperfil/'.$_SESSION['fotoperfil'].'" height="100px" width="100px">';?>
    		</td>
    	</tr>
        <tr>
        	<td>
            	Cambiar Nombre:
            </td>
            <td>
            	<input type="text" name="cambiarnombre"/>
            </td>
        </tr>
    <!--<tr>
            <td>
            	Cambiar Nickname:
            </td>
            <td>
            	<input type="text" name="cambiarnick"/>
            </td>
        </tr> -->
        <tr>
            <td>
            	Cambiar Contraseña:
            </td>
            <td>
            	<input type="password" name="cambiarcontraseña"/>
            </td>
        </tr>
        <tr>
            <td>
            	Actualizar Descripción:
            </td>
            <td>
            	<textarea rows="4" cols="23" name="cambiardescripcion" maxlength="80"></textarea>
            </td>
        </tr>
        <tr>
        	<td>
        		Subir/actualizar imagen:
        	</td>
        	<td>
        		<input name="fotosubida" type="file"/>
				<input type="submit" class="boton" name="subirfoto" value="Subir imagen"/>
        	</td>
        </tr>
        <?php if(isset($msg)){echo '<tr><td colspan=2>'.$msg.'</td></tr>';}?>
        <tr>
            <td colspan="2">
            	<input type="submit" class="boton" name="actualizardatos" value="Actualizar Datos"/>
            </td>
        </tr>
        <tr >
        	<td colspan="2">
        		<?
        		  if(isset($_POST['actualizardatos'])){
        		      if(isset($cambiarnombre)&&$cambiarnombre||isset($cambiarnick)&&$cambiarnick||isset($cambiarcontraseña)&&$cambiarcontraseña||isset($cambiardescripcion)&&$cambiardescripcion){
        		          echo 'Datos actualizados correctamente';
        		      }else{
        		          echo 'Se produjo un error al actualizar los datos';
        		      }
        		  }
        		?>
        	</td>
        </tr>
        <tr>
            <td colspan="2">
            <?php 
            if(isset($_POST['borrarcuenta'])){
                echo '<span id="error">¿Realmente estás seguro de <b>ELIMIAR</b> tu cuenta <b>DEFINITIVAMENTE</b>?</span><br>';
                echo '<input type="submit" class="boton" name="borrarcuentadef" value="Si, borrar"/>';
            }
            else{
                echo '<input type="submit" class="boton" name="borrarcuenta" value="Borrar Cuenta"/>';
            }
            ?>
            </td>
        </tr>
    </table>
</form>

<?php 
}
?>
<br>
<a href="index.php"><input type="button" class="boton" value="Volver"  name="volver"></a>
</div>
</div>
</body>
</html>
