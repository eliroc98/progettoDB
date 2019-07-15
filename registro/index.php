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
<script>
function votiFiglio(){
    var chk = document.getElementById("selFiglio");   
    var chkdValue = chk.options[chk.selectedIndex].value; 
    $.ajax({
        type: "GET",
        url: "/progetto/registro/get_anno.php",
        datatype: "html",
        data: {"figlio":chkdValue},
        success: function(data) {
            $(\'#selAnno\').html(data);
          }
      });
    $.ajax({
        type: "GET",
        url: "/progetto/registro/set_alunno.php",
        datatype: "html",
        data: {"figlio":chkdValue,"anno":"no"},
        success: function(data) {
            $(\'#tbl tbody\').html(data);
          }
      });
 

}
function chg(){
    var chk = document.getElementById("selFiglio");
    var chkdValue = chk.options[chk.selectedIndex].value;
    var chkAnno = document.getElementById("selAnno");
    var chkdValueAnno = chkAnno.options[chkAnno.selectedIndex].value;
    $.ajax({
        type: "GET",
        url: "/progetto/registro/set_alunno.php",
        datatype: "html",
        data: {"figlio":chkdValue, "anno":chkdValueAnno},
        success: function(data) {
            $(\'#tbl tbody\').html(data);
          }
      });
      $("tr:even").css("class", "row0");
      $("tr:odd").css("class", "row1");
    

}
</script>
<body onload="votiFiglio()"><select name="figlio" id ="selFiglio" onchange="chg()">';
    $con = connect_DB();
    $usr = $_SESSION["cf"];
    $query = "SELECT alunno FROM Referente as R WHERE R.genitore = '$usr';";
    $query_res = pg_query($con,$query);
    if(!$query_res){
        echo 'Errore: '.pg_last_error($con);
        exit;
    }
    while($figlio = pg_fetch_assoc($query_res)["alunno"]){
        echo '<option>'.$figlio.'</option>';
    }
    echo '</select>
    <select name="anno" id ="selAnno" onchange="chg()">';
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