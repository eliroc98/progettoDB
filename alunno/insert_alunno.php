<?php

require_once("../comuni/utility.php");

$con = connect_DB();

$queryScuole = "SELECT * FROM Scuola;";
$queryScuole_res = pg_query($con,$queryScuole);
if(!$queryScuole_res){
    echo "Errore: ".pg_last_error($conn);
    exit; 
}
echo <<<STAMPA
<form action="insert_alunno1.php" method="POST">
  <table>
    <tr>
        <td>Codice Fiscale</td>
        <td><input type="text" name="cf"  title="Inserire codice fiscale" size="16" required></td>
    </tr>
    <tr>
        <td>Nome</td>
        <td><input type="text" name="nome"  title="Inserire nome alunno" size="50" required></td>
    </tr>
    <tr>
        <td>Cognome</td>
        <td><input type="text" name="cognome"  title="Inserire cognome alunno" size="50" required></td>
    </tr>
    <tr>
        <td>Indirizzo</td>
        <td><input type="text" name="indirizzo"  title="Inserire indirizzo" size="50" required></td>
    </tr>
    <tr>
        <td>Data di nascita</td>
        <td><input type="date" name="datadinascita"  title="Inserire data di nascita" required></td>
    </tr>
    <tr>
        <td>Data di iscrizione</td>
        <td><input type="date" name="datadiiscrizione"  title="Inserire data di iscrizione" required></td>
    </tr>
    <tr>
        <td>Zona di residenza</td>
        <td><input type="text" name="zona"  title="Inserire zona di residenza alunno" size="50" required></td>
    </tr>
    <tr>
        <td>Scuola e classe frequentata</td>
        <td><select name="scuola" id="selectScuolaClasse" >
STAMPA;
while($scuola=pg_fetch_assoc($queryScuole_res)){
   
    $codScuola = $scuola["codice"];
    $queryClasse = "SELECT * FROM Classe WHERE scuola ='$codScuola'; ";
    $queryClasse_res = pg_query($con,$queryClasse);
    if(!$queryClasse){
        echo "Errore: ".pg_last_error($conn);
        exit; 
    }
    while($classe = pg_fetch_assoc($queryClasse_res)){
        echo '<option value="'.$classe["codice"].'">'.$classe["codice"].'</option>';
    }
}
echo<<< STAMPA
        </select></td>
    </tr>
    <tr>
        <td>Usufruisce di opzioni pre-scuola</td>
        <td><input type="checkbox" name="pre"></td>
    </tr>
    <tr>
        <td>Usufruisce di opzioni post-scuola</td>
        <td><input type="checkbox" name="post"></td>
    </tr>
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
        echo '<option value="'.$genitore["cf"].'">'.$genitore["cf"].'</option>';
    }
echo<<< STAMPA
                    </select>
                </td>
            </tr>
            <tr>
                <td>Codice Fiscale</td>
                <td><input type="text" name="cfG1"  title="Inserire codice fiscale genitore 1" size="50" ></td>
            </tr>
            <tr>
                <td>Nome</td>
                <td><input type="text" name="nomeG1"  title="Inserire nome genitore 1" size="50" ></td>
            </tr>
            <tr>
                <td>Cognome</td>
                <td><input type="text" name="cognomeG1"  title="Inserire cognome genitore 1" size="50" ></td>
            </tr>
            <tr>
                <td>Professione</td>
                <td><input type="text" name="professioneG1"  title="Inserire professione genitore 1" size="50" ></td>
            </tr>
            <tr>
                <td>Titolo</td>
                <td>
                    <select name="titoloG1">
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
        <td>Contatto Genitore 1</td>
        <td>
            <table>
                <tr>
                    <td>Numero di telefono</td>
                    <td><input type="tel" name="numeroG1" title="Inserire numero di telefono genitore 1" /></td>
                </tr>
                <tr>
                    <td>Tipo numero di telefono</td>
                    <td>
                        <select name="tipotelG1">
                            <option value="cellulare">Cellulare</option>
                            <option value="fisso">Fisso</option>
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
                    <td><input type="text" name="usernameG1"  title="Inserire username genitore 1" size="50" ></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type="text" name="pwdG1"  title="Inserire password genitore 1" size="50" ></td>
                </tr>
            </table>
        </td>
    </tr>
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