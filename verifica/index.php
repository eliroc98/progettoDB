<?php
require_once("../comuni/utility.php");

$con = connect_DB();
$query = "SELECT * FROM Personale";
$query_res=pg_query($con, $query);
if(!$query_res)  {
	echo "Errore: ".pg_last_error($con);
	exit;
}

echo <<<STAMPA
<h3>Personale</h3>
<table class="elenco">
<tr>
<th>Codice fiscale</th>
<th>Nome e cognome</th>
<th>Mansione</th>
<th>Tipo</th>

</tr>
STAMPA;
while($personale = pg_fetch_assoc($query_res)){
    
    $cf = $personale["cf"];
    $mansione = $personale["mansione"];
    $queryTipo = pg_query($con, "SELECT * FROM TipoPersonale WHERE codice='$personale[tipopersonale]'");
	$tipo = pg_fetch_row($queryTipo);
	
	$queryPersona = pg_query($con, "SELECT * FROM Persona WHERE cf='$cf'");
	$persona = pg_fetch_row($queryPersona);
 
    echo '<tr>';
    echo '<td>'.$cf.'</td>';
	echo '<td>'.$persona[1].' '.$persona[2].'</td>';
    echo '<td>'.$mansione.'</td>';
    echo '<td>'.$tipo[1].'</td>';
    echo '<td>
    <form action="edit.php" method="get">
    <input type="hidden" name="cf" value="'.$cf.'" />
    <input type="submit" value="Modifica" />
    </form>
    </td>' ;
    echo '</tr>';
}
echo <<< STAMPA
<tr><td><a href="insert.php">Inserisci un nuovo personale</td></tr>
</table>
STAMPA;
?>
<style>
table,td,th{
	border: 1px solid black;
}
</style>

