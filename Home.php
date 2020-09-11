<?php session_start() ?>
<!DOCTYPE html>

<html lang='it'>
    <head>
    <meta charset="utf-8" />
    <title>Home</title>
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
    <?php   
    
		  if($_SESSION['utente']==null){
             printf('<li class="item"><a href="Login.php">Login</a></li>');
         }else{
          printf("<li class='item'><a class='disabled' href='Login.php' >Login</a></li>");
         }
    ?>

          <li class='item'><a href="Paga.php">Paga</a></li>
          <li class='item'><a href="log.php">Log</a></li>
   
    <?php   
		 if($_SESSION['utente']==null){
            printf("<li class='item'><a href='Logout.php' class='disabled'>Logout</a></li>");
         }else{
            printf("<li class='item'><a href='Logout.php' >Logout</a></li>");
         }	
    ?> 
    </ul>
    </nav>
  
    <h2 class='sottotitoli'>Il nostro ruolo</h2>
    <main id="contenuto">
   
    <p class='presentazione'>Pagare non è mai stato così semplice, sicuro e veloce... Affidati a noi!</p>
    <div class='cornice'><img class="foto" src="./img/presentazione.jpg" alt="Immagine di presentazione!"></div>
    <p class="citazione">Crediamo che il progresso possa semplificarci la vita!</p>
    </main>

    <footer id="myfooter">
	
    <?php echo "Pagina: <a href='".basename($_SERVER['PHP_SELF'])."'>".basename($_SERVER['PHP_SELF'])."</a>";
    $tags=get_meta_tags(basename($_SERVER['PHP_SELF'])); 
   echo "<br>Autore pagina: ".$tags['author'];?>
    
   </footer>

    </body>
</html>