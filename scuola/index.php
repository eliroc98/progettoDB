<?php
require_once("../comuni/utility.php");
require_once("../comuni/header.php");
function tipiScuola($cod,$conn,$color){
    $query1 = "SELECT ts.tipo AS tipo FROM TipoScuola AS ts,TipoOspitato AS too WHERE too.tipo_scuola = ts.codice AND scuola = '$cod' ";
    $query_res1 = pg_query($conn,$query1);
    if(!$query_res1){
        echo "Errore: ".pg_last_error($conn);
        exit;
    }
    echo '<td>';
    while ($tipo = pg_fetch_assoc($query_res1)){
        echo '<tr class="'.$color.'"><td>'.$tipo["tipo"].'</td></tr>';
    }
    echo '</td>';
}

$con = connect_DB();
$query = "SELECT * FROM Scuola,ContattoS WHERE codice = scuola";
$query_res=pg_query($con, $query);
if(!$query_res)  {
	echo "Errore: ".pg_last_error($con);
	exit;
}

echo <<<STAMPA
<h3>Scuola</h3>
<table class="elenco">
<tr>
<th>Codice</th>
<th>Nome</th>
<th>Indirizzo</th>
<th>Telefono</th>
<th>Tipi ospitati</th>
<th>Anno fondazione</th>
<th>Ultima ristrutturazione</th>
</tr>
STAMPA;
$pari=true;
while($scuola = pg_fetch_assoc($query_res)){
    if ($pari) $color="row0";
	else $color="row1";
    $codice = $scuola["codice"];
    $nome = $scuola["nome"];
    $indirizzo = $scuola["indirizzo"];
    $telefono = $scuola["telefono"];
    $anno = $scuola["annofondazione"];
    $ca=null;
    $ct=null;
    $query1 = "SELECT * FROM TipoOspitato  WHERE tipo_scuola = 1 AND scuola = '$codice' ";
    $query_res1 = pg_query($con,$query1);
    if($query_res1){
        $queryR = "SELECT * FROM Ristrutturazione WHERE scuola='$codice' ORDER BY anno DESC LIMIT 1";
        $query_resR = pg_query($con,$queryR);
        if(!$query_resR){
            echo "Errore: ".pg_last_error($con);
            exit;
        }
        $ristrutturazione = pg_fetch_assoc($query_resR);
        $ca=$ristrutturazione["anno"];
        $ct=$ristrutturazione["tipo"];
    }

    echo '<tr class="'.$color.'">';
    echo '<td class="'.$color.'">'.$codice.'</td>';
    echo '<td class="'.$color.'">'.$nome.'</td>';
    echo '<td class="'.$color.'">'.$indirizzo.'</td>';
    echo '<td class="'.$color.'">'.$telefono.'</td>';
    tipiScuola($codice,$con, $color);
    echo '<td class="'.$color.'">'.$anno.'</td>';
    echo '<td class="'.$color.'">'.$ca.' '.$ct.'</td>';
    echo '<td class="'.$color.'">
    <form action="edit.php" method="get">
    <input type="hidden" name="codice" value="'.$codice.'" />
    <input type="submit" value="Modifica" />
    </form>
    </td>' ;
    echo '</tr>';
    $pari=!$pari;
}
echo <<< STAMPA
<tr><td><a href="insert.php">Inserisci una nuova scuola</td></tr>
</table>
STAMPA;
?>


