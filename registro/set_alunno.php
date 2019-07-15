<?php
require_once("../comuni/utility.php");
session_start();
$con = connect_DB();
$_SESSION['stampa']='';
$_SESSION['figlio'] = $_GET['figlio'];
$f = $_SESSION['figlio'];
if($_GET['anno']!="no"){
    $_SESSION['anno'] = $_GET['anno'];
    $a = explode("/",$_SESSION['anno']);
    $queryVoto = "SELECT alunno, materia, data, voto, tipo_prova FROM Verifica WHERE alunno = '$f' GROUP BY alunno, materia,data, tipo_prova HAVING  EXTRACT(YEAR FROM data)  IN ('$a[0]','$a[1]'); ";
} 
else $queryVoto = "SELECT * FROM Verifica WHERE alunno = '$f'; ";
$queryVoto_res=pg_query($con,$queryVoto);
while($voto = pg_fetch_assoc($queryVoto_res)){
    $mat = $voto["materia"];
    $d = $voto["data"];
    $v = $voto["voto"];
    $t = $voto["tipo_prova"];
    $_SESSION['stampa'] .= '<tr><td>'.$mat.'</td><td>'.$d.'</td><td>'.$v.'</td><td>'.$t.'</td></tr>';
}
echo $_SESSION['stampa'];
?>