<?php
    require_once("../comuni/utility.php");
    require_once("../comuni/header.php");
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
$pari=true;
    while($supp = pg_fetch_assoc($query_res)){
        if ($pari) $color="row0";
        else $color="row1";
        echo '<tr class="'.$color.'"><td class="'.$color.'">';
        echo $supp["supplente"];
        echo '</td></tr>';
        $pari=!$pari;
    }
echo '</table>';

$queryNumSupp = "SELECT S.supplente, COUNT(*) as numsupplenze FROM Supplenza as S, Personale as P WHERE S.supplente = P.cf AND EXTRACT(YEAR FROM S.datainizio) IN ($aa1, $aa2) GROUP BY S.supplente;";
$queryNumSupp_res = pg_query($con, $queryNumSupp);
    if(!$queryNumSupp_res){
        echo 'Errore: '.pg_last_error($con);
        exit;
    }
echo<<< STAMPA
    <table>
        <th>Supplente</th>
STAMPA;
$pari=true;
    while($supp = pg_fetch_assoc($queryNumSupp_res)){
        if ($pari) $color="row0";
        else $color="row1";
        if($supp["numsupplenze"]>1){
            echo '<tr class="'.$color.'"><td class="'.$color.'">';
            echo $supp["supplente"];
            echo '</td></tr>';
        }
        $pari=!$pari;
    }
echo '</table>';
?>