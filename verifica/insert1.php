<?php
require_once("../comuni/utility.php");
function insert(){
    if(
		isset($_POST['cf'])&&
		isset($_POST['cognome'])&& 
		isset($_POST['nome'])&& 		
		isset($_POST['codTipoPersonale']) && 
		isset($_POST['codScuola']) && 
		isset($_POST['dataInizio']) && 
		isset($_POST['dataFine'])){
			
        $con = connect_DB();

        $cf= strtoupper($_POST['cf']);
		$nome= $_POST['nome'];
		$cognome= $_POST['cognome'];
        $mansione= $_POST['mansione'];
        $codTipoPersonale = $_POST['codTipoPersonale'];
		$codScuola= $_POST['codScuola'];
		$dataInizio= $_POST['dataInizio'];
		$dataFine= $_POST['dataFine'];
        
        $check_query1="SELECT * FROM TipoPersonale WHERE codice = '$codTipoPersonale'";
		$check_query2="SELECT * FROM Scuola WHERE codice = '$codScuola'";

        $check_result1 = pg_query($con,$check_query1);
		$check_result2 = pg_query($con,$check_query2);
        if(!$check_result1 || !$check_result2)
        {
            echo "Errore: ".pg_last_error($con)."<br><a href='index.php'>Torna alla pagina index per il personale.";
            exit;
        }
		
		$queryPersona= "INSERT INTO Persona VALUES('$cf','$nome','$cognome');";
        $insertPersona = pg_query($con, $queryPersona);
		if($insertPersona){
			$queryPersonale= "INSERT INTO Personale VALUES('$cf','$mansione','$codTipoPersonale');";
			$insertPersonale = pg_query($con, $queryPersonale);
			if($insertPersonale){
				$queryImpiego= "INSERT INTO Impiego VALUES('$cf','$dataInizio','$dataFine', '$codScuola');";
				$insertImpiego = pg_query($con, $queryImpiego);
				if($insertImpiego) {
					echo "<div>Inserimento personale avvenuto con successo</div><br><a href='index.php'>Torna alla pagina index per il personale.</a>";
					exit;
				}
					
			}
		}
		echo "<div>Inserimento personale fallito</div><br><a href='index.php'>Torna alla pagina index per lil personale.";
  
    }else{
		echo "Errore, dati mancanti<a href='index.php'>Torna alla pagina index per il personale.</a>";
	}
}
insert();
?>
