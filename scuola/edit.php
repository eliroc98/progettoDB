<?php
require_once("../comuni/utility.php");
require_once("../comuni/header.php");
$con = connect_DB();

$codice = $_GET['codice'];
$query = "SELECT * FROM Scuola,ContattoS,Telefono WHERE codice = scuola AND codice = '$codice' AND Telefono.numero = ContattoS.telefono";

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
$telefono = $scuola["telefono"];
$tipotel = $scuola["tipo"];

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
$cr=null;
$ca=null;
$ct=null;
if($infanzia=="checked"){
    $queryR = "SELECT * FROM Ristrutturazione WHERE scuola='$cod' ORDER BY anno DESC LIMIT 1";
    $query_resR = pg_query($con,$queryR);
    if(!$query_resR){
        echo "Errore: ".pg_last_error($con);
        exit;
    }
    $ristrutturazione = pg_fetch_assoc($query_resR);
    $cr=$ristrutturazione["codice"];
    $ca=$ristrutturazione["anno"];
    $ct=$ristrutturazione["tipo"];
    
}



echo'
<form action="edit1.php" method="POST">
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
    <td>Contatto Scuola</td>
    <td>
        <table>
            <tr>
                <td>Numero di telefono</td>
                <td><input value="'.$telefono.'" type ="tel" name="numero" title="Inserire numero di telefono scuola" required readonly/></td>
            </tr>
            <tr>
                <td>Tipo numero di telefono</td>
                <td>
                    <select name="tipotel">';
                    if($tipotel=="cellulare") echo '<option value="cellulare" selected>Cellulare</option>';
                    else echo '<option value="cellulare">Cellulare</option>';
                    if($tipotel=="fisso") echo '<option value="fisso" selected>Fisso</option>';
                    else echo '<option value="fisso">Fisso</option>';
                    echo'
                    </select>
                </td>
            </tr>
        </table>
    </td>
    </tr>
    <tr>
        <td>Indirizzo</td>
        <td><input type="text" name="indirizzo" value="'.$indirizzo.'" title="Inserire indirizzo scuola" size="50" required></td>
    </tr>
    <tr>
        <td>Tipi ospitati</td>
        <table>
            <tr><td><input type="checkbox" name="infanzia" id="infanzia" title="Infanzia" onClick="toggle(\'infanzia\', \'ristrutturazione\')"'.$infanzia.' />Infanzia</td></tr>
            <tr><td><input type="checkbox" name="elementare" title="Elementare"'.$elementare.' />Elementare</td></tr>
            <tr><td><input type="checkbox" id="media" name="media" title="Media" onClick="toggle(\'media\', \'anno\')"'.$media.' />Media</td></tr>
        </table>
    </tr>
    <tr>
        <td>Anno fondazione</td>
        <td><input type="number" name="anno" id="anno" class="anno" min="1900" max="2019" title="Inserire anno fondazione scuola" disabled="true" value="'.$anno.'"></td>
    </tr>
    <tr>
        
        <table>
        <tr><td>Ultima ristrutturazione</td></tr>
            <tr>
            <td>Codice</td>
            <td><input type="text" name="codR" title="Inserire codice ultima ristrutturazione" class="ristrutturazione" disabled="true" value="'.$cr.'"></td></tr>
            <tr><td>Anno</td>
            <td><input type="number" name="annoR" title="Inserire anno dell\'ultima ristrutturazione" class="ristrutturazione" disabled="true" value="'.$ca.'"></td></tr>
            <tr><td>Tipo</td>
            <td><input type="text" name="tipoR" title="Inserire tipo dell\'ultima ristrutturazione" class="ristrutturazione" disabled="true" value="'.$ct.'"></td></tr>
        </table>
    </tr>
    <tr>
    <td><a href="index.php">Torna alla pagina index per le scuole.</td>
        <td><input type="submit" value="Modifica scuola" style="margin-left: 10px; font-size: 12px"></td>
      </tr>
  </table>
</form>
'

?>
<script>
function toggle(checkboxID, toggleID) {
     var checkbox = document.getElementById(checkboxID);
     var toggle = document.getElementsByClassName(toggleID);
     var i;
    for (i = 0; i < toggle.length; i++) {
        updateToggle = checkbox.checked ? toggle[i].disabled=false : toggle[i].disabled=true;
    }
}
toggle('infanzia','ristrutturazione');
toggle('media','anno');
</script>
