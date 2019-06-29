<head>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>

<?php
require_once("../comuni/utility.php");
require_once("../comuni/header.php");
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

$queryI = "SELECT * FROM Impiego WHERE personale='$cf'";
$query_resI=pg_query($con, $queryI);
if(!$query_resI)  {
	echo "Errore: ".pg_last_error($con);
	exit;
}

$persona = pg_fetch_assoc($query_resP);
echo '<h1>Modifica di '.$persona["cognome"].' '.$persona["nome"].'</h1>
<form action="edit1.php" method="POST" onsubmit="validateForm();">
  <table>
	<tr>
		<td>Codice fiscale</td>
		<td><input type="text" name="cf" value="'.$cf.'" minlength=16 maxlength=16 required</td>
		<input type="hidden" name="vecchioCF" value="'.$cf.'" </td>
	</tr> 
	<tr>
        <td>Cognome</td>
		<td><input type="text" value="'.$persona["cognome"].'" name="cognome"  title="Inserire cognome" minlength="3" maxlength="16" pattern="[a-zA-z]+" required></td>
	</tr>
	<tr>
        <td>Nome</td>
		<td><input type="text" value="'.$persona["nome"].'" name="nome"  title="Inserire nome" minlength="3" maxlength="16" pattern="[A-Za-z]+" required></td>
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
			echo '</select></td>
	</tr>
	<tr>
		<td>Mansione</td>
		<td><input type="text" name="mansione"  title="Inserire mansione" size="50" value="'.$mansione.'"></td>
	</tr>
	<tr>
		<td>Impiego</td>
		<td><select name="inizioScuolaImpiego" id="selectImpiego">
				<option value="">Scegli un impiego</option>';
				while($impiego = pg_fetch_assoc($query_resI)){
					echo '<option value="'.$impiego['inizio'].' '.$impiego['scuola'].'">'.$impiego['scuola'].' da '.$impiego['inizio'].' a '.$impiego['fine'].'</option>';
				}
			echo '</select></td>
	</tr>
	<tr>
		<td>Scuola</td>
		<td><select required name="codScuola" id="selectScuola" class="toggle" disabled>
				<option value="">Scegli una scuola</option>';
				while($scuola = pg_fetch_assoc($query_resS)){
				   echo '<option value="'.$scuola['codice'].'">'.$scuola['nome'].' ,'.$scuola['indirizzo'].'</option>';
				}	
			echo '</select></td></tr>
	<tr>
		<td>Anno scolastico</td>
		<td><select name="annoScolastico"  id="selectAnno" class="toggle" disabled>
			<option value="">Scegli un anno scolastico</option>';
	echo '</select></td>
	</tr>
	<tr>
		<td>Inizio</td>
		<td><input type="text" name="dataInizio" id="dataInizio" class="datepicker toggle" title="Inserire data inizio" disabled></td>
	</tr>
	<tr>
		<td>Fine</td>
		<td><input type="text" name="dataFine" id="dataFine" class="datepicker toggle" title="Inserire data fine" disabled ></td>
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

function rangeDate(){
	var selectedText = $("#selectAnno option:selected").val();
	var dateRange = selectedText.replace("/",":");
	var anni=dateRange.split(":");
	
	$('.datepicker').datepicker( "option", "yearRange", dateRange);
	$('.datepicker').datepicker( "option", "minDate", new Date(anni[0]+'-1-1'));
	$('.datepicker').datepicker( "option", "maxDate", new Date(anni[1]+'-12-31'));
}

 
 $('#selectAnno').on('change', function (e) {
	if(this.value != ""){
		$(".toggle").attr("disabled", false);
		rangeDate();
	}
	else{
		$(".toggle").attr("disabled", true);
		$('#selectAnno').attr("disabled", false);
	}
	$('#dataInizio').val("");
	$('#dataFine').val("");
	
});

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

$('#selectImpiego').on('change', function (e) {
    var valueSelected = this.value;
	var textSelected = $( "#selectImpiego option:selected" ).text();
	
	if (valueSelected==""){
		$(".toggle").attr("disabled", true);
	}else{
		$(".toggle").attr("disabled", false);
		
		var splitText=textSelected.split(" ");
		
		$('#selectScuola').val(splitText[0]);
		var dataInizio=splitText[2].split("-");
		
		var valueSelected=valueSelected.split(" ");
		$("#selectAnno").empty();
		
		var annoScolastico;
		if(dataInizio[2]>9){
			annoScolastico=dataInizio[0]+"/"+(parseInt(dataInizio[0])+1);
		}else{
			annoScolastico=parseInt(dataInizio[0]-1)+"/"+dataInizio[0];
		}
		var dateRange;
		$.get('selectAnno1.php', { codScuola: valueSelected[1] }, function(data){
				anni = JSON.parse(data);
				$("#selectAnno").append(new Option("Scegli un anno scolastico",""));

				$.each( anni, function( key, value ) {
					if(value==annoScolastico){
						$("#selectAnno").append(new Option(String(value),String(value),false,true));
						dateRange = value.replace("/",":");	
					}else{$("#selectAnno").append(new Option(String(value),String(value)));}
					
				});
				var anni=dateRange.split(":");
				
				$('.datepicker').datepicker( "option", "yearRange", dateRange);
				$('.datepicker').datepicker( "option", "minDate", new Date(anni[0]+'-1-1'));
				$('.datepicker').datepicker( "option", "maxDate", new Date(anni[1]+'-12-31'));
				$('#dataInizio').val(splitText[2]);
				$('#dataFine').val(splitText[4]);
		});
	}
});

function validateForm(){
	
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
 