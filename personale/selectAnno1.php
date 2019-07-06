<?php
require_once("../comuni/utility.php");
function selectAnno(){
    if(isset($_GET['codScuola'])){
        $con = connect_DB();

        $codScuola = $_GET['codScuola'];
        
        $query= "SELECT * FROM Attivita WHERE SCUOLA='$codScuola'";
        $result = pg_query($con, $query);
		$array=array();
		while($annoScolastico = pg_fetch_assoc($result)){
			array_push($array, $annoScolastico['annoscolastico']);
		}
		echo json_encode($array);
  
    }else{
		echo false;
	}
}
selectAnno();
?>
 
