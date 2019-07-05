<?php
require_once("../comuni/utility.php");
function selectMateria(){
    if(isset($_GET['codClasse'])){
        $con = connect_DB();

        $codClasse = $_GET['codClasse'];
		
        $query= "SELECT * FROM Docenza WHERE classe='$codClasse'";
        $result = pg_query($con, $query);
		$array=array();
		while($materie= pg_fetch_assoc($result)){
			$key=$materie['materia'];
			$value=$materie['materia'];
			$array[$key] = $value;
		}
		echo json_encode($array);
  
    }else{
		return false;
	}
}
selectMateria();
?>