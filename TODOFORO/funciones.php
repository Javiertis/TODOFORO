<?php
//Validacion usuario email
function valida_usuario($nickname,$pass)
{
    require_once 'conexionBD.php';
    $conexion=conectarBD(HOST,USUARIO,CONTRASEÑA,BD);
    if($conexion)
    {
        $consulta="SELECT pass FROM usuarios WHERE nickname=?";
        $preparada=$conexion->prepare($consulta);
        $preparada->bind_param('s',$nickname);
        $hash='';
        if($preparada->execute())
        {
            $preparada->bind_result($hash);
            return (($preparada->fetch())&&(password_verify($pass,$hash)))==true;
        }
        $conexion->close();
    }
    return false;
}


function usuario_existe($nickname)
{
    require_once 'conexionBD.php';
    $conexion=conectarBD(HOST,USUARIO,CONTRASEÑA,BD);
    if($conexion)
    {
        $consulta= "SELECT nombre from usuarios WHERE nickname='$nickname'";
        $resultado=$conexion->query($consulta);
        $datos=$resultado->fetch_row();
        return $datos==true;
    }
}

function mail_existe($email)
{
    require_once 'conexionBD.php';
    $conexion=conectarBD(HOST,USUARIO,CONTRASEÑA,BD);
    if($conexion)
    {
        $consulta= "SELECT nombre from usuarios WHERE email='$email'";
        $resultado=$conexion->query($consulta);
        $datos=$resultado->fetch_row();
        return $datos==true;
    }
}
/*    REGISTRO     */
function registrar($nick,$email,$nombre,$password){
    include_once 'head.php';
    require_once 'conexionBD.php';
    $conexion=conectarBD(HOST,USUARIO,CONTRASEÑA,BD);
    
    $consulta="INSERT INTO usuarios VALUES(?,?,?,?,'Miembro',current_timestamp(),NULL)";
    $preparada=$conexion->prepare($consulta);
    $preparada->bind_param('ssss',$nick,$email,$nombre,$password);
    if($preparada->execute())
    {
        echo '<h3>Usuario registrado correctamente, ya puedes iniciar sesión.</h3>';
        echo '<a href="index.php"><input class="boton" type="button" value="Volver al foro"  name="volver"></a>';
    }
    else
    {
        echo '<h3>Se produjo un error. El usuario no se creó.</h3>';
    }
}
/*    FUNCIONES DE TEMAS   */
function lista_temas()
{
    require_once 'conexionBD.php';
    $conexion=conectarBD(HOST,USUARIO,CONTRASEÑA,BD);
    if($conexion)
    {
        $consulta="SELECT idtema,nombre,fechacreacion,descripcion
                   FROM temas
                   ORDER BY nombre DESC";
        $resultado=$conexion->query($consulta);
        $datos=$resultado->fetch_row();
        if ($datos)
        {
            while($datos)
            {
                echo '<br>';
                echo '<div class="tema">';
                echo '<a href=tema.php?id='.$datos[0].' class="hilos">'.$datos[1].'</a>';
                if(isset($_SESSION['nickname'])&&((($_SESSION['nivelacceso'])=='Admin')||($_SESSION['nivelacceso'])=='Modder'))
                {
                    echo ' <a href="eliminartema.php?idtema='.$datos[0].'&amp;confirmado=false">(Eliminar)</a>';
                }
                echo '<br><span class="descripcion">'.$datos[3].'</span>';
                echo '<br>';
                echo '</div>';
                $datos=$resultado->fetch_row();
            }
        }
        $conexion->close();
    }
    return false;
}

function infoTema($id) {
    require_once 'conexionBD.php';
    $conexion=conectarBD(HOST,USUARIO,CONTRASEÑA,BD);
    if($conexion)
    {
        $consulta="SELECT * FROM temas WHERE idtema=?";
        $preparada=$conexion->prepare($consulta);
        $preparada->bind_param('i',$id);
        if($preparada->execute())
        {
            $datos=array();
            $preparada->bind_result($datos[],$datos[],$datos[],$datos[]);
            if($resultado=$preparada->fetch())
            {
                return $datos;
            }
        }
        $conexion->close();
    }
}

/*     FUNCIONES DE HILO      */

function lista_hilos($idtema)
{
    require_once 'conexionBD.php';
    $conexion=conectarBD(HOST,USUARIO,CONTRASEÑA,BD);
    if($conexion)
    {
        $consulta="SELECT h.idhilo,h.creador,h.idtema,h.nombre,h.fechacreacion,h.descripcion,h.cerrado,max(c.fechacreacion)
            FROM comentarios AS c INNER JOIN hilos AS h ON c.idhilo=h.idhilo
            WHERE h.idtema=$idtema
            GROUP BY h.idhilo
            ORDER BY max(c.fechacreacion) DESC";
        if($resultado=$conexion->query($consulta))
        {
            $datos=$resultado->fetch_row();
            if ($datos)
            {
                while($datos)
                {
                    echo '<br>';
                    echo '<div class="tema">';
                    echo '<a href="hilo.php?id='.$datos[0].'" class="hilos">'.strtoupper($datos[3]).'</a>';
                    if(isset($_SESSION['nickname'])&&((($_SESSION['nivelacceso'])=='Admin')||($_SESSION['nivelacceso'])=='Modder'))
                    {
                        echo ' <a href="eliminarhilo.php?idhilo='.$datos[0].'&amp;confirmado=false">Eliminar</a>';
                    }
                    echo '<div class="metadata"> Creado por:';
                    if($datos[1]==NULL){
                        echo ' <i>"Usuario eliminado"</i></div>';
                    }
                    else{
                        echo '<a href="mostrarperfil.php?nickname='.$datos[1].'">'.$datos[1].'</a> el '.substr($datos[4],0,-9).'</div>';
                    }
                    
                    echo '<span class="descripcion">'.$datos[5].'</span><br>';
                    echo '<span class="info">Ultimo comentario: '.$datos[7].'</span>';
                    echo '<br>';
                    echo '</div>';
                    $datos=$resultado->fetch_row();
                }
            }
        }
        $conexion->close();
        
    }
    return false;
}

