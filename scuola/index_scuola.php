<?php
require_once("../comuni/utility.php");

function tipiScuola($cod,$conn){
    $query1 = "SELECT ts.tipo AS tipo FROM TipoScuola AS ts,TipoOspitato AS too WHERE too.tipo_scuola = ts.codice AND scuola = '$cod' ";
    $query_res1 = pg_query($conn,$query1);
    if(!$query_res1){
        echo "Errore: ".pg_last_error($conn);
        exit;
    }
    echo '<td><table>';
    while ($tipo = pg_fetch_assoc($query_res1)){
        echo '<tr><td>'.$tipo["tipo"].'</td></tr>';
    }
    echo '</table></td>';
}

$con = connect_DB();
$query = "SELECT * FROM Scuola";
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
<th>Anno fondazione</th>
<th>Tipi ospitati</th>
</tr>
STAMPA;
while($scuola = pg_fetch_assoc($query_res)){
    $codice = $scuola["codice"];
    $nome = $scuola["nome"];
    $indirizzo = $scuola["indirizzo"];
    $anno = $scuola["annofondazione"];
    echo '<tr>';
    echo '<td>'.$codice.'</td>';
    echo '<td>'.$nome.'</td>';
    echo '<td>'.$indirizzo.'</td>';
    echo '<td>'.$anno.'</td>';
    tipiScuola($codice,$con);
    echo '<td>
    <form action="edit_scuola.php" method="get">
    <input type="hidden" name="codice" value="'.$codice.'" />
    <input type="submit" value="Modifica" />
    </form>
    </td>' ;
    echo '</tr>';
}
echo <<< STAMPA
<tr><td><a href="insert_scuola.php">Inserisci una nuova scuola</td></tr>
</table>
STAMPA;
?>


