<?php
require_once("../comuni/utility.php");
function insert(){
    if(isset($_POST['alunno'])
	&& isset($_POST['materia'])
	&& isset($_POST['data'])
	&& isset($_POST['vecchiaData'])
	&& isset($_POST['voto'])
	&& isset($_POST['tipo_prova'])){
		
        $con = connect_DB();

        $alunno = $_POST['alunno'];
		$materia = $_POST['materia'];
		$data = $_POST['data'];
		$vecchiaData = $_POST['vecchiaData'];
		$voto = $_POST['voto'];
		$tipo_prova = $_POST['tipo_prova'];
		
		
		$queryVerifica= "UPDATE Verifica SET data='$data', voto='$voto', tipo_prova='$tipo_prova' WHERE alunno='$alunno' AND materia='$materia' AND data='$vecchiaData';";
        $updateVerifica = pg_query($con, $queryVerifica);
		if($updateVerifica){
			echo "<div>Modifica voto verifica avvenuto con successo</div><br><a href='index.php'>Torna alla pagina index per i voti.</a>";
			exit;
		}
		echo "<div>Modifica voto fallito</div><br><a href='index.php'>Torna alla pagina index per i voti.";
  
    }else{
		echo "Errore, dati mancanti <a href='index.php'>Torna alla pagina index per i voti.</a>";
	}

		
		
}
insert();
?>
