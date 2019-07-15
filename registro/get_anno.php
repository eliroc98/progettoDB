<?php
require_once("../comuni/utility.php");
session_start();
$con = connect_DB();
$_SESSION['figlio'] = $_GET['figlio'];
$f = $_SESSION['figlio'];
$_SESSION['stampa']='';
$query = "SELECT DISTINCT annoscolastico FROM Frequentazione, Classe WHERE alunno = '$f' AND classe = codice; ";
$query_res=pg_query($con,$query);
while($voto = pg_fetch_assoc($query_res)){
    $aa = $voto["annoscolastico"];
    $_SESSION['stampa'] .= '<option value="'.$aa.'">'.$aa.'</option>';
}
echo $_SESSION['stampa'];
?>