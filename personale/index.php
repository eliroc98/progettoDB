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

$queryS = "SELECT * FROM Scuola";
$query_resS=pg_query($con, $queryS);
if(!$query_resS)  {
	echo "Errore: ".pg_last_error($con);
	exit;
}
echo '
<h3>Personale</h3>
<div><a href="/Progetto/insegnanti_supplenti/">Informazioni su insegnanti supplenti</a></div>
<table class="elenco">
<tr>
<th>Codice fiscale</th>
<th>Nome e cognome</th>
<th>Mansione</th>
<th>Tipo</th>
</tr>';
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
echo '
<tr><td><a href="insert.php">Inserisci un nuovo personale</td></tr>
</table>
<h3>Filtra personale</h3>
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
		<td>Anno scolastico</td>
		<td><select name="annoScolastico" disabled id="selectAnno" required>
			<option value="">Scegli un anno scolastico</option>';
			echo '</select>
		</td>
	</tr>
	<tr>
		<td><input type="button" value="Cerca" onclick="cerca();"></td>
	</tr>
</table>
<table class="elenco" id="tabFilter">
<thead>
<tr>
<th>Codice fiscale</th>
<th>Nome e cognome</th>
<th>Mansione</th>
<th>Tipo</th>
</tr>
</thead>
<tbody></tbody>
</table>
';
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

function cerca(){
	var scuola = $('#selectScuola').val();
	var anno = $('#selectAnno').val();
	$.get('selectPersonale1.php', { scuola: scuola, anno:anno }, function(data){
		var personale = JSON.parse(data);
		$("#tabFilter > tbody").empty();
		$.each( personale, function( i, row ) {
			$('#tabFilter tbody').append('<tr ><td>'+row["cf"]+'</td><td>'+row["nome"] + " " + row["cognome"]+'</td><td>'+row["mansione"]+'</td><td>'+row["tipo"]+'</td></tr>');
		});
	});		
}
</script>