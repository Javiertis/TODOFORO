<?php 
session_start();
// Compruebo si se ha propagado la session
if(isset($_SESSION['nickname'])&&($_SERVER['REMOTE_ADDR']==$_SESSION['miip'])){
	//Vacio las variables de sesion
	$_SESSION=array();
	//Establezco una cookie con el mismo nombre de sesion con tiempo negativo
	$params = session_get_cookie_params(); 
	setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"] );
	//Destruyo la sesion
	session_destroy();
	//Redirigo a otra pagina
	header("Location:index.php");
}
else {
	header("Location:index.php");
}
?>