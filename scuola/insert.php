
<form action="insert1.php" method="POST">
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
    <td>Contatto Scuola</td>
    <td>
        <table>
            <tr>
                <td>Numero di telefono</td>
                <td><input type ="tel" name="numero" title="Inserire numero di telefono scuola" required/></td>
            </tr>
            <tr>
                <td>Tipo numero di telefono</td>
                <td>
                    <select name="tipotel">';
                    <option value="cellulare">Cellulare</option>;
                    <option value="fisso">Fisso</option>;
                    </select>
                </td>
            </tr>
        </table>
    </td>
    <tr>
        <td>Tipi ospitati</td>
        <table>
            <tr><td><input type="checkbox" name="infanzia" id="infanzia" title="Infanzia" onClick="toggle('infanzia', 'ristrutturazione')"">Infanzia</td></tr>
            <tr><td><input type="checkbox" name="elementare" title="Elementare">Elementare</td></tr>
            <tr><td><input type="checkbox" id="media" name="media" title="Media" onClick="toggle('media', 'anno')"">Media</td></tr>
        </table>
    </tr>
    <tr>
        <td>Anno fondazione</td>
        <td><input type="number" name="anno" id="anno" class="anno" min="1900" max="2019" title="Inserire anno fondazione scuola" disabled="true"></td>
    </tr>
    <tr>
        
        <table>
        <tr><td>Ultima ristrutturazione</td></tr>
            <tr>
            <td>Codice</td>
            <td><input type="text" name="codiceR" title="Inserire codice ultima ristrutturazione" class="ristrutturazione" disabled="true"></td></tr>
            <tr><td>Anno</td>
            <td><input type="number" name="annoR" title="Inserire anno dell'ultima ristrutturazione" class="ristrutturazione" disabled="true"></td></tr>
            <tr><td>Tipo</td>
            <td><input type="text" name="tipoR" title="Inserire tipo dell'ultima ristrutturazione" class="ristrutturazione" disabled="true"></td></tr>
        </table>
    </tr>
    <tr>
        <td><a href='index.php'>Torna alla pagina index per le scuole.</td>
        <td><input type="reset" value="Cancella" style="font-size: 12px;"><input type="submit" value="Inserisci scuola" style="margin-left: 10px; font-size: 12px"></td>
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