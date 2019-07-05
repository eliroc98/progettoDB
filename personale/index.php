<?php
require_once("../comuni/utility.php");
require_once("../comuni/header.php");

$con = connect_DB();
$query = "SELECT * FROM Personale";
$query_res=pg_query($con, $query);
if(!$query_res)  {
	echo "Errore: ".pg_last_error($con);
	exit;
}

echo <<<STAMPA
<h3>Personale</h3>
<div><a href="/insegnanti_supplenti/index.php">Informazioni su insegnanti supplenti</a></div>
<table class="elenco">
<tr>
<th>Codice fiscale</th>
<th>Nome e cognome</th>
<th>Mansione</th>
<th>Tipo</th>

</tr>
STAMPA;
$pari=true;
while($personale = pg_fetch_assoc($query_res)){
    if ($pari) $color="row0";
	else $color="row1";
    $cf = $personale["cf"];
    $mansione = $personale["mansione"];
    $queryTipo = pg_query($con, "SELECT * FROM TipoPersonale WHERE codice='$personale[tipopersonale]'");
	$tipo = pg_fetch_row($queryTipo);
	
	$queryPersona = pg_query($con, "SELECT * FROM Persona WHERE cf='$cf'");
	$persona = pg_fetch_row($queryPersona);
 
    echo '<tr class="'.$color.'">';
    echo '<td class="'.$color.'">'.$cf.'</td>';
	echo '<td class="'.$color.'">'.$persona[1].' '.$persona[2].'</td>';
    echo '<td class="'.$color.'">'.$mansione.'</td>';
    echo '<td class="'.$color.'">'.$tipo[1].'</td>';
    echo '<td class="'.$color.'">
    <form action="edit.php" method="get">
    <input type="hidden" name="cf" value="'.$cf.'" />
    <input type="submit" value="Modifica" />
    </form>
    </td>' ;
    echo '</tr>';
    $pari=!$pari;
}
echo <<< STAMPA
<tr><td><a href="insert.php">Inserisci un nuovo personale</td></tr>
</table>
STAMPA;
?>

