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
        <td>Tipi ospitati</td>
        <table>
            <tr><td><input type="checkbox" name="infanzia" title="Infanzia">Infanzia</td></tr>
            <tr><td><input type="checkbox" name="elementare" title="Elementare">Elementare</td></tr>
            <tr><td><input type="checkbox" name="media" title="Media">Media</td></tr>
        </table>
    </tr>
    <tr>
        <td><a href='index_scuola.php'>Torna alla pagina index per le scuole.</td>
        <td><input type="reset" value="Cancella" style="font-size: 12px;"><input type="submit" value="Inserisci scuola" style="margin-left: 10px; font-size: 12px"></td>
      </tr>
  </table>
</form>