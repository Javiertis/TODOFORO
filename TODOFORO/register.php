<?php

session_start();
if(isset($_SESSION['nickname'])&&($_SERVER['REMOTE_ADDR']==$_SESSION['miip'])){
    header("Location:perfil.php");
}
if(isset($_POST['registrar']))
{
    require_once 'funciones.php';
    //Comprovación de valores
    $nombre=trim(strip_tags($_POST['nombreusuario']));
    $nick=trim(strip_tags($_POST['nick']));
    $email=trim(strip_tags($_POST['email']));
    $pass=trim(strip_tags($_POST['pass']));
    $pass2=trim(strip_tags($_POST['pass2']));
    $nombrevacio=false;
    $nickvacio=false;
    $emailvacio=false;
    $passvacia=false;
    $passnocoincide=false;
    $nickexiste=false;
    $emailexiste=false;
    
    if($nombre==''){
        $nombrevacio=true;
    }
    if(usuario_existe($nick)) {
        $nickexiste=true;
    }
    if($nick==''){
        $nickvacio=true;
    }
    if(mail_existe($email)){
        $emailexiste=true;
    }
    if($email==''){
        $emailvacio=true;
    }
    if($pass==''){
        $passvacia=true;
    }elseif($pass==$pass2){
        $passnocoincide=false;
        $password=password_hash($pass, PASSWORD_BCRYPT);
    }
    else
    {
        $passnocoincide=true;
    }
    
    if(($nombrevacio!=true)&&($nickvacio!=true)&&($emailvacio!=true)&&($passvacia!=true)&&($passnocoincide!=true))
    {
        registrar($nick,$email,$nombre,$password);
    }
    else 
    {
        ?>
        	
            <?php include_once 'head.php';?>
            <div class="tema">
            <h3>Formulario de Registro</h3>
            <form  method="POST" action="">
            <table>
            
              <tr>
             	<td>Nombre de Usuario:</td>
             	<td><input type="text" name="nombreusuario" placeholder="Introduce un nombre." <?php if(isset($_POST['nombreusuario'])){echo 'value="'.$_POST['nombreusuario'].'"';}?>></td>
             	<?php if($nombrevacio){echo '<td id="error">El nombre de usuario no puede estar vacío.</td>';}?>
             </tr>
             <tr>
             	<td>Nick:</td>
             	<td><input type="text" name="nick" placeholder="Introduce un nick." <?php if(isset($_POST['nick'])){echo 'value="'.$_POST['nick'].'"';}?>></td>
             	<?php if($nickvacio){echo '<td id="error">El nick no puede estar vacío</td>';}?>
             	<?php if($nickexiste){echo '<td id="error">Ya existe un usuario con ese nick</td>';}?>
             </tr>
             <tr>
             	<td>Email:</td>
             	<td><input type="email" name="email" placeholder="Introduce tu email." <?php if(isset($_POST['email'])){echo 'value="'.$_POST['email'].'"';}?>></td>
             	<?php if($emailvacio){echo '<td id="error">El email no puede estar vacío</td>';}?>
             	<?php if($emailexiste){echo '<td id="error">Ya existe un usuario registrado con este email</td>';}?>
             </tr>
             <tr>
             	<td>Contraseña:</td>
             	<td><input type="password" name="pass" placeholder="Introduce una contraseña."></td>
             	<?php if($passvacia){echo '<td id="error">La contraseña no puede estar vacía</td>';}?>
             </tr>
             <tr>
             	<td>Repetir Contraseña:</td>
             	<td><input type="password" name="pass2" placeholder="Introduce una contraseña."></td>
             	<?php if($passnocoincide){echo '<td id="error">La contraseña no coincide</td>';}?>
             </tr>
             <tr>
             <tr height=15px></tr>
             <tr>
             	<td align="left"><a href="index.php"><input class="boton" type="button" value="Volver al foro"  name="volver" style="height: 25px;width: 90px;"></a></td>
             	<td align="right"><input class="boton" type="submit" value="Registrar"  name="registrar" style="height: 25px; "></td>
             </tr>
             <tr>
             <td>
             	<td>
             		<br>
             	</td>
             </tr>
             <tr>
             	<td colspan=2 align=center style="font-size:12px">
             		¿Ya estás registrado? <a href="login.php">Entrar</a>
             	</td>
             </tr>
             
             </table>
            </form>
            </div>
            </body>
            </html>
        <?php 
    }
}
else
{

?>
<?php include_once 'head.php';?>
<div class="tema">
<h3>Formulario de Registro</h3>
    <form  method="POST" action="">
        <table>
            <tr>
            	<td>
            		Nombre de Usuario:
            	</td>
            	<td>
            		<input type="text" name="nombreusuario" placeholder="Introduce un nombre.">
            	</td>
            </tr>
            <tr>
            	<td>
            		Nick:
            	</td>
            	<td>
            		<input type="text" name="nick" placeholder="Introduce un nick.">
            	</td>
            </tr>
            <tr>
            	<td>
            		Email:
            	</td>
            	<td>
            		<input type="text" name="email" placeholder="Introduce tu email.">
            	</td>
            </tr>
            <tr>
            	<td>
            		Contraseña:
            	</td>
            	<td>
            		<input type="password" name="pass" placeholder="Introduce una contraseña.">
            	</td>
            </tr>
            <tr>
            	<td>
            		Repetir Contraseña:
            	</td>
            	<td>
            		<input type="password" name="pass2" placeholder="Introduce una contraseña.">
            	</td>
            </tr>
            <tr>
            <tr height=15px>
            </tr>
            <tr>
            	<td align="left">
            		<a href="index.php"><input class="boton" type="button" value="Volver al foro"  name="volver" style="height: 25px;width: 90px;"></a>
            	</td>
            	<td align="right">
            		<input class="boton" type="submit" value="Registrar"  name="registrar" style="height: 25px; ">
            	</td>
            </tr>
            <tr>
            	<td>
            		<br>
            	</td>
            </tr>
            <tr>
            	<td colspan=2 align=center style="font-size:12px">
            		¿Ya estás registrado? <a href="login.php">Entrar</a>
            	</td>
            </tr>
        </table>
    </div>
    </form>
</div>
</body>
</html>
<?php 
}
?>