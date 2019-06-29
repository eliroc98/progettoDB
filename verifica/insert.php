<head>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<?php
require_once("../comuni/utility.php");

$con = connect_DB();


$queryS = "SELECT * FROM Scuola";
$query_resS=pg_query($con, $queryS);
if(!$query_resS)  {
	echo "Errore: ".pg_last_error($con);
	exit;
}

$queryC = "SELECT * FROM Classe";
$query_resC=pg_query($con, $queryC);
if(!$query_resC)  {
	echo "Errore: ".pg_last_error($con);
	exit;
}

$queryM = "SELECT * FROM Materia";
$query_resM=pg_query($con, $queryM);
if(!$query_resM)  {
	echo "Errore: ".pg_last_error($con);
	exit;
}

$queryA = "SELECT * FROM Alunno"; 
$query_resA=pg_query($con, $queryA);
if(!$query_resA)  {
	echo "Errore: ".pg_last_error($con);
	exit;
}

echo '<h1>Inserimento voti</h1>
<form action="insert1.php" method="POST" onsubmit="return validateForm();">
  <table>
  	<tr>
		<td>Scuola</td>
		<td><select name="codScuola" id="selectScuola" required>
			<option value="">Scegli una scuola</option>';
			while($scuola = pg_fetch_assoc($query_resS)){
			   echo '<option value="'.$scuola['codice'].'">'.$scuola['nome'].' ,'.$scuola['indirizzo'].'</option>';
			}	
			echo '</select></td></tr>
	<tr>
	<tr>
		<td>Classe</td>
		<td><select name="codClasse" id="selectClasse" required disabled>
				<option value="">Scegli una classe</option>
			</select></td>
	</tr>
	<tr>
		<td>Materia</td>
		<td><select name="nomeMateria" id="selectMateria" required disabled>
				<option value="">Scegli una materia</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Alunno</td>
		<td><select name="cf" id="selectAlunno" required disabled>
				<option value="">Scegli un alunno</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Voto</td>
		<td><input type="number" step="0.5" min="0" max="10" name="voto" id="selectVoto" class="datepicker" title="Inserire data prova" required disabled></td>
	</tr>
	<tr>
		<td>Tipo prova</td>
		<td><select name="tipoProva" id="selectProva" required disabled>
				<option value="">Scegli un tipo</option>
				<option value="Scritto">Scritto</option>
				<option value="Orale">Orale</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Data prova</td>
		<td><input type="text" name="data" id="data" class="datepicker" title="Inserire data prova" required ></td>
	</tr>
	<tr>
		<td><input type="reset" value="Cancella" style="font-size: 12px;"><input type="submit" value="Inserisci voto" style="margin-left: 10px; font-size: 12px"></td>
	</tr>
</table>
</form>';
?>
<style>
table,td,th{
	border: 1px solid black;
}
</style>
<script>

 $( document ).ready(function() {
    $(".datepicker" ).datepicker({
        changeMonth: true,
		showButtonPanel: true,
		dateFormat: 'yy-mm-dd',
        changeYear: true
    });
	$(".datepicker" ).attr("autocomplete", "off");
});

$('#selectScuola').on('change', function (e) {
    var optionSelected = $("option:selected", this);
    var valueSelected = this.value;
	$.get('checkAnno1.php', { codScuola: valueSelected }, function(data){
		if (!data){
			alert("Errore, la scuola selezionata non contiene un anno scolastico corrente");
			$('#selectClasse').prop("disabled", true); 
		}
		else{
			$('#selectClasse').prop("disabled", false); 
		}
	});	
	$.get('selectClassi1.php', { codScuola: valueSelected }, function(data){
		classi = JSON.parse(data);
		$("#selectClasse").empty();
		$("#selectClasse").append(new Option("Scegli una classe",""));
		$.each( classi, function( key, value ) {
			$("#selectClasse").append(new Option(String(value),String(key)));
		});
	});	
});

$('#selectClasse').on('change', function (e) {
    var optionSelected = $("option:selected", this);
    var valueSelected = this.value;
	$.get('selectMaterie1.php', { codClasse: valueSelected }, function(data){
		classi = JSON.parse(data);
		$("#selectMateria").empty();
		$("#selectMateria").append(new Option("Scegli una materia",""));
		$.each( classi, function( key, value ) {
			$("#selectMateria").append(new Option(String(value),String(key)));
		});
		$('#selectMateria').prop("disabled", false); 
	});	
});

$('#selectMateria').on('change', function (e) {
	var classe=$('#selectClasse').val();
	$.get('selectAlunni1.php', { codClasse: classe }, function(data){
		classi = JSON.parse(data);
		$("#selectAlunno").empty();
		$("#selectAlunno").append(new Option("Scegli un alunno",""));
		$.each( classi, function( key, value ) {
			$("#selectAlunno").append(new Option(String(value),String(key)));
		});
		$('#selectAlunno').prop("disabled", false); 
	});	
});

$('#selectAlunno').on('change', function (e) {
	var optionSelected = $("option:selected", this);
    var valueSelected = this.value;
	if (valueSelected==""){
		$('#selectVoto').prop("disabled", true); 
	}
	else{
		$('#selectVoto').prop("disabled", false); 
	}
});


function validateForm()
{
}
</script>

  