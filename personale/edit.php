<?php
require_once("../comuni/utility.php");

$con = connect_DB();

$cf = $_GET['cf'];
$query = "SELECT * FROM Personale WHERE cf = '$cf'";

$result= pg_query($con,$query);
if(!$result){
    echo "Errore: ".pg_last_error($con);
    exit;
}
$personale = pg_fetch_assoc($result);
$codTipoPersonale = $personale["tipopersonale"];
$mansione = $personale["mansione"];

$queryT = "SELECT * FROM TipoPersonale";
$query_resT=pg_query($con, $queryT);
if(!$query_resT)  {
	echo "Errore: ".pg_last_error($con);
	exit;
}

$queryP = "SELECT * FROM Persona WHERE cf='$cf'";
$query_resP=pg_query($con, $queryP);   
if(!$query_resP)  {
	echo "Errore: ".pg_last_error($con);
	exit;
}
$persona = pg_fetch_assoc($query_resP);
echo '<h1>Modifica di '.$persona["cognome"].' '.$persona["nome"].'</h1>';
echo '<form action="edit1.php" method="POST">
  <table>
	<tr>
	<td>Codice fiscale</td>
	<td><input type="text" name="cf" readonly value="'.$cf.'"</td>
	</tr> 
	<tr>
	<td>Tipo</td>
	<td><select name="codTipoPersonale">';
while($tipo = pg_fetch_assoc($query_resT)){
	if ($tipo['codice'] == $codTipoPersonale){
		echo '<option selected value="'.$tipo['codice'].'">'.$tipo['tipo'].'</option>';
	}else{
		echo '<option value="'.$tipo['codice'].'">'.$tipo['tipo'].'</option>';
	}
}
echo '</select>
</td></tr>
<tr>
	<td>Mansione</td>
	<td><input type="text" name="mansione"  title="Inserire mansione" size="50" value="'.$mansione.'"></td>
</tr>
<tr>
	<td><a href="index.php">Torna alla pagina index per il personale.</td>
	<td><input type="reset" value="Cancella" style="font-size: 12px;"><input type="submit" value="Modifica personale" style="margin-left: 10px; font-size: 12px"></td>
</tr>
</table>
</form>';
?>
<style>
table,td,th{
	border: 1px solid black;
}
</style>

  