<?php
require_once("../comuni/utility.php");
function insert(){
    if(isset($_POST['cf'])&& isset($_POST['codTipoPersonale'])){
        $con = connect_DB();

        $cfPersona = $_POST['cf'];
        $mansione = $_POST['mansione'];
        $codTipoPersonale = $_POST['codTipoPersonale'];
        
        $check_query1="SELECT * FROM Persona WHERE cf = '$cfPersona'";
        $check_query2="SELECT * FROM TipoPersonale WHERE codice = '$codTipoPersonale'";

        $check_result1 = pg_query($con,$check_query1);
        $check_result2 = pg_query($con,$check_query2);
        if(!$check_result1 || !$check_result2)
        {
            echo "Errore: ".pg_last_error($con)."<br><a href='index.php'>Torna alla pagina index per il personale.";
            exit;
        }
		
        $query= "UPDATE Personale SET mansione='$mansione', tipoPersonale='$codTipoPersonale' WHERE cf='$cfPersona';";
        $result = pg_query($con, $query);
        if($result==TRUE) echo "<div>Inserimento personale avvenuto con successo</div><br><a href='index.php'>Torna alla pagina index per il personale.";
        else echo "<div>'Inserimento personale fallito</div><br><a href='index.php'>Torna alla pagina index per lil personale.";
  
    }else{
		echo "dfg";
		echo $_POST['cf'];
	}
}
insert();
?>
