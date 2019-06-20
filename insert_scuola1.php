<?php
require_once("comuni/utility.php");
function insert_scuola(){
    if(isset($_POST['codice'])&&isset($_POST['nome'])&&isset($_POST['indirizzo'])){
        $con = connect_DB();

        $codice = $_POST['codice'];
        $nome = $_POST['nome'];
        $indirizzo = $_POST['indirizzo'];
        $anno = isset($_POST['anno'])?$_POST['anno']:NULL;

        $check_query1="SELECT * FROM Scuola WHERE codice = '$codice'";
        $check_query2="SELECT FROM Scuola WHERE nome = '$nome' AND indirizzo = '$indirizzo'";

        $check_result1 = pg_query($con,$check_query1);
        $check_result2 = pg_query($con,$check_query2);
        if(!$check_result1)
        {
            echo "Errore: ".pg_last_error($con);
            exit;
        }
        if(!$check_result2)
        {
            echo "Errore: ".pg_last_error($con);
            exit;
        }

        $query = "INSERT INTO Scuola VALUES('$codice','$nome','$indirizzo','$anno')";
        $result = pg_query($con, $query);
        if($result==TRUE)
        {
            echo "window.alert('Inserimento scuola avvenuto con successo')";
        }
        else echo "window.alert('Inserimento scuola fallito')";
    }
}
insert_scuola();
?>