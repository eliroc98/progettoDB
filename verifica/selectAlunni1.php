<?php
require_once("../comuni/utility.php");
function selectMateria(){
    if(isset($_GET['codClasse'])){
        $con = connect_DB();

        $codClasse = $_GET['codClasse'];
		
        $query= "SELECT persona.cf, nome, cognome FROM Frequentazione JOIN Alunno ON Frequentazione.alunno = Alunno.cf JOIN persona ON alunno.cf=persona.cf WHERE Frequentazione.classe='$codClasse';";
        $result = pg_query($con, $query);
		$array=array();
		while($alunni= pg_fetch_assoc($result)){
			$key=$alunni['cf'];
			$value=$alunni['cognome']." ".$alunni['nome'];
			$array[$key] = $value;
		}
		echo json_encode($array);
  
    }else{
		return false;
	}
}
selectMateria();
?>