<?php
require_once("comuni/utility.php");
$con = connect_DB();
$query = "SELECT * FROM Scuola";
$query_res=pg_query($con, $query);
if(!$query_res)  {
	echo "Errore: ".pg_last_error($con);
	exit;
}
echo <<<STAMPA
<h3>Scuola</h3>
<table class="elenco">
<tr>
<th>Codice</th>
<th>Nome</th>
<th>Indirizzo</th>
<th>Anno fondazione</th>
</tr>
STAMPA;
while($scuola = pg_fetch_assoc($query_res)){
    $codice = $scuola["codice"];
    $nome = $scuola["nome"];
    $indirizzo = $scuola["indirizzo"];
    $anno = $scuola["annofondazione"];
    echo '<tr>';
    echo '<td>'.$codice.'</td>';
    echo '<td>'.$nome.'</td>';
    echo '<td>'.$indirizzo.'</td>';
    echo '<td>'.$anno.'</td>';
    echo '</tr>';
}
echo '</table>'
?>
<form action="insert_scuola1.php" method="POST">
  <table>
    <tr>
        <td>Codice</td>
        <td><input type="text" name="codice"  title="Inserire codice scuola" size="5" required></td>
    </tr>
    <tr>
        <td>Nome</td>
        <td><input type="text" name="nome"  title="Inserire nome scuola" size="50" required></td>
    </tr>
    <tr>
        <td>Indirizzo</td>
        <td><input type="text" name="indirizzo"  title="Inserire indirizzo scuola" size="50" required></td>
    </tr>
    <tr>
        <td>Anno fondazione</td>
        <td><input type="number" name="anno" min="1900" max="2019" title="Inserire anno fondazione scuola"  ></td>
    </tr>
    <tr>
        <td></td>
        <td><input type="reset" value="Cancella" style="font-size: 12px;"><input type="submit" value="Inserisci scuola" style="margin-left: 10px; font-size: 12px"></td>
      </tr>
  </table>
</form>
