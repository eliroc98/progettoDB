<?php
require_once("../comuni/utility.php");
require_once("../comuni/header.php");
$con = connect_DB();


$queryS = "SELECT * FROM Scuola";
$query_resS=pg_query($con, $queryS);
if(!$query_resS)  {
	echo "Errore: ".pg_last_error($con);
	exit;
}

echo '<h1>Modifica voti</h1>
<form action="edit1.php" method="POST"">
  <table>
  	<tr>
		<td>Scuola</td>
		<td><select name="scuola" id="selectScuola" required>
			<option value="">Scegli una scuola</option>';
			while($scuola = pg_fetch_assoc($query_resS)){
			   echo '<option value="'.$scuola['codice'].'">'.$scuola['nome'].' ,'.$scuola['indirizzo'].'</option>';
			}	
			echo '</select></td></tr>
	<tr>
	<tr>
		<td>Classe</td>
		<td><select name="classe" id="selectClasse" required disabled>
				<option value="">Scegli una classe</option>
			</select></td>
	</tr>
	<tr>
		<td>Materia</td>
		<td><select name="materia" id="selectMateria" required disabled>
				<option value="">Scegli una materia</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Alunno</td>
		<td><select name="alunno" id="selectAlunno" required disabled>
				<option value="">Scegli un alunno</option>
			</select>
		</td>
	</tr>
		<tr>
		<td>Elenco prove</td>
		<td><select name="voti" id="selectVoti" required disabled>
				<option value="">Scegli una prova</option>
			</select></td>
	</tr>
	<tr>
		<td>Voto</td>
		<td><input type="number" step="0.5" min="0" max="10" name="voto" id="voto" class="prova" title="Inserire data prova" required disabled></td>
	</tr>
	<tr>
		<td>Tipo prova</td>
		<td><select name="tipo_prova" id="selectProva" class="prova" required disabled>
				<option value="">Scegli un tipo</option>
				<option value="Scritto">Scritto</option>
				<option value="Orale">Orale</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Data prova</td>
		<td><input type="text" name="data" id="data" class="datepicker prova" title="Inserire data prova" required ></td>
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
var d =new Date();
var currentYear = d.getFullYear();
var currentMonth= d.getMonth();
var schoolYear = ((currentMonth > 8) ? currentYear+":"+(currentYear+1) : (currentYear-1)+":"+currentYear);

 $( document ).ready(function() {
    $(".datepicker" ).datepicker({
        changeMonth: true,
		showButtonPanel: true,
		dateFormat: 'yy-mm-dd',
		yearRange: schoolYear,
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
	
	$.get('selectProve1.php', { alunno: valueSelected }, function(data){
		prove = JSON.parse(data);
		$('#selectVoti').prop("disabled", false);
		$("#selectVoti").empty();
		$("#selectVoti").append(new Option("Scegli una prova",""));
		$.each( prove, function( key, value ) {
			$("#selectVoti").append(new Option(String(value),String(key)));
		});
	});	
	
});

$('#selectVoti').on('change', function (e) {
	var valueSelected = this.value;
	if( valueSelected =! ""){
		var text = $("#selectVoti option:selected").text();
		var values=text.split(", ");
		$('.prova').prop("disabled", false);
		$('#voto').val(values[0]);
		$('#data').val(values[1]);
		$("#selectProva").val(values[2]).change();
	}
	else{
		$('.prova').prop("disabled", true);
	}
	
});


</script>

  