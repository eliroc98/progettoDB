<?php

require_once("../comuni/utility.php");

$con = connect_DB();

$cfGET = $_GET['cf'];

$queryAlunno = "SELECT * FROM Alunno as A, Persona as P, Frequentazione WHERE A.cf = '$cfGET' AND P.cf='$cfGET' AND A.cf = P.cf AND A.cf = alunno AND P.cf = alunno;";
$queryGenitoreA = "SELECT P.cf as cf,P.nome as nome, P.cognome as cognome,G.professione as professione, G.titolo as titolo, Co.telefono as telefono, T.tipo as tipotel, C.nomeutente as nomeutente, C.password as password FROM Genitore as G, Persona as P, ContattoG AS Co, Referente as R, Credenziali as C, Telefono as T WHERE R.alunno = '$cfGET' AND P.cf = G.cf AND R.genitore = P.cf AND R.genitore = G.cf AND Co.genitore = P.cf AND Co.genitore = G.cf AND C.genitore = P.cf AND C.genitore = G.cf AND T.numero = Co.telefono;"; 
$queryGenitoreA_res = pg_query($con,$queryGenitoreA);
$queryAlunno_res = pg_query($con,$queryAlunno);
if(!$queryAlunno_res&&!$queryGenitoreA_res){
    echo "Errore: ".pg_last_error($conn);
    exit; 
}
$alunno = pg_fetch_assoc($queryAlunno_res);
$cf=$alunno["cf"];
$nome =$alunno["nome"];
$cognome =$alunno["cognome"];
$indirizzo =$alunno["indirizzo"];
$datadinascita =$alunno["datadinascita"];
$zona =$alunno["zonaresidenza"];
//c'è anche la classe frequentata e l'anno scolastico
$classeA =$alunno["classe"];
$inizio = $alunno["inizio"];
$pre =$alunno["pre"];
$post =$alunno["post"];

echo'
<form action="edit_alunno1.php" method="POST">
  <table>
    <tr>
        <td>Codice Fiscale</td>
        <td><input value="'.$cf.'" type="text" name="cf"  title="Inserire codice fiscale" size="16" required></td>
    </tr>
    <tr>
        <td>Nome</td>
        <td><input value ="'.$nome.'" type="text" name="nome"  title="Inserire nome alunno" size="50" required></td>
    </tr>
    <tr>
        <td>Cognome</td>
        <td><input value ="'.$cognome.'" type="text" name="cognome"  title="Inserire cognome alunno" size="50" required></td>
    </tr>
    <tr>
        <td>Indirizzo</td>
        <td><input value ="'.$indirizzo.'" type="text" name="indirizzo"  title="Inserire indirizzo" size="50" required></td>
    </tr>
    <tr>
        <td>Data di nascita</td>
        <td><input value ="'.$datadinascita.'"type="date" name="datadinascita"  title="Inserire data di nascita" required></td>
    </tr>
    <tr>
        <td>Data di iscrizione</td>
        <td><input value ="'.$inizio.'" type="date" name="datadiiscrizione"  title="Inserire data di iscrizione" required></td>
    </tr>
    <tr>
        <td>Zona di residenza</td>
        <td><input value ="'.$zona.'" type="text" name="zona"  title="Inserire zona di residenza alunno" size="50" required></td>
    </tr>
    <tr>
        <td>Scuola e classe frequentata</td>
        <td><select name="scuola" id="selectScuolaClasse" >
';
$queryScuole = "SELECT * FROM Scuola;";
$queryScuole_res = pg_query($con,$queryScuole);
if(!$queryScuole_res){
    echo "Errore: ".pg_last_error($conn);
    exit; 
}
while($scuola=pg_fetch_assoc($queryScuole_res)){
   
    $codScuola = $scuola["codice"];
    $queryClasse = "SELECT * FROM Classe WHERE scuola ='$codScuola'; ";
    $queryClasse_res = pg_query($con,$queryClasse);
    if(!$queryClasse){
        echo "Errore: ".pg_last_error($conn);
        exit; 
    }
    while($classe = pg_fetch_assoc($queryClasse_res)){
        if($classe["codice"]==$classeA){
            echo '<option value="'.$classe["codice"].'" selected>'.$classe["codice"].'</option>';
        }
        else echo '<option value="'.$classe["codice"].'">'.$classe["codice"].'</option>';
    }
}
echo<<< STAMPA
        </select></td>
    </tr>
    <tr>
        <td>Usufruisce di opzioni pre-scuola</td>
        <td><input type="checkbox" name="pre"
