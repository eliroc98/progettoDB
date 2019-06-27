<?php
require_once("../comuni/utility.php");
session_start();

$username = isset($_POST["nomeutente"])?$_POST["nomeutente"]:NULL;
$pwd = isset($_POST["pwd"])?$_POST["pwd"]:NULL;
$con = connect_DB();
$query = "SELECT cf, nome, cognome FROM Persona as P, Credenziali as C WHERE P.cf = C.genitore AND nomeutente = '$username' AND password ='$pwd';";
$query_res = pg_query($con, $query);
if(!$query_res){
    echo "Errore: ".pg_last_error($con);
	exit;
}
$tuple = pg_fetch_assoc($query_res);
if($tuple){
    $_SESSION["username"] = $username;
    $_SESSION["cf"] = $tuple["cf"];
    $_SESSION["nome"] = $tuple["nome"];
    $_SESSION["cognome"] = $tuple["cognome"];
    $queryFiglio = "SELECT alunno FROM Referente as R WHERE R.genitore = '$usr';";
    $queryFiglio_res = pg_query($con,$queryFiglio);
    if(!$queryFiglio_res){
        echo 'Errore: '.pg_last_error($con);
        exit;
    }
    $_SESSION["figlio"] = pg_fetch_assoc($queryFiglio_res)["alunno"]; 
    $_SESSION['stampa']='';
}
header("location:index_registro.php");
?>