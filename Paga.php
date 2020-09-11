<?php session_start(); ?>

<!doctype html>
<html lang='it'>
    <head>
    <meta charset="utf-8" />
    <title>Pagamento</title>
    <link rel="stylesheet" type="text/css" href="stile.css">
    <link rel="stylesheet" type="text/css" href="print.css" media="print">
    <link rel='icon' href='./img/favicon.png' type='image/png'>
    <meta name="author" content="Giuseppe Alfonso Pio Palermo">
    
    </head>

    <body>
    <script>
        function controlloInvio(x,y){
            let regExp= /^\d+(\,\d{1,2})?$/;
            
            if(x==="0" || x==="0,0" || x==="0,00"){
                window.alert("Hai inserito un importo nullo!");
                return false;
            }
            if(!regExp.test(x)){
                window.alert("Numero inserito in maniera errata: utlizzare la virgola come separatore con massimo due cifre decimali");
                return false;
            }
            if(y==null || y==''){
                window.alert("Errore- Seleziona CHI PAGARE");
                return false;
            }
            return true;
        }
    </script>

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
  
<h2 class='sottotitoli'>PAGAMENTO</h2>
<form class='form_paga'  action="ConfermaPagamento_2.php" method="POST" onSubmit="return controlloInvio(importo.value, ChiPagare.value)">

<?php
    if($_SESSION['utente'] == null ){
    $connessione_1 = mysqli_connect("localhost", "uReadOnly", "posso_solo_leggere", "pagamenti");

        if (mysqli_connect_errno()) {
				echo "<p>Errore connessione al DBMS: ".mysqli_connect_error()."</p>\n";
        }else{
            $query = "SELECT COUNT(*) as persone FROM usr WHERE negozio<>1 ";
            $result = mysqli_query($connessione_1, $query);

            if(!$result){
                printf('<p>Errore query fallita: ".mysqli_error($connessione_1)."</p>');
            }else{
                while($row = mysqli_fetch_assoc($result)){
                    $persone = $row['persone'];
                }
                mysqli_free_result($result);
            }
            mysqli_close($connessione_1);
           

            $connessione_1 = mysqli_connect("localhost", "uReadOnly", "posso_solo_leggere", "pagamenti");
            $query2= "SELECT COUNT(*) as negozi FROM usr WHERE negozio=1";
            $result2 = mysqli_query($connessione_1, $query2);

            if(!$result2){
                printf('<p>Errore query: ".mysqli_error($connessione_1)."</p>');
            }else{
                while($row2 = mysqli_fetch_assoc($result2)){
                    $negozi = $row2['negozi'];
                }
                mysqli_free_result($result2);

            }
            mysqli_close($connessione_1);
            printf('<p>Sul sito sono registrati '.$negozi.' negozi e '.$persone.' persone</p>');
            echo "<p>Per effetturare un pagamento procede con il <a href='Login.php'>LOGIN</a></p>";
        }
    }else if($_SESSION['utente'] != null && $_SESSION['negozio']==1){
        $connessione_1 = mysqli_connect("localhost", "uReadOnly", "posso_solo_leggere", "pagamenti");
      

        if (mysqli_connect_errno()) {
            echo "<p>Errore connessione al DBMS: ".mysqli_connect_error()."</p>\n";
    }else{
        $query = "SELECT nome,nick,saldo FROM usr";
        $result = mysqli_query($connessione_1, $query);

        if(!$result){
            printf('<p>Errore query: ".mysqli_error($connessione_1)."</p>');
        }else{
            printf("<table><caption>Verso chi eseguo il pagamento:</caption>");
            while($row = mysqli_fetch_assoc($result)){
                if($row['nick']!==$_SESSION['utente']){
                    printf("<tr><th><input type='radio' name='ChiPagare' value='%s'>  <th>%s</th> </th></tr>", 
                    $row['nick'], $row['nome']);
                    }
            }
            printf("</table>");
            mysqli_free_result($result);
            mysqli_close($connessione_1);
            printf("<p>Inserire l'importo da versare:</p><p>(Inserire la virgola per i centesimi)</p><input type='text' name='Importo' id='importo' placeholder='Importo'> <br>
            <button name='pagaButton' type='submit'>PROCEDI</button>");
        }
    }
}else{
    $connessione_1 = mysqli_connect("localhost", "uReadOnly", "posso_solo_leggere", "pagamenti");
    $query= "SELECT nome,nick,saldo FROM usr WHERE negozio=1";
	$result= mysqli_query($connessione_1, $query);

    if(!$result){
        printf('<p>Errore query: ".mysqli_error($connessione_1)."</p>');
    }else{
        printf("<table><caption>Verso chi eseguo il pagamento:</caption>");
        while($row = mysqli_fetch_assoc($result)){
			if($row['nick']!==$_SESSION['utente']){
				printf("<tr><th><input type='radio' name='ChiPagare' value='%s'></th>  <th>%s</th> </tr>", 
			$row['nick'], $row['nome']);
			}
        }
        printf("</table>");
        mysqli_close($connessione_1);
        printf("<p>Inserire l'importo da versare:</p><p>(Inserire la virgola per i centesimi)</p><input type='txt' name='Importo' id='importo' placeholder='Importo'> <br>
            <button name='pagaButton' type='submit'>PROCEDI</button>");
    }
}
    $_SESSION['pagamento']=false;
?>
</form>


<footer id="myfooter">
	
	 <?php echo "Pagina: <a href='".basename($_SERVER['PHP_SELF'])."'>".basename($_SERVER['PHP_SELF'])."</a>";
	 $tags=get_meta_tags(basename($_SERVER['PHP_SELF'])); 
    echo "<br>Autore pagina: ".$tags['author'];?>
	 
    </footer>
    </body>
</html>