function infoHilo($id){
    $conexion=conectarBD(HOST,USUARIO,CONTRASEÑA,BD);
    if($conexion)
    {
        $hilo="SELECT * FROM hilos WHERE idhilo=?";
        $hiloprep=$conexion->prepare($hilo);
        $hiloprep->bind_param('i',$id);
        if($hiloprep->execute())
        {
            $datoshilo=array();
            $hiloprep->bind_result($datoshilo[],$datoshilo[],$datoshilo[],$datoshilo[],$datoshilo[],$datoshilo[],$datoshilo[]);
            if($resultado=$hiloprep->fetch())
            {
                return $datoshilo;
            }
        }
        return false;
    }
    $conexion->close();
}

function crearHilo($idtema) {
    require_once 'conexionBD.php';
    $conexion=conectarBD(HOST,USUARIO,CONTRASEÑA,BD);
    if($conexion)
    {
        if(isset($_POST['crearhilo']))
        {
            $nickname=$_SESSION['nickname'];
            $nombrehilo=$_POST['nombrehilo'];
            $descripcionhilo=$_POST['descripcionhilo'];
            $consulta="INSERT INTO hilos VALUES(NULL,'$nickname',$idtema,?,current_timestamp(),?,'N')";
            $preparada=$conexion->prepare($consulta);
            $preparada->bind_param('ss',$nombrehilo,$descripcionhilo);
            if($preparada->execute())
            {
                $consulta2="SELECT max(idhilo) FROM hilos";
                $result=$conexion->query($consulta2);
                $dato=$result->fetch_row();
                header("Location:hilo.php?id=$dato[0]");
                return true;
            }
            else
            {
                return false;
            }
        }
        $conexion->close();
    }
}

function cerrarHilo() {
    require_once 'conexionBD.php';
    if((isset($_POST['cerrarhilo']))||(isset($_POST['abrirhilo'])))
    {
        if(isset($_POST['cerrarhilo']))
        {
            $conexion=conectarBD(HOST,USUARIO,CONTRASEÑA,BD);
            if($conexion){
                $consulta="UPDATE hilos SET cerrado='Y' WHERE idhilo=$_GET[id]";
                return $conexion->query($consulta)==true;
            }
            $conexion->close();
        }
        else
        {
            $conexion=conectarBD(HOST,USUARIO,CONTRASEÑA,BD);
            if($conexion){
                $consulta="UPDATE hilos SET cerrado='N' WHERE idhilo=$_GET[id]";
                return $conexion->query($consulta)==false;
            }
            $conexion->close();
        }
    }
}

/*         FUNCIONES DE COMENTARIOS    */

function lista_comentarios($idhilo)
{
    require_once 'conexionBD.php';
    $conexion=conectarBD(HOST,USUARIO,CONTRASEÑA,BD);
    if($conexion)
    {
        $consulta="SELECT * FROM comentarios WHERE idhilo=$idhilo";
        $resultado=$conexion->query($consulta);
        $datos=$resultado->fetch_row();
        if ($datos)
        {
            while($datos)
            {
                $query="SELECT * FROM usuarios WHERE nickname='$datos[2]'";
                $resultsuarios=$conexion->query($query);
                $usuario=$resultsuarios->fetch_row();
                if($usuario)
                {
                    
                }
                echo '<br>';
                echo '<div class="tema">';
                echo '<div class="metadata">';
                if($datos[2]==NULL){
                    echo 'Publicado por: <i>"Usuario eliminado"</i>';
                }
                else{
                    echo '<img src="fotosperfil/'.$datos[2].'_profile.jpg'.'" height="40px" width="40px"> <a href="mostrarperfil.php?nickname='.$datos[2].'">'.$datos[2].'</a> - '.$usuario[4];
                }
                
                if(isset($_SESSION['nickname'])&&($datos[2]==$_SESSION['nickname']))
                {
                    echo ' <a href="eliminarcomentario.php?idhilo='.$idhilo.'&amp;idcoment='.$datos[0].'&amp;confirmado=false">Eliminar</a>';
                }
                echo '<br>Fecha de publicación: '.$datos[3].'</p></div>';
                $acambiar=array('<','>');
                $cambiarpor=array('&lt;','&gt;');
                $comentario=str_replace($acambiar,$cambiarpor,$datos[4]);
                echo '<div class="comentario"><p>'.$comentario.'</p></div>';
                echo '<br>';
                echo '</div>';
                $datos=$resultado->fetch_row();
            }
            return true;
        }
        return false;
        $conexion->close();
    }
    return false;
}

function publicarComentario($idhilo) {
    if(isset($_SESSION['nickname']))
    {
        $conexion=conectarBD(HOST,USUARIO,CONTRASEÑA,BD);
        if($conexion)
        {
            if(isset($_POST['publicarcomentario']))
            {
                $nickname=$_SESSION['nickname'];
                $comentario=$_POST['comentario'];
                $consulta="INSERT INTO comentarios VALUES(NULL,$idhilo,?,current_timestamp(),?)";
                $preparada=$conexion->prepare($consulta);
                $preparada->bind_param('ss',$nickname,$comentario);
                return $preparada->fetch()== true;
            }else{
                return NULL;
            }
            $conexion->close();
        }
    }
    else 
    {
        return false;
    }
    
}

?>