<?php 
session_start();

if($_SESSION['acs']==true){
	$_SESSION['acs']=false;
	header('Location: Login.php');
}

    if( isset($_REQUEST['username']) && $_REQUEST['username'] != ''){


        $username = trim($_REQUEST['username']);
        $password = $_REQUEST['passUser'];

        $connessione_1 = mysqli_connect("localhost", "uReadOnly", "posso_solo_leggere", "pagamenti");
        if (mysqli_connect_errno()) {
				echo "<p>Errore connessione al DBMS: ".mysqli_connect_error()."</p>\n";
        }
        else{
        
        $query = "SELECT * FROM usr WHERE nick = ? AND pwd = ? ";

        $stmt= mysqli_prepare( $connessione_1, $query);

        mysqli_stmt_bind_param($stmt, "ss", $username, $password);

        $result = mysqli_stmt_execute($stmt);

	    mysqli_stmt_bind_result($stmt, $nickname, $psw, $nomePagante, $saldo, $negozio );
        mysqli_stmt_fetch($stmt);
        

        if($nickname === $username && $psw === $password){

            $scadenza = time()+24*5*3600;
            setcookie('utente', $nickname, $scadenza);
            $_SESSION['utente']= $nickname;
            $_SESSION['ChiPaga'] = $nomePagante;
            $_SESSION['saldo'] = $saldo;
            $_SESSION['negozio'] = $negozio;
            
              echo "<script>location.href='Paga.php'</script>";
        }
        else{
          echo'<script>alert("Errore - Username o password inseriti non sono validi!")</script>';
          echo'<script>location.href="Login.php"</script>';
          $_SESSION['acs'] = true;
        }
        }
        mysqli_stmt_close($stmt);
    }else{
      echo'<script>alert("Errore - inserire un username!")</script>';
      echo'<script>location.href="Login.php"</script>';
      $_SESSION['acs'] = true;

    }

      ?>
