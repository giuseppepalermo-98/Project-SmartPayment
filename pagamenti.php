<?php session_start(); 
?>
<!doctype html>
<html lang='it'>
    <head>
    <meta charset="utf-8" />
    <title>I tuoi movimenti</title>
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
    <?php 
		
           if(isset($_REQUEST['posizione']) && isset($_REQUEST['data'])){

            $posizione = $_REQUEST['posizione'];
            $data = $_REQUEST['data'];
            $nomePagante = $_SESSION['ChiPaga'];
            
            printf("<table class='lista_pagamenti'>
                    <caption>Lista Movimenti</caption>
                    <tr><th>Emettitore</th><th>Destinatario</th><th>Importo</th><th>Data</th></tr>");

            if($posizione == 'ricevuti' && $data == 'corrente'){

                $connessione_1 = mysqli_connect("localhost", "uReadOnly", "posso_solo_leggere", "pagamenti");
                if (mysqli_connect_errno()) {
                    echo "<p>Errore connessione al DBMS: ".mysqli_connect_error()."</p>\n";
            }else{
                $annoCorrente=date('Y');
                $meseCorrente=date('m');
                $query1="SELECT src, dst, importo, data FROM log WHERE dst='".$_SESSION['utente']."' AND YEAR(data)='".$annoCorrente."' AND MONTH(data)='".$meseCorrente."' ";
                $result1=mysqli_query($connessione_1, $query1);


                if(!$result1){
		
                    printf("<p> Errore-query fallita: %s<p>\n", mysqli_error($connessione_1));
                }else{
                    while( $row1 = mysqli_fetch_assoc($result1)){
                            $src=$row1['src'];
                            $dst=$row1['dst'];
                            $importo=$row1['importo'];
                            $dataDB=$row1['data'];


                            $importo=$importo/100;
                            $importo=money_format('%.2n', $importo);
                            $importo=number_format($importo, 2, ',','');
                            
                            printf("<tr><th>".$src."</th>
                                        <th>".$dst."</th>
                                        <th>".$importo."</th>
                                        <th>".$dataDB."</th></tr>");
                    }
                    mysqli_close($connessione_1);
                    printf("</table>");
                }
            }
            }else if($posizione == 'ricevuti' && $data == 'trePrecedenti'){

                $connessione_1 = mysqli_connect("localhost", "uReadOnly", "posso_solo_leggere", "pagamenti");
                if (mysqli_connect_errno()) {
                    echo "<p>Errore connessione al DBMS: ".mysqli_connect_error()."</p>\n";
            }else{
                $annoCorrente=date('Y');
                $meseCorrente=date('m');
                $mesePossibili=$meseCorrente-2;
                $query1="SELECT src, dst, importo, data FROM log WHERE dst='".$_SESSION['utente']."' AND YEAR(data)='".$annoCorrente."' AND MONTH(data)<='".$meseCorrente."' AND MONTH(data)>='".$mesePossibili."' ";
                $result1=mysqli_query($connessione_1, $query1);

                if(!$result1){
		
                    printf("<p> Errore-query fallita: %s<p>\n", mysqli_error($connessione_1));
                }else{
                    while( $row1 = mysqli_fetch_assoc($result1)){
                            $src=$row1['src'];
                            $dst=$row1['dst'];
                            $importo=$row1['importo'];
                            $dataDB=$row1['data'];


                            $importo=$importo/100;
                            $importo=money_format('%.2n', $importo);
                            $importo=number_format($importo, 2, ',','');

                            printf("<tr><th>".$src."</th>
                                        <th>".$dst."</th>
                                        <th>".$importo."</th>
                                        <th>".$dataDB."</th></tr>");
                            
            }
            mysqli_close($connessione_1);
            printf("</table>");
                }
              }
            }else if($posizione == 'eseguiti' && $data == 'corrente'){

                $connessione_1 = mysqli_connect("localhost", "uReadOnly", "posso_solo_leggere", "pagamenti");
                if (mysqli_connect_errno()) {
                    echo "<p>Errore connessione al DBMS: ".mysqli_connect_error()."</p>\n";
            }else{
                $annoCorrente=date('Y');
                $meseCorrente=date('m');
                $query1="SELECT src, dst, importo, data FROM log WHERE src='".$_SESSION['utente']."' AND YEAR(data)='".$annoCorrente."' AND MONTH(data)='".$meseCorrente."' ";
                $result1=mysqli_query($connessione_1, $query1);


                if(!$result1){
		
                    printf("<p> Errore-query fallita: %s<p>\n", mysqli_error($connessione_1));
                }else{
                    while( $row1 = mysqli_fetch_assoc($result1)){
                            $src=$row1['src'];
                            $dst=$row1['dst'];
                            $importo=$row1['importo'];
                            $dataDB=$row1['data'];


                            $importo=$importo/100;
                            $importo=money_format('%.2n', $importo);
                            $importo=number_format($importo, 2, ',','');

                            printf("<tr><th>".$src."</th>
                                        <th>".$dst."</th>
                                        <th>".$importo."</th>
                                        <th>".$dataDB."</th></tr>");
                            
            }
            mysqli_close($connessione_1);
            printf("</table>");
                }
            }
        }else if($posizione == 'eseguiti' && $data == 'trePrecedenti'){

            $connessione_1 = mysqli_connect("localhost", "uReadOnly", "posso_solo_leggere", "pagamenti");
            if (mysqli_connect_errno()) {
                echo "<p>Errore connessione al DBMS: ".mysqli_connect_error()."</p>\n";
        }else{
            $annoCorrente=date('Y');
            $meseCorrente=date('m');
            $mesePossibili=$meseCorrente-2;
            $query1="SELECT src, dst, importo, data FROM log WHERE src='".$_SESSION['utente']."' AND YEAR(data)='".$annoCorrente."' AND MONTH(data)<='".$meseCorrente."' AND MONTH(data)>='".$mesePossibili."' ";
            $result1=mysqli_query($connessione_1, $query1);

            if(!$result1){
    
                printf("<p> Errore-query fallita: %s<p>\n", mysqli_error($connessione_1));
            }else{
                while( $row1 = mysqli_fetch_assoc($result1)){
                        $src=$row1['src'];
                        $dst=$row1['dst'];
                        $importo=$row1['importo'];
                        $dataDB=$row1['data'];


                        $importo=$importo/100;
                        $importo=money_format('%.2n', $importo);
                        $importo=number_format($importo, 2, ',','');

                        printf("<tr><th>".$src."</th>
                                    <th>".$dst."</th>
                                    <th>".$importo."</th>
                                    <th>".$dataDB."</th></tr>");
                        
        }
        mysqli_close($connessione_1);
        printf("</table>");
            }
          }
        }else if($posizione == 'tutti' && $data == 'corrente'){

            $connessione_1 = mysqli_connect("localhost", "uReadOnly", "posso_solo_leggere", "pagamenti");
                if (mysqli_connect_errno()) {
                    echo "<p>Errore connessione al DBMS: ".mysqli_connect_error()."</p>\n";
            }else{
                $annoCorrente=date('Y');
                $meseCorrente=date('m');
                $query1="SELECT src, dst, importo, data FROM log WHERE (dst='".$_SESSION['utente']."' OR src='".$_SESSION['utente']."') AND YEAR(data)='".$annoCorrente."' AND MONTH(data)='".$meseCorrente."' ";
                $result1=mysqli_query($connessione_1, $query1);


                if(!$result1){
		
                    printf("<p> Errore-query fallita: %s<p>\n", mysqli_error($connessione_1));
                }else{
                    while( $row1 = mysqli_fetch_assoc($result1)){
                            $src=$row1['src'];
                            $dst=$row1['dst'];
                            $importo=$row1['importo'];
                            $dataDB=$row1['data'];


                            $importo=$importo/100;
                            $importo=money_format('%.2n', $importo);
                            $importo=number_format($importo, 2, ',','');
                            
                            printf("<tr><th>".$src."</th>
                                        <th>".$dst."</th>
                                        <th>".$importo."</th>
                                        <th>".$dataDB."</th></tr>");
                    }
                    mysqli_close($connessione_1);
                    printf("</table>");
                }
            }
        }else if($posizione == 'tutti' && $data == 'trePrecedenti'){

            $connessione_1 = mysqli_connect("localhost", "uReadOnly", "posso_solo_leggere", "pagamenti");
            if (mysqli_connect_errno()) {
                echo "<p>Errore connessione al DBMS: ".mysqli_connect_error()."</p>\n";
        }else{
            $annoCorrente=date('Y');
            $meseCorrente=date('m');
            $mesePossibili=$meseCorrente-2;
            $query1="SELECT src, dst, importo, data FROM log WHERE (src='".$_SESSION['utente']."' OR dst='".$_SESSION['utente']."') AND YEAR(data)='".$annoCorrente."' AND MONTH(data)<='".$meseCorrente."' AND MONTH(data)>='".$mesePossibili."' ";
            $result1=mysqli_query($connessione_1, $query1);

            if(!$result1){
    
                printf("<p> Errore-query fallita: %s<p>\n", mysqli_error($connessione_1));
            }else{
                while( $row1 = mysqli_fetch_assoc($result1)){
                        $src=$row1['src'];
                        $dst=$row1['dst'];
                        $importo=$row1['importo'];
                        $dataDB=$row1['data'];


                        $importo=$importo/100;
                        $importo=money_format('%.2n', $importo);
                        $importo=number_format($importo, 2, ',','');

                        printf("<tr><th>".$src."</th>
                                    <th>".$dst."</th>
                                    <th>".$importo."</th>
                                    <th>".$dataDB."</th></tr>");
                        
        }
        mysqli_close($connessione_1);
        printf("</table>");
            }
          }
        }else{
            printf("<tr><td colspan='4'>Non sono state trovati movimenti in base la ricerca</td></tr></table>");
        }
    }
            else{
               printf("<h1>ERRORE - dati mancanti!</h1>");
           }
?>

    <footer id="myfooter">
	
    <?php echo "Pagina: <a href='".basename($_SERVER['PHP_SELF'])."'>".basename($_SERVER['PHP_SELF'])."</a>";
    $tags=get_meta_tags(basename($_SERVER['PHP_SELF'])); 
   echo "<br>Autore pagina: ".$tags['author'];?>
    
   </footer>
    </body>
    </html>