<?php session_start();
$ricarica=false;
if($_SESSION['pagamento']==true){
    $_SESSION['pagamento']=false;
    $ricarica=true;
	header('Location: Home.php');
}
?>

<!DOCTYPE html>

<html lang='it'>
    <head>
    <meta charset="utf-8" />
    <title>Conferma Pagamento</title>
    <link rel="stylesheet" type="text/css" href="stile.css">
    <link rel="stylesheet" type="text/css" href="print.css" media="print">
    <link rel='icon' href='./img/favicon.png' type='image/png'>
    <meta name="author" content="Giuseppe Alfonso Pio Palermo">
  

    <script>
        function controlloImporto(x){
            let regExp= /^\d+(\,\d{1,2})?$/;
            
            if(x==="0" || x==="0,0" || x==="0,00"){
                window.alert("Hai inserito un importo nullo!");
                return false;
            }
            if(!regExp.test(x)){
                window.alert("Numero inserito in maniera errata: utlizzare la virgola come separatore con massimo due cifre decimali");
                return false;
            }
            return true;
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
 
    <?php
     //Fino a qui era solo l'impostazione della pagina!
        if(isset($_REQUEST['Importo']) == true && $ricarica==false){
            //echo "procedo!";

        //sistemo il formato dell'importo inserito per essere coerente
        $importo=(float)strtr($_REQUEST['Importo'], ',', '.');
        
        $importo=$importo*100;
        //mi prendo le variabili che mi serviranno
        $incasso = $_REQUEST['ChiPagare']; //questo è il nick che mi servirà per poi inserire nella tabella incasso
        $utente = $_SESSION['utente'];
        $saldoDiPagante = $_SESSION['saldo'];
        $nomePagante = $_SESSION['ChiPaga'];


        //Aggiorno le tabelle con le query che seguono 
        if($saldoDiPagante > $importo){
            //echo "SONO DENTRO";

            //apro connessione in scrittura
            $connessione_2 = mysqli_connect("localhost", "uReadWrite", "SuperPippo!!!", "pagamenti");
            if (mysqli_connect_errno()) {
				echo "<p>Errore connessione al DBMS: ".mysqli_connect_error()."</p>\n";
            }else{
            //per prima cosa vado a sottrarre il saldo al pagante
            $query="UPDATE usr SET saldo= saldo-'".$importo."' WHERE nick='".$utente."' ";
            $result = mysqli_query($connessione_2, $query);

            if(!$result){
                printf('<p>Errore query fallita: ".mysqli_error($connessione_2)." la prima query</p>');
            }
        }
        mysqli_close($connessione_2);

        $connessione_2 = mysqli_connect("localhost", "uReadWrite", "SuperPippo!!!", "pagamenti");
        if (mysqli_connect_errno()) {
            echo "<p>Errore connessione al DBMS: ".mysqli_connect_error()."</p>\n";
        }else{

            //Aggiorno il saldo invece di chi riceve i soldi 
           //  $query2= "UPDATE usr SET saldo= saldo+'".$importo."' WHERE nome='".$incasso."' ";
            $query2= "UPDATE usr SET saldo= saldo+'".$importo."' WHERE nick='".$incasso."' ";
            $result2 = mysqli_query($connessione_2, $query2);

            if(!$result2){
                printf('<p>Errore query fallita: ".mysqli_error($connessione_2)." la seconda query</p>');
            }
        }
        mysqli_close($connessione_2);

            $connessione_1 = mysqli_connect("localhost", "uReadOnly", "posso_solo_leggere", "pagamenti");
            if (mysqli_connect_errno()) {
                echo "<p>Errore connessione al DBMS: ".mysqli_connect_error()."</p>\n";
            }
            else{
            //prendo il nuovo saldo del pagante e aggiorno la sessione
            $stmtc= mysqli_prepare($connessione_1, "SELECT saldo FROM usr WHERE nick = ? ");
	
	        mysqli_stmt_bind_param($stmtc, 's', $utente);
	
	        mysqli_stmt_execute($stmtc);
	        mysqli_stmt_bind_result($stmtc, $saldo);
	        mysqli_stmt_fetch($stmtc);
	
            $_SESSION['saldo']= $saldo;
        }     
        mysqli_close($connessione_1);
        
        $eseguito=true;   
        }   
    else{
        $eseguito=false;
    }

    if($eseguito==true){

        //DEVO ANDARE AD AGGIUNGERE IL PAGAMENTO ALLA TABELLA E POI STAMPARE 'RICEVUTA'
        $connessione_1 = mysqli_connect("localhost", "uReadOnly", "posso_solo_leggere", "pagamenti");
        if (mysqli_connect_errno()) {
            echo "<p>Errore connessione al DBMS: ".mysqli_connect_error()."</p>\n";
        }
        else{
            //DEVO RICAVARMI L'ID PIU' GARNDE DELLA TABELLA AGGIORNARLO +1
            $query3 = "SELECT max(id) as id FROM log ORDER BY data DESC";
            $result3= mysqli_query($connessione_1, $query3);

            if(!$result3){
		
                printf("<p> Errore-query fallita: %s<p>\n", mysqli_error($connessione_1));
            }else{
                while($row3 = mysqli_fetch_assoc($result3)){
                    $id=$row3['id'];
                }
              }
        
            $id=$id+1; //AGGIORNAMENTO
        }
        mysqli_close($connessione_1);
        //SE TUTTO VA A BUON FINE AGGIORNO LA TABELLA LOG CON CONNESSIONE IN SCRITTURA
        $connessione_2 = mysqli_connect("localhost", "uReadWrite", "SuperPippo!!!", "pagamenti");
        if (mysqli_connect_errno()) {
            echo "<p>Errore connessione al DBMS: ".mysqli_connect_error()."</p>\n";
        }else{

            //Prendo la data del server 
            $data=time();
	        $data=date('Y-m-d H:i:s', $data);

            $query4= "INSERT INTO log ( `id` , `src` , `dst` , `importo` , `data` ) VALUES ( '".$id."' , 
	        '".$utente."' , '".$incasso."' , '".$importo."' , '".$data."') ";
	        $result4= mysqli_query($connessione_2, $query4);
	
	        if(!$result4){
		
		        printf("<p> Errore-query fallita: %s<p>\n", mysqli_error($connessione_2));
	        }
        }

        //Sistemo l'importo da stampare a video nella 'RICEVUTA'
        $importo = number_format($importo/100, 2, ',', '');

        printf( "<h2 class='sottotitoli'>PAGAMENTO ESEGUITO CORRETTAMENTE</h2>
        <div class='ricevuta'>
        <p> ESEGUITO DA: %s</p>
        <p>	DESTINATARIO: %s </p>
        <p>	IMPORTO: %s &euro; </p>
        <p>	DATA E ORA DEL PAGAMENTO: %s</p></div>", $utente, $incasso, $importo, $data);

        //adesso aggiorno la sessione del pagamento cosi se aggiorna la pagina me ne vado nella home 
        $_SESSION['pagamento']=true;
    }else{
        printf("<h2 class='sottotitoli'>PAGAMENTO NON ESEGUITO</h2>
        <div class='ricevuta'><p>Importo insufficiente... Esegui un altro tentativo se vuoi procedere con il pagamento!</p></div>");
    }
}else{
    printf("<h2 class='sottotitoli'>ERRORE - pagina non eseguibile!</h2>");
}

?>

<footer id="myfooter">
	
    <?php echo "Pagina: <a href='".basename($_SERVER['PHP_SELF'])."'>".basename($_SERVER['PHP_SELF'])."</a>";
    $tags=get_meta_tags(basename($_SERVER['PHP_SELF'])); 
   echo "<br>Autore pagina: ".$tags['author'];?>
    
   </footer>
   </body>
</html>