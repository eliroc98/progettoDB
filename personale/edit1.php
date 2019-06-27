<?php
require_once("../comuni/utility.php");
function insert(){
    if(isset($_POST['cf'])
	&& isset($_POST['cognome'])
	&& isset($_POST['nome'])
	&& isset($_POST['codTipoPersonale'])){
		
        $con = connect_DB();

        $cfPersona = $_POST['cf'];
		$cfVecchioPersona = $_POST['vecchioCF'];
		
        $mansione = $_POST['mansione'];
        $codTipoPersonale = $_POST['codTipoPersonale'];
		$nome=$_POST['nome'];
		$cognome=$_POST['cognome'];
        
        $check_query1="SELECT * FROM Persona WHERE cf = '$cfVecchioPersona'";
        $check_query2="SELECT * FROM TipoPersonale WHERE codice = '$codTipoPersonale'";

        $check_result1 = pg_query($con,$check_query1);
        $check_result2 = pg_query($con,$check_query2);
        if(!$check_result1 || !$check_result2)
        {
            echo "Errore: ".pg_last_error($con)."<br><a href='index.php'>Torna alla pagina index per il personale.";
            exit;
        }
		
		$query0= "UPDATE Persona SET cf='$cfPersona', nome='$nome', cognome='$cognome' WHERE cf='$cfVecchioPersona';";
        $result0 = pg_query($con, $query0);
		if($result0){
			$query1= "UPDATE Personale SET mansione='$mansione', tipoPersonale='$codTipoPersonale' WHERE cf='$cfPersona';";
			$result1 = pg_query($con, $query1);
			if($result1){
				if(isset($_POST['codScuola'])
				&& isset($_POST['dataInizio'])
				&& isset($_POST['dataFine'])){
					$inizioScuola=$_POST['inizioScuolaImpiego'];
					$dataInizio=$_POST['dataInizio'];
					$codScuola=$_POST['codScuola'];	
					$dataFine=$_POST['dataFine'];
					$varImpiego = explode(" ", $inizioScuola);
					$query2= "UPDATE Impiego SET personale='$cfPersona', inizio='$dataInizio', fine='$dataFine',scuola='$codScuola'
					WHERE personale='$cfPersona' AND inizio='$varImpiego[0]' AND scuola='$varImpiego[1]';";
					$result2 = pg_query($con, $query2);
					if(!$result2) echo "<div>'Modifica personale fallito</div><br><a href='index.php'>Torna alla pagina index per lil personale.";
				}
				echo "<div>Modifica personale avvenuto con successo</div><br><a href='index.php'>Torna alla pagina index per il personale.";
				exit;
			}
		}
		echo "<div>'Modifica personale fallito</div><br><a href='index.php'>Torna alla pagina index per lil personale.";
		
    }else{
		echo "<div>Mancano dati</div><br><a href='index.php'>Torna alla pagina index per lil personale.";
	}
}
insert();
?>
