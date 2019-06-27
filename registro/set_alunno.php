<?php
require_once("../comuni/header.php");
session_start();
$con = connect_DB();
$_SESSION['figlio'] = $GET['figlio'];
$f = $_SESSION['figlio'];
$queryVoto = "SELECT * FROM Verifica WHERE alunno = '$f'; ";
$queryVoto_res=pg_query($con,$queryVoto);
while($voto = pg_fetch_assoc($queryVoto_res)){
    $mat = $voto["materia"];
    $d = $voto["data"];
    $v = $voto["voto"];
    $t = $voto["tipo_prova"];
    $SESSION['stampa'] .= '<tr>
        <td>'.$mat.'</td>
        <td>'.$d.'</td>
        <td>'.$v.'</td>
        <td>'.$t,'</td>
    </tr>';
header("location: index_registro.php");
?>