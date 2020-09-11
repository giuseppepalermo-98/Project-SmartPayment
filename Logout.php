<?php session_start();?>
<?php 
		$_SESSION = array(); //L'array con tutte le sessioni lo metto a ZERO
		session_destroy();
		header('Location: Home.php');
		
?>