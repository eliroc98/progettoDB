<?php
require_once("../comuni/utility.php");
$con = connect_DB();

$codice = $_GET['codice'];
$query = "SELECT * FROM Scuola WHERE codice = '$codice'";

$result= pg_query($con,$query);
if(!$result){
    echo "Errore: ".pg_last_error($con);
    exit;
}
$scuola = pg_fetch_assoc($result);
$cod = $scuola["codice"];
$nome = $scuola["nome"];
$indirizzo = $scuola["indirizzo"];
$anno = $scuola["annofondazione"];

$query1 = "SELECT tipo_scuola AS tipo FROM TipoOspitato  WHERE scuola = '$cod' ";
$query_res1 = pg_query($con,$query1);
if(!$query_res1){
    echo "Errore: ".pg_last_error($con);
    exit;
}
$infanzia = '';
$elementare = '';
$media = '';
while ($tipo = pg_fetch_assoc($query_res1)){
    if($tipo["tipo"]=='1') $infanzia='checked';
    if($tipo["tipo"]=='2') $elementare='checked';
    if($tipo["tipo"]=='3') $media ='checked';
}


echo'
<form action="edit_scuola1.php" method="POST">
  <table>
    <tr>
        <td>Codice</td>
        <td><input type="text" name="codice"  size="5" value="'.$cod.'" required readonly></td>
    </tr>
    <tr>
        <td>Nome</td>
        <td><input type="text" name="nome"  value="'.$nome.'" title="Inserire nome scuola" size="50" required></td>
    </tr>
    <tr>
        <td>Indirizzo</td>
        <td><input type="text" name="indirizzo" value="'.$indirizzo.'" title="Inserire indirizzo scuola" size="50" required></td>
    </tr>
    <tr>
        <td>Anno fondazione</td>
        <td><input type="number" name="anno" min="1900" max="2019" value="'.$anno.'" title="Inserire anno fondazione scuola" ></td>
    </tr>
    <tr>
        <td>Tipi ospitati</td>
        <table>
            <tr><td><input type="checkbox" name="infanzia" title="Infanzia" '.$infanzia.' />Infanzia</td></tr>
            <tr><td><input type="checkbox" name="elementare" title="Elementare" '.$elementare.' />Elementare</td></tr>
            <tr><td><input type="checkbox" name="media" title="Media" '.$media.' />Media</td></tr>
        </table>
    </tr>
    <tr>
        <td></td>
        <td><input type="submit" value="Modifica scuola" style="margin-left: 10px; font-size: 12px"></td>
      </tr>
  </table>
</form>
'

?>
