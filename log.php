<?php session_start(); ?>
<!DOCTYPE html>

<html lang='it'>
    <head>
    <meta charset="utf-8" />
    <title>Log</title>
    <link rel="stylesheet" type="text/css" href="stile.css">
    <link rel="stylesheet" type="text/css" href="print.css" media="print">
    <link rel='icon' href='./img/favicon.png' type='image/png'>
    <meta name="author" content="Giuseppe Alfonso Pio Palermo">
   
    <script>
        function verificaCampi(){
            if(document.f.posizione !='' && document.f.data != '')
            return true;

            return false;
        }
        </script>
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
    <?php 
		   if($_SESSION['utente']==null){
			  
                //Faccio apparire la scritta di effetturare il login         
                printf("<div class='attenzione'><p class='attection'>Attenzione! L’elenco dei pagamenti &egrave; disponibile solo per gli utenti autenticati.</p>
                        <p>Se desideri effetturare il login <a href='Login.php'>Clicca qui</a></div>");

		   }
		   else{
                 ?>
                 <h2 class='sottotitoli'>Cerca pagamenti</h2>
                 <form class='form_log' name='f' action="pagamenti.php" method="POST">

                   
                    <h2>Scegli le opzioni di ricerca:</h2>
                    <p>Seleziona il tipo di pagamento</p>
                    <ul>
                        <li><input type='radio' name='posizione' value='ricevuti'>Pagamenti ricevuti</li>
                        <li><input type="radio" name="posizione" value='eseguiti'>Pagamenti eseguiti</li>
                        <li><input type='radio' name='posizione' value='tutti' checked>Tutti i tipi di movimenti</li>
                    </ul>
                    <p>Seleziona quali mesi</p>
                    <ul>
                        <li><input type='radio' name='data' value='corrente' checked>Mese corrente</li>
                        <li><input type='radio' name='data' value='trePrecedenti'>Ultimi 3 mesi</li>
                    </ul>

                    <input type='submit' value='Cerca' onClick='verificaCampi()'>
                 </form>
        <?php 
        }
    ?>


<footer id="myfooter">
	
    <?php echo "Pagina: <a href='".basename($_SERVER['PHP_SELF'])."'>".basename($_SERVER['PHP_SELF'])."</a>";
    $tags=get_meta_tags(basename($_SERVER['PHP_SELF'])); 
   echo "<br>Autore pagina: ".$tags['author'];?>
    
   </footer>

</body>
</html>