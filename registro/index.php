<?php
require_once("../comuni/utility.php");
require_once("../comuni/header.php");
if(!checksession()){
echo<<<STAMPA
<div>Per accedere al registro, occorre fare il login.
</div>
STAMPA;
}
else{

echo '
<script src="http://code.jquery.com/jquery-1.6.4.min.js" type="text/javascript"></script>
<script>
function votiFiglio(){
    var chk = document.getElementById("selFiglio");
    var chkdValue = chk.options[chk.selectedIndex].value;
    $.get("/progetto/registro/set_alunno.php",{"figlio":chkdValue});

}
function chgFiglio(){
    var chk = document.getElementById("selFiglio");
    var chkdValue = chk.options[chk.selectedIndex].value;
    $.ajax({
        type: "GET",
        url: "/progetto/registro/set_alunno.php",
        datatype: "html",
        data: {"figlio":chkdValue},
        success: function(data) {
            $(\'#tbl tbody\').html(data);
          }
      });
      $("tr:even").css("class", "row0");
      $("tr:odd").css("class", "row1");
    

}
</script>
<body onload="votiFiglio()"><select name="figlio" id ="selFiglio" onchange="chgFiglio()">';
    $con = connect_DB();
    $usr = $_SESSION["cf"];
    $query = "SELECT alunno FROM Referente as R WHERE R.genitore = '$usr';";
    $query_res = pg_query($con,$query);
    if(!$query_res){
        echo 'Errore: '.pg_last_error($con);
        exit;
    }
    while($figlio = pg_fetch_assoc($query_res)["alunno"]){
        echo '<option value="'.$figlio.'">'.$figlio.'</option>';
    }
    echo '</select>';
echo<<<STAMPA
<table id ="tbl">
    <thead>
        <tr>
            <th>Materia</th>
            <th>Data</th>
            <th>Voto</th>
            <th>Tipo prova</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
</body>

STAMPA;
}

?>