<?php 
session_start();
 $_SESSION['acs']=false;

?>

<!DOCTYPE html>

<html lang='it'>
    <head>
    <meta charset="utf-8" />
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="stile.css">
    <link rel="stylesheet" type="text/css" href="print.css" media="print">
    <link rel='icon' href='./img/favicon.png' type='image/png'>
    <meta name="author" content="Giuseppe Alfonso Pio Palermo">
    
    
    </head>

    <body>
    <header>
    <h1 class="nome"><a href="Home.php">SmartPayment</a></h1>

    <?php 
		$saldoU=$_SESSION['saldo']/100;
		$saldoU=money_format('%.2n', $saldoU);
		$saldoU=number_format($saldoU, 2, ',','');
		

		   if($_SESSION['utente']==null){
			   printf("<p class='UtenteSaldo'>Utente: Anonimo Saldo: 0,00&euro;</p>");  
		   }
		   else{
			   printf("<p class='UtenteSaldo'>Utente: %s Saldo: %s &euro;</p>", $_SESSION['utente'], $saldoU);  
		   }

?>
    </header>

    <nav id="menu">
    <ul>
            <li><a href="Home.php">Home</a></li>

    <?php   
		  if($_SESSION['utente']==null){
             printf('<li><a href="Login.php">Login</a></li>');
          }else{
            printf("<li><a class='disabled' href='Login.php' >Login</a></li>");
           }
    ?>

          <li><a href="Paga.php">Paga</a></li>
          <li><a href="log.php">Log</a></li>
   
    <?php   
		 if($_SESSION['utente']==null){
            printf("<li><a href='Logout.php' class='disabled'>Logout</a></li>");
         }else{
            printf("<li><a href='Logout.php' >Logout</a></li>");
         }	
    ?>

    </ul>
    </nav>

    
    <h2 class='sottotitoli'> Esegui accesso</h2>
    <form class='form_login'  action="EseguiAccesso.php" class="" method="POST" name="MyFrom">
    <h2>Inserisci le credenziali</h2>
    <p>Username: <input type="text" name="username" 
    <?php 
    //se esiste il cookie lo stampo pre-compilato
    if(isset($_COOKIE['utente']))
         printf("value='".$_COOKIE['utente']."' ");
   else
         printf ("placeholder='Username'");
    ?> /></p>
    <p>Password: <input type="password" name="passUser" placeholder='Password' /></p>

    <p><input type="submit" class="button" name="loginUser" value='Accedi'>
       <input type="reset" class="button" value='Pulisci'> </p>
 
    </form>
   




    <footer id="myfooter">
	
   <?php echo "Pagina: <a href='".basename($_SERVER['PHP_SELF'])."'>".basename($_SERVER['PHP_SELF'])."</a>";
   $tags=get_meta_tags(basename($_SERVER['PHP_SELF'])); 
  echo "<br>Autore pagina: ".$tags['author'];?>
   
  </footer>
  </body>
    </html>