STAMPA;
if($pre!=null) echo 'checked';
echo<<< STAMPA
        ></td>
    </tr>
    <tr>
        <td>Usufruisce di opzioni post-scuola</td>
        <td><input type="checkbox" name="post"
STAMPA;
if($post!=null) echo 'checked';
echo<<< STAMPA
        ></td>
    </tr>
STAMPA;

while($genitore=pg_fetch_assoc($queryGenitoreA_res)){
    $cfG1 = $genitore["cf"];
    $nomeG1 = $genitore["nome"];
    $cognomeG1 = $genitore["cognome"];
    $professioneG1 = $genitore["professione"];
    $titoloG1 = $genitore["titolo"];
    $numeroG1 = $genitore["telefono"];
    $tipotelG1 = $genitore["tipotel"];
    $usernameG1 = $genitore["nomeutente"];
    $passwordG1 = $genitore["password"];
    if($numeroG1!=null){
echo<<< STAMPA
    <tr>
        <td>Genitore 1</td>
        <td>
            <table>
            <tr>
                <td>Genitore gia esistente</td>
                <td>
                    <select name="selG1">
                    <option value="no">NO</option>
STAMPA;
    $queryGenitore = "SELECT * FROM Genitore; ";
    $queryGenitore_res = pg_query($con,$queryGenitore);
    if(!$queryGenitore_res){
        echo "Errore: ".pg_last_error($conn);
        exit; 
    }
    while($genitore = pg_fetch_assoc($queryGenitore_res)){
        if($genitore["cf"]==$cfG1) echo '<option value="'.$genitore["cf"].'" selected>'.$genitore["cf"].'</option>';
        else echo '<option value="'.$genitore["cf"].'">'.$genitore["cf"].'</option>';
    }
    echo '</select>
                </td>
            </tr>
            <tr>
                <td>Codice Fiscale</td>
                <td><input value="'.$cfG1.'" type="text" name="cfG1"  title="Inserire codice fiscale genitore 1" size="50" required></td>
            </tr>
            <tr>
                <td>Nome</td>
                <td><input value="'.$nomeG1.'" type="text" name="nomeG1"  title="Inserire nome genitore 1" size="50" required></td>
            </tr>
            <tr>
                <td>Cognome</td>
                <td><input value="'.$cognomeG1.'" type="text" name="cognomeG1"  title="Inserire cognome genitore 1" size="50" required></td>
            </tr>
            <tr>
                <td>Professione</td>
                <td><input value="'.$professioneG1.'" type="text" name="professioneG1"  title="Inserire professione genitore 1" size="50" required></td>
            </tr>
            <tr>
                <td>Titolo</td>
                <td>
                    <select name="titoloG1">';
                    if($titoloG1=="Padre") echo '<option value="Padre" selected>Padre</option>';
                    else echo '<option value="Padre">Padre</option>';
                    if($titoloG1=="Madre") echo '<option value="Madre" selected>Madre</option>';
                    else echo '<option value="Madre">Madre</option>';
                    if($titoloG1=="Altro") echo '<option value="Altro" selected>Altro</option>';
                    else echo '<option value="Altro">Altro</option>';
                    echo'</select>
                </td>
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>Contatto Genitore 1</td>
        <td>
            <table>
                <tr>
                    <td>Numero di telefono</td>
                    <td><input value="'.$numeroG1.'" type ="tel" name="numeroG1" title="Inserire numero di telefono genitore 1" required/></td>
                </tr>
                <tr>
                    <td>Tipo numero di telefono</td>
                    <td>
                        <select name="tipotelG1">';
                        if($tipotelG1=="cellulare") echo '<option value="cellulare" selected>Cellulare</option>';
                        else echo '<option value="cellulare">Cellulare</option>';
                        if($tipotelG1=="fisso") echo '<option value="fisso" selected>Fisso</option>';
                        else echo '<option value="fisso">Fisso</option>';
                        echo'
                        </select>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>Credenziali genitore 1</td>
        <td>
            <table>
                <tr>
                    <td>Username</td>
                    <td><input value="'.$usernameG1.'" type="text" name="usernameG1"  title="Inserire username genitore 1" size="50" required></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input value="'.$passwordG1.'" type="text" name="pwdG1"  title="Inserire password genitore 1" size="50" required></td>
                </tr>
            </table>
        </td>
    </tr>';
echo<<<STAMPA
    <tr>
        <td>Inserimento genitore 2</td>
        <td><input name ="checkgen2" value="si" type="checkbox" id="checkGen2" onchange="toggle('checkGen2','gen2')" />Voglio inserire anche il secondo genitore</td>
    </tr>
    <tr>
        <td>Genitore 2</td>
        <td>
            <table class="gen2">
            <tr>
            <td>Genitore gia esistente</td>
            <td>
                <select name="selG2" class="gen2" disabled>
                <option value="no">NO</option>
STAMPA;
$queryGenitore = "SELECT * FROM Genitore; ";
$queryGenitore_res = pg_query($con,$queryGenitore);
if(!$queryGenitore_res){
    echo "Errore: ".pg_last_error($conn);
    exit; 
}
while($genitore = pg_fetch_assoc($queryGenitore_res)){
    echo '<option value="'.$genitore["cf"].'">'.$genitore["cf"].'</option>';
}
echo<<< STAMPA
                </select>
            </td>
        </tr>
            <tr>
                <td>Codice Fiscale</td>
                <td><input class="gen2" type="text" name="cfG2"  title="Inserire codice fiscale genitore 2" size="50" disabled></td>
            </tr>
            <tr>
                <td>Nome</td>
                <td><input class="gen2" type="text" name="nomeG2"  title="Inserire nome genitore 2" size="50" disabled></td>
            </tr>
            <tr>
                <td>Cognome</td>
                <td><input class="gen2" type="text" name="cognomeG2"  title="Inserire cognome genitore 2" size="50" disabled></td>
            </tr>
            <tr>
                <td>Professione</td>
                <td><input class="gen2" type="text" name="professioneG2"  title="Inserire professione genitore 2" size="50" disabled ></td>
            </tr>
            <tr>
                <td>Titolo</td>
                <td>
                    <select class="gen2" name="titoloG2" disabled>
                        <option value="Padre">Padre</option>
                        <option value="Madre">Madre</option>
                        <option value="Altro">Altro</option>
                    </select>
                </td>
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>Contatto Genitore 2</td>
        <td>
            <table class="gen2">
                <tr>
                    <td>Numero di telefono</td>
                    <td><input class="gen2" type="tel" name="numeroG2" title="Inserire numero di telefono genitore 2" disabled /></td>
                </tr>
                <tr>
                    <td>Tipo numero di telefono</td>
                    <td>
                        <select class="gen2" name="tipotelG2" disabled>
                            <option value="cellulare">Cellulare</option>
                            <option value="fisso">Fisso</option>
                        </select>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>Credenziali genitore 2</td>
        <td>
            <table>
                <tr>
                    <td>Username</td>
                    <td><input class="gen2" type="text" name="usernameG2"  title="Inserire username genitore 2" size="50" disabled></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input class="gen2" type="text" name="pwdG2"  title="Inserire password genitore 2" size="50" disabled></td>
                </tr>
            </table>
        </td>
    </tr>
STAMPA;
    }
    else{
echo<<< STAMPA
        <tr>
            <td>Inserimento genitore 2</td>
            <td><input name ="checkgen2" value="si" type="checkbox" id="checkGen2" onchange="toggle('checkGen2','gen2')" checked/>Voglio inserire anche il secondo genitore</td>
        </tr>
        <tr>
            <td>Genitore 2</td>
            <td>
                <table class="gen2">
                <tr>
                <td>Genitore gia esistente</td>
                <td>
                    <select name="selG2" class="gen2" disabled>
                    <option value="no">NO</option>
STAMPA;
    $queryGenitore = "SELECT * FROM Genitore; ";
    $queryGenitore_res = pg_query($con,$queryGenitore);
    if(!$queryGenitore_res){
        echo "Errore: ".pg_last_error($conn);
        exit; 
    }
    while($genitore = pg_fetch_assoc($queryGenitore_res)){
        if($genitore["cf"]==$cfG1) echo '<option value="'.$genitore["cf"].'" selected>'.$genitore["cf"].'</option>';
        else echo '<option value="'.$genitore["cf"].'">'.$genitore["cf"].'</option>';
    }
    echo'
                    </select>
                </td>
            </tr>
                <tr>
                    <td>Codice Fiscale</td>
                    <td><input value="'.$cfG1.'"class="gen2" type="text" name="cfG2"  title="Inserire codice fiscale genitore 2" size="50" disabled></td>
                </tr>
                <tr>
                    <td>Nome</td>
                    <td><input value="'.$nomeG1.'" class="gen2" type="text" name="nomeG2"  title="Inserire nome genitore 2" size="50" disabled></td>
                </tr>
                <tr>
                    <td>Cognome</td>
                    <td><input value="'.$cognomeG1.'" class="gen2" type="text" name="cognomeG2"  title="Inserire cognome genitore 2" size="50" disabled></td>
                </tr>
                <tr>
                    <td>Professione</td>
                    <td><input value="'.$professioneG1.'" class="gen2" type="text" name="professioneG2"  title="Inserire professione genitore 2" size="50" disabled ></td>
                </tr>
                <tr>
                    <td>Titolo</td>
                    <td>
                        <select class="gen2" name="titoloG2" disabled>
                            <option value="Padre" '.($titoloG1=="Padre")? 'selected':''.'>Padre</option>
                            <option value="Madre" '.($titoloG1=="Madre")? 'selected':''.'>Madre</option>
                            <option value="Altro" '.($titoloG1=="Altro")? 'selected':''.'>Altro</option>
                        </select>
                    </td>
                </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>Contatto Genitore 2</td>
            <td>
                <table class="gen2">
                    <tr>
                        <td>Numero di telefono</td>
                        <td><input value="'.$numeroG1.'" class="gen2" type="tel" name="numeroG2" title="Inserire numero di telefono genitore 2" disabled /></td>
                    </tr>
                    <tr>
                        <td>Tipo numero di telefono</td>
                        <td>
                            <select class="gen2" name="tipotelG2" disabled>
                                <option value="cellulare"'.($tipotelG1=="cellulare")? 'selected':''.'>Cellulare</option>
                                <option value="fisso" '.($tipotelG1=="fisso")? 'selected':''.'>Fisso</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>Credenziali genitore 2</td>
            <td>
                <table>
                    <tr>
                        <td>Username</td>
                        <td><input value="'.$usernameG1.'" class="gen2" type="text" name="usernameG2"  title="Inserire username genitore 2" size="50" disabled></td>
                    </tr>
                    <tr>
                        <td>Password</td>
                        <td><input value="'.$passwordG1.'" class="gen2" type="text" name="pwdG2"  title="Inserire password genitore 2" size="50" disabled></td>
                    </tr>
                </table>
            </td>
        </tr>
    ';
    }
}
echo<<< STAMPA
    <tr>
        <td><a href='index_alunno.php'>Torna alla pagina index per gli alunni.</td>
        <td><input type="reset" value="Cancella" style="font-size: 12px;"><input type="submit" value="Inserisci Alunno" style="margin-left: 10px; font-size: 12px"></td>
      </tr>
  </table>
</form>
<script>

function toggle(checkboxID, toggleID) {
     var checkbox = document.getElementById(checkboxID);
     var toggle = document.getElementsByClassName(toggleID);
     var i;
    for (i = 0; i < toggle.length; i++) {
        updateToggle = checkbox.checked ? toggle[i].disabled=false : toggle[i].disabled=true;
    }
}
</script>
STAMPA;
?>