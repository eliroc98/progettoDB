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
<th>Mansione</th>
<th>Tipo</th>

</tr>
STAMPA;
while($personale = pg_fetch_assoc($query_res)){
    
    $cf = $personale["cf"];
    $mansione = $personale["mansione"];
    $tipo = $personale["tipopersonale"];
	
    echo '<tr>';
    echo '<td>'.$cf.'</td>';
    echo '<td>'.$mansione.'</td>';
    echo '<td>'.$tipo.'</td>';
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


