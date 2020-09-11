<?php 
    $connessione_1 = mysqli_connect("localhost", "uReadOnly", "posso_solo_leggere", "pagamenti");
    $connessione_2 = mysqli_connect("localhost", "uReadWrite", "SuperPippo!!!", "pagamenti");
?> 