<?php
    require_once("../comuni/utility.php");
    $con = connect_DB();
    $current = date("M");
    if($current<9){
        $aa1 = date("Y")-1;
        $aa2 = date("Y");
    }
    else{
        $aa1 = date("Y");
        $aa2 = date("Y")+1;
    }
    $query = "SELECT DISTINCT S.supplente FROM Supplenza as S, Personale as P WHERE S.supplente = P.cf AND EXTRACT(YEAR FROM S.datainizio) IN ($aa1, $aa2);";
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

$query = "SELECT S.supplente, COUNT(*) as numSupplenze FROM Supplenza as S, Personale as P WHERE S.supplente = P.cf AND EXTRACT(YEAR FROM S.datainizio) IN ($aa1, $aa2) GROUP BY S.supplente;";
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
        if($supp["numSupplenze"]>1){
            echo '<tr><td>';
            echo $supp["supplente"];
            echo '</td></tr>';
        }
        
    }
echo '</table>';
?>