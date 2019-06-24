<head>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
</head>
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

$queryS = "SELECT * FROM Scuola";
$query_resS=pg_query($con, $queryS);
if(!$query_resS)  {
	echo "Errore: ".pg_last_error($con);
	exit;
}

$queryA = "SELECT * FROM Attivita";
$query_resA=pg_query($con, $queryA);
if(!$query_resA)  {
	echo "Errore: ".pg_last_error($con);
	exit;
}

echo '<h1>Inserimento personale</h1>
<form action="insert1.php" method="POST">
  <table>
    <tr>
        <td>Persona</td>
		<td><select name="cf" required>
		<option value="">Scegli una persona</option>';
while($persona = pg_fetch_assoc($query_resP)){
   echo '<option value="'.$persona['cf'].'">'.$persona['cognome'].' '.$persona['nome'].'</option>';
}	
echo '</select></td></tr>
<tr>
	<td>Tipo</td>
	<td><select required name="codTipoPersonale">
		<option value="">Scegli un tipo</option>';
while($tipo = pg_fetch_assoc($query_resT)){
   echo '<option value="'.$tipo['codice'].'">'.$tipo['tipo'].'</option>';
}
echo '</select>
</td></tr>
<tr>
	<td>Mansione</td>
	<td><input type="text" name="mansione"  title="Inserire mansione" ></td>
</tr>
<tr>
	<td>Scuola</td>
	<td><select required name="codScuola" id="selectScuola">
		<option value="">Scegli una scuola</option>';
while($scuola = pg_fetch_assoc($query_resS)){
   echo '<option value="'.$scuola['codice'].'">'.$scuola['nome'].' ,'.$scuola['indirizzo'].'</option>';
}	
echo '</select></td></tr>
<tr>
	<td>Anno scolastico</td>
	<td><select name="annoScolastico" disabled id="selectAnno">
		<option value="">Scegli un anno scolastico</option>';
while($anno = pg_fetch_assoc($query_resA)){
   echo '<option value="'.$anno['aa'].'">'.$anno['aa'].'</option>';
}
echo '</select>
</td></tr>
<tr>
	<td>Inizio</td>
	<td><input type="date" name="mansione"  title="Inserire mansione"  ></td>
</tr>
<tr>
	<td>Fine</td>
	<td><input type="date" name="mansione"  title="Inserire mansione"  ></td>
</tr>
<tr>
	<td><a href="index.php">Torna alla pagina index per il personale.</td>
	<td><input type="reset" value="Cancella" style="font-size: 12px;"><input type="submit" value="Inserisci personale" style="margin-left: 10px; font-size: 12px"></td>
</tr>
</table>
</form>';
?>

<script>
$('#selectScuola').on('change', function (e) {
    var optionSelected = $("option:selected", this);
    var valueSelected = this.value;
	$('#selectAnno').prop("disabled", false); 
});
</script>

  