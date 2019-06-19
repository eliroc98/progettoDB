<?php
/* creo connessione al DB*/
function connect_DB() {
$con = pg_connect("host=localhost port=5432 dbname=progetto user=postgres password=unimi");
/* in laboratorio usare
   $con = pg_connect("host=localhost port=5432 dbname=progetto user=postgres password=postgres"); 
   verificando il nome del DB */
if (!$con) {
	echo "Errore nella connessione al database: " . pg_last_error($con);
    exit;
}
return $con;
}
?>