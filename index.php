<?php
//require_once "funciones.php";
require_once "classes/validator.php";
require_once "ini.php";
//require_once "classes/usuario.php";
//require_once "classes/dbmysql.php";

//$dbMysql = new DbMysql;

$errores=[];
$lastNameOk="";
$nameOk="";
$emailOk="";
$usuarioExistente="";
$errorLogin=false;
$logout= "logout";
$login="login";
$redBackground = "background-color:rgba(255,0,0,0.2); border-radius:10px";

//var_dump($_POST);
//echo "<br>";
if ($auth->usuarioLogueado()){
  $usuario=$dbMysql->traerUsuarioLogueado();

  //var_dump($usuario);
  //exit;
  
  $lastNameOk=$usuario->getLastName();
  $nameOk=$usuario->getName();
  $emailOk=$usuario->getEmail();
}

if ($_POST) {
  if (!empty($_POST["register"])) {
    
    $errores = Validator::validarRegistro($_POST);
    //var_dump($errores);
    //exit;
    $nameOk = trim($_POST["name"]);
    $lastNameOk = trim($_POST["lastName"]);
    $emailOk = trim($_POST["email"]);
      //var_dump($nameOk);
    if (empty($errores)){

      //var_dump($_POST["email"]);
      //echo "<br>";
      $quepaso =$dbMysql->existeElusuario($_POST["email"]);
      //var_dump($quepaso);
      //exit;

      if($dbMysql->existeElUsuario($_POST["email"])==NULL){

        $usuario= new Usuario($_POST); //armarUsuario($_POST);
        //var_dump($usuario);
        //exit;
        
        $guardarUsuario=$dbMysql->guardarUsuario($usuario);
        //var_dump($guardarUsuario);
        //exit; 

        //logueo al usuario
        //$usuario= buscarUsuario($_POST["email"]); esta linea no se porque esta acá

        $auth->loguearUsuario($usuario);
        //var_dump($usuario);
        //redirijo
        header("Location:exito.php");
        exit;
        }else{
          $usuarioExistente = "El usuario ya se encuentra registrado.";
        }

    }
  }
  if (!empty($_POST['login'])) {
    
    $errores = Validator::validarLogin($_POST);
    //var_dump($errores);
    //echo "<br>";
    //exit;

    if (empty($errores)){
      $usuario= $dbMysql->buscarUsuario($_POST["email"]);
      
      //var_dump($_POST);
      //var_dump($usuario);
      //exit;
      
      if ($usuario==NULL){
        $errorLogin = "El mail no se encuentra registrado. Por favor, regístrese haciendo <a href='#section-register'>click acá</a>.";
      }
      //logeo al usuario
      $auth->loguearUsuario($usuario);
      //$auth->setcookie($usuario);

      //seteo de cookies

      if (isset($_POST["remember"])){
        $auth->setCookies($usuario);
      }

      //redirijo

      header("Location:exito.php");
      exit;
    }
  }
}
?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>jirafa BrewHouse</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" media="screen" href="styles.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
  <link rel="icon" type="image/png" href="IMG/iconbeer.ico" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <header class="nav-header">
        <input type="checkbox" id="abrir-cerrar" name="abrir-cerrar" value="">
        <label for="abrir-cerrar"><a href="#home" class="btn-home">Login</a><span class="abrir">&#9776;</span><span class="cerrar">&#9776; Cerrar</span></label>
        <div id="sidebar" class="sidebar">
            <ul class="menu">
              <li><a href="index.php">home</a></li>
              <li><a href="index.php#section-nosotros">nosotros</a></li>
              <li><a href="index.php#section-estilos">estilos</a></li>
              <li><a href="contact.php">contacto</a></li>
              <?php if ($auth->usuarioLogueado()):?>
                <li><a href="exito.php">perfil</a></li>
                <li><a href="logout.php"><?= $logout?></a>
                <span class="welcome" style="padding: 14.5px 16px; float: right; color: #f90" >Bienvenide, <?= $nameOk?> !</span></li>
              <?php else:?>
                  <li><a href="#section-forms"><?= $login?></a></li>
              <?php endif?>
            </ul>
        </div>
    </header>
    <main>
      <div id="contenido">
        <section class="landing" id="home">
            <div class="bloque-home">
                <video class="background-video" poster="http://adnhd.com/wp-content/uploads/2018/10/0029462316.jpg" src="IMG/Loop-Background.mp4" autoplay loop muted></video>
                <div class="landing">
                    <img class="logo-landing-img" src="IMG\jirafa-brew-house-logo.png" alt="jirafa-logo">
                </div>
            </div>
        </section>
       <section id="section-nosotros">
            <div class="nosotros">
                <p class="paragraph-us"><h1 class="title-princ">Nosotros</h1>¡Hablemos de cervezas! Somos una cervecería que hace <i>cerveza de garage</i>, ¿Qué significa esto? Somos un emprendimiento de dos amigos que les gusta el mundo de la cerveza, tenemos nuestra fábrica en nuestro garage.. y muchas ganas de aprender. Las recetas de todas nuestras birras se encuentran en linea. ¿Estas comenzando y tenes dudas? <a style="color:#ffbb37" href="#section-contact">No dudes en contactarnos</a></p>
                <p class="dektop-us">Una vez al mes hacemos una visita guiada por la fábrica acompañada de una pequeña cocción de unos 20 litros, allí compartimos nuestros conocimientos, aprendemos de ustedes, y les contamos nuestra experiencia.</p>
            </div>
      </section> 

        <!-- categorias o estilos de cerveza -->
        <section class="section-estilos" id="section-estilos">
          <h1 class="title-princ">ESTILOS</h1>
            <article class="estilo">
              <div class="photo-container">
                  <img class="photo" src="IMG/estilo-rubia.jpg" alt="estilo 01">
              </div>
              <div class="title">
                  <h1>Rubia</h1>
                  <p class="title">IPA's o Blonde, muy suaves o muy power.</p>
              </div>
            </article>
            <article class="estilo">
              <div class="photo-container">
                <img class="photo" src="IMG/estilo-negra.jpg" alt="estilo 02">
              </div>
              <div class="title">
                  <h1>Negra</h1>
                  <p class="title">Stout, porter, mucho aroma y sabor.</p>
              </div>
            </article>
            <article class="estilo">
              <div class="photo-container">
                <img class="photo" src="IMG/estilo-roja.jpg" alt="estilo 03">
              </div>
              <div class="title">
                  <h1>Roja</h1>
                  <p class="title">Cervezas maltosas, agradables al paladar</p>
              </div>
            </article>
            <article class="estilo">
              <div class="photo-container">
                <img class="photo" src="IMG/estilo-reserva.jpg" alt="estilo 04">
              </div>
              <div class="title">
                  <h1>Reserva</h1>
                  <p class="title">Cervezas doradas reserva en barriles de whisky.</p>
              </div>
            </article>
        </section>

        <?php if (!$auth->usuarioLogueado()):?>
          <section id="section-contact">
            <div id="section-forms">
              <div class="formulario">
                <h1>LOGIN</h1>
                <form action="#section-forms" method="post" class="tarjets ">
                  <div class="form-group">
                    <span class="errores"><?= (isset($errorLogin)) ? $errorLogin : "" ?></span>

                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" value="<?= (isset($errores["email"]) && (!empty($_POST["login"]))) ? "" : $emailOk ?>" style="<?= (isset($errores["email"]) && (!empty($_POST["login"]))) ? $redBackground : "" ?>" name="email" aria-describedby="emailHelp">
                    <span class="errores"><?= (isset($errores["email"]) && (!empty($_POST["login"]))) ? $errores["email"] : "" ?></span>
                  </div>

                  <div class="form-group">
                    <label for="pass">Password</label>
                    <input type="password" class="form-control" id="pass" value="<?= (isset($errores["pass"]) && (!empty($_POST["login"]))) ? "" : $emailOk ?>" style="<?= (isset($errores["pass"]) && (!empty($_POST["login"]))) ? $redBackground : "" ?>" name="pass" aria-describedby="forgotPass">
                    <span class="errores"><?= (isset($errores["pass"]) && (!empty($_POST["login"]))) ? $errores["pass"] : "" ?></span>
                    <p><a class="forgot-pass" href="#">Olvidé mi contraseña</a></p>
                  </div>

                  <button type="submit" class="btn-standard" value="login" name="login">Ingresar</button>
                  <div class="form-check">
                    <input type="checkbox" value="remember" class="form-check-input" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Recordarme</label>
                  </div>

                </form>
                  <h1 id="section-register">REGISTRATE</h1>
                  <h3>¿No tenes cuenta? Completá tus datos</h3>
              <form action="#section-register" method="POST" class="tarjets">
                <?php if (isset($_POST["email"]) && $dbMysql->existeElUsuario($_POST["email"])):?>
                  <span class="errores"><?= $usuarioExistente ?></span>
                <?php endif ?>

                    <div class="form-group">
                      <label for="name">Nombre</label>
                      <input type="text" class="form-control" id="name" name="name" value="<?= isset($errores["name"]) ? "" : $nameOk ?>" style="<?= (isset($errores["name"])) ? $redBackground : "" ?>"> 
                      <span class="errores"><?= isset($errores["name"]) ? $errores["name"] : "" ?></span>
                    </div>

                    <div class="form-group">
                        <label for="lastName">Apellido</label>
                        <input type="text" class="form-control" id="lastName" name="lastName" value="<?= isset($errores["lastName"]) ? "" : $lastNameOk ?>" style="<?= isset($errores["lastName"]) ? $redBackground : "" ?>">
                        <span class="errores"><?= isset($errores["lastName"]) ? $errores["lastName"] : "" ?></span>
                    </div>

                    <div class="form-group">
                    <label for="gender">Género:</label><br>
                    <?php if(isset($errores["gender"]) && $_POST["gender"]=="fem"):?>
                      <input type="radio" name="gender" value="fem" checked>Femenino
                    <?php else:?>
                      <input type="radio" name="gender" value="fem">Femenino
                    <?php endif?>
                    <?php if (isset($_POST["gender"]) && $_POST["gender"] == "masc"): ?>
                      <input type="radio" name="gender" value="masc" checked>Masculino
                    <?php else:?>
                      <input type="radio" name="gender" value="masc">Masculino
                    <?php endif?>
                    <?php if (isset($_POST["gender"]) && $_POST["gender"] == "other"): ?>
                      <input type="radio" name="gender" value="other" ckecked>Prefiero no decirlo
                    <?php else:?>
                      <input type="radio" name="gender" value="other">Prefiero no decirlo
                    <?php endif?>
                    <?php if(isset($errores["gender"])):?>
                      <span class="errores"><?= $errores["gender"] ?></span>
                  <?php endif?>
                    </div>

                    <div class="form-group">
                      <label for="email">Email</label>
                      <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" value="<?= (isset($errores["email"]) && (!empty($_POST["register"]))) ? "" : $emailOk ?>" style="<?= (isset($errores["email"]) && (!empty($_POST["register"]))) ? $redBackground : "" ?>">
                      <span class="errores"><?= (isset($errores["email"]) && (!empty($_POST["register"]))) ? $errores["email"] : "" ?></span>
                    </div>

                    <div class="form-group">
                      <label for="pass">Contraseña</label>
                      <input type="password" class="form-control" id="pass" name="pass" maxlength="20" style="<?= (isset($errores["pass"]) && (!empty($_POST["register"]))) ? $redBackground : "" ?>" tabindex="17" autocapitalize="none" spellcheck="false" autocorrect="off" autocomplete="off" data-uid="5">
                      <span class="errores"><?= (isset($errores["pass"]) && (!empty($_POST["register"]))) ? $errores["pass"] : "" ?></span>
                    </div>
                    <div class="form-group">
                      <label for="pass2">Repetí la contraseña</label>
                      <input type="password" class="form-control" id="pass2" name="pass2" maxlength="20" style="<?= (isset($errores["pass"]) && (!empty($_POST["register"]))) ? $redBackground : "" ?>" tabindex="17" autocapitalize="none" spellcheck="false" autocorrect="off" autocomplete="off" data-uid="5">
                      <span class="errores"><?= (isset($errores["pass"]) && (!empty($_POST["register"]))) ? $errores["pass"] : "" ?></span>
                    </div>

                    <div class="form-group form-adult">
                        <input type="checkbox" name="adult" class="form-check-adult" id="adult" value="adult">
                        <label class="form-check-label" for="adult">Soy mayor de 18 años</label>
                        <p class="term-conditions">Al registrarme, declaro que soy mayor de edad y acepto los Terminos y condiciones y las Políticas de privacidad.</p>
                        <span class="errores"><?= (isset($errores["adult"])) ? $errores["adult"] : "" ?></span>
                    </div>
                    <button type="submit" name="register" value="register" class="btn-standard">Registrarme</button>
                  </form>
              </div>
            </div>
          </section>
        <?php endif?>
        </div>
      </main>
      <footer class="footer">
        <div class="iconos">
          <a href=""><i class="fab fa-facebook-f"></i></a>
          <a href=""><i class="fab fa-instagram"></i></a>
          <a href=""><i class="fab fa-twitter"></i></a>
        </div>
        <p class="nota">Beber con moderación. Prohibida su venta a menores de 18 años.</p>
        <h5 class="copy-footer">Jirafa BrewHouse ® Todos los derechos reservados</h5>


      </footer>
  </body>
</html>
