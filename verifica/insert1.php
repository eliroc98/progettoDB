<?php
require_once("../comuni/utility.php");
function insert(){
    if(
		isset($_POST['scuola'])&&
		isset($_POST['classe'])&& 
		isset($_POST['materia'])&& 		
		isset($_POST['alunno']) && 
		isset($_POST['voto']) && 
		isset($_POST['tipo_prova']) && 
		isset($_POST['data'])){
			
        $con = connect_DB();
			
		$materia= $_POST['materia'];	
        $alunno= $_POST['alunno'];
		$voto= $_POST['voto'];
		$tipo_prova= $_POST['tipo_prova'];
        $data= $_POST['data'];
		
		
		$queryVerifica= "INSERT INTO Verifica VALUES ('$alunno', '$materia', '$data', '$voto', '$tipo_prova');";
        $insertVerifica = pg_query($con, $queryVerifica);
		if($insertVerifica){
			echo "<div>Inserimento voto verifica avvenuto con successo</div><br><a href='index.php'>Torna alla pagina index per i voti.</a>";
			exit;
		}
		echo "<div>Inserimento voto fallito</div><br><a href='index.php'>Torna alla pagina index per i voti.";
  
    }else{
		echo "Errore, dati mancanti <a href='index.php'>Torna alla pagina index per i voti.</a>";
	}
}
insert();
?>
