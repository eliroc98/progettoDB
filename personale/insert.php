<head>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<?php
require_once("../comuni/utility.php");

$con = connect_DB();

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

$queryA = "SELECT * FROM Attivita"; //Per l'anno scolastico
$query_resA=pg_query($con, $queryA);
if(!$query_resA)  {
	echo "Errore: ".pg_last_error($con);
	exit;
}

echo '<h1>Inserimento personale</h1>
<form action="insert1.php" method="POST" onsubmit="return validateForm();">
  <table>
    <tr>
        <td>CF</td>
		<td><input type="text" name="cf"  title="Inserire CF"  minlength=16 maxlength=16 required></td>
	</tr>
	<tr>
        <td>Cognome</td>
		<td><input type="text" name="cognome"  title="Inserire cognome" minlength="3" maxlength="16" pattern="[a-zA-z]+" required></td>
	</tr>
	<tr>
        <td>Nome</td>
		<td><input type="text" name="nome"  title="Inserire nome" minlength="3" maxlength="16" pattern="[A-Za-z]+" required></td>
	</tr>
	<tr>
		<td>Mansione</td>
		<td><input type="text" name="mansione"  title="Inserire mansione" maxlength="50" pattern="[A-Za-z]+" ></td>
	</tr>
	<tr>
		<td>Tipo</td>
		<td><select name="codTipoPersonale" required>
			<option value="">Scegli un tipo</option>';
			while($tipo = pg_fetch_assoc($query_resT)){
			   echo '<option value="'.$tipo['codice'].'">'.$tipo['tipo'].'</option>';
			}
			echo '</select></td>
	</tr>
	<tr>
		<td>Scuola</td>
		<td><select name="codScuola" id="selectScuola" required>
			<option value="">Scegli una scuola</option>';
			while($scuola = pg_fetch_assoc($query_resS)){
			   echo '<option value="'.$scuola['codice'].'">'.$scuola['nome'].' ,'.$scuola['indirizzo'].'</option>';
			}	
			echo '</select></td></tr>
	<tr>
		<td>Anno scolastico</td>
		<td><select name="annoScolastico" disabled id="selectAnno" required>
			<option value="">Scegli un anno scolastico</option>';
			echo '</select>
		</td>
	</tr>
	<tr>
		<td>Inizio</td>
		<td><input type="text" name="dataInizio" id="dataInizio" class="datepicker" title="Inserire data inizio" required ></td>
	</tr>
	<tr>
		<td>Fine</td>
		<td><input type="text" name="dataFine" id="dataFine" class="datepicker" title="Inserire data fine" required></td>
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
	$("#selectAnno").empty();
    var valueSelected = this.value;
	 $.get('selectAnno1.php', { codScuola: valueSelected }, function(data){
                    anni = JSON.parse(data);
					$("#selectAnno").append(new Option("Scegli un anno scolastico",""));
					$.each( anni, function( key, value ) {
						$("#selectAnno").append(new Option(String(value),String(value)));
					});
		});		
	$('#selectAnno').prop("disabled", false); 
	
});
$('#selectAnno').on('change', function (e) {
	$(".datepicker" ).datepicker({
        //dateFormat: "dd/mm/yy",
        changeMonth: true,
		showButtonPanel: true,
        changeYear: true
    });
    var optionSelected = $("option:selected", this);
    var valueSelected = this.value;
	var dateRange = valueSelected.replace("/",":");
	$('.datepicker').datepicker( "option", "yearRange", dateRange );
	
});


function validateForm()
{
	var startDate = new Date($('#dataInizio').val());
	var splitStartDate = startDate.split("/");
	var formatStartDate = new Date(splitStartDate[2], splitStartDate[1] - 1, splitStartDate[0]);
	var endDate = new Date($('#dataFine').val());
	var splitEndDate = endDate.split("/");
	var formatEndDate = new Date(splitEndDate[2], splitEndDate[1] - 1, splitEndDate[0])

	if (formatStartDate < formatEndDate){
		return true;
	}
	else{
		alert("La data di fine deve essere dopo la data di inizio");
		return false;
	}
}
</script>

  