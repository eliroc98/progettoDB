<?php
    require_once("../comuni/utility.php");
    $con = connect_DB();
    $query = "SELECT S.supplente FROM Supplenza as S, Personale as P WHERE S.supplente = P.cf AND EXTRACT(YEAR FROM S.datainizio) IN (2018, 2019);";
    $query_res = pg_query($con, $query);
    if(!$query_res){
        echo 'Errore: '.pg_last_error($con);
        exit;
    }
echo<<< STAMPA
    <table>
        <th>Supplente</th>
STAMPA;
    while($supp = pg_fetch_assoc($query_res)){
        echo '<tr><td>';
        echo $supp["supplente"];
        echo '</td></tr>';
    }
echo '</table>';
?>