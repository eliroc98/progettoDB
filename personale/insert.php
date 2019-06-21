<?php
require_once("../comuni/utility.php");

$con = connect_DB();
$queryP = "SELECT * FROM Persona";
$query_resP=pg_query($con, $queryP);
if(!$query_resP)  {
	echo "Errore: ".pg_last_error($con);
	exit;
}

$queryT = "SELECT * FROM TipoPersonale";
$query_resT=pg_query($con, $queryT);
if(!$query_resT)  {
	echo "Errore: ".pg_last_error($con);
	exit;
}

echo '<form action="insert1.php" method="POST">
  <table>
    <tr>
        <td>Persona</td>
		<td><select name="dropDownPersonale">';
while($persona = pg_fetch_assoc($query_resP)){
   echo '<option value="'.$persona['cf'].'">'.$persona['cognome'].' '.$persona['nome'].'</option>';
}	
echo '</select></td></tr>
<tr>
	<td>Tipo</td>
	<td><select name="dropDownTipoPersonale">';
while($tipo = pg_fetch_assoc($query_resT)){
   echo '<option value="'.$tipo['codice'].'">'.$tipo['tipo'].'</option>';
}
echo '</select>
</td></tr>
<tr>
	<td>Mansione</td>
	<td><input type="text" name="mansione"  title="Inserire mansione" size="50" required></td>
</tr>
<tr>
	<td><a href="index.php">Torna alla pagina index per il personale.</td>
	<td><input type="reset" value="Cancella" style="font-size: 12px;"><input type="submit" value="Inserisci personale" style="margin-left: 10px; font-size: 12px"></td>
</tr>
</table>
</form>';
?>


  