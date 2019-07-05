<?php
require_once("../comuni/utility.php");
function selectProve(){
    if(isset($_GET['alunno'])){
        $con = connect_DB();

        $alunno = $_GET['alunno'];
		
        $query= "SELECT * FROM Verifica WHERE alunno='$alunno'";
        $result = pg_query($con, $query);
		$array=array();
		while($prove= pg_fetch_assoc($result)){
			$key=$prove['data'];
			$value=$prove['voto'] . ", " . $prove['data'] . ", " .$prove['tipo_prova'];
			$array[$key] = $value;
		}
		echo json_encode($array);
  
    }else{
		return false;
	}
}
selectProve();
?>