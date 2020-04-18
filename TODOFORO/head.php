<?php
if(isset($_SESSION['nickname'])&&($_SERVER['REMOTE_ADDR']==$_SESSION['miip'])){
    $haysesion=true;
}
else{
    $haysesion=false;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Forotodo</title>
    <link rel="stylesheet" href="style.css" type="text/css" media="screen" />
</head>
<body>
<header id="head">
	<nav>
    	<ul  id="nav">
    		<li>
    			<a href="index.php">Inicio</a>
    		</li>
            <li>
    			<a href="perfil.php">Perfil</a>
    		</li>
    		<?php 
    		if ($haysesion)
    		{
    		    echo '<li><a href="logout.php">Cerrar Sesión</a></li>';
    		}
    		else 
    		{
    		    echo '<li><a href="login.php">Inicia sesión</a></li><li><a href="register.php">Registrate</a></li>';
    		}
    		?>
		</ul>
	</nav>
	<span class="foro">Forotodo</span>
</header>

<div class="body">
	
