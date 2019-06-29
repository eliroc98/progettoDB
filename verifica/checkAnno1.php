<?php
require_once("../comuni/utility.php");
function checkAnno(){
    if(isset($_GET['codScuola'])){
        $con = connect_DB();

        $codScuola = $_GET['codScuola'];
        
        $query= "SELECT * FROM Attivita WHERE SCUOLA='$codScuola' ORDER BY annoscolastico DESC";
        $result = pg_query($con, $query);
		$riga = pg_fetch_assoc($result);
		$annoScolastico=$riga['annoscolastico'];
		$annoC = date('Y');
		if(date('m') < 8)
			$annoScolasticoC = ($annoC - 1).'/'.$annoC;
		else
			$annoScolasticoC = ($annoC).'/'.($annoC + 1);

		if ($annoScolastico != $annoScolasticoC){	
			echo false;
			exit;
		} 
		echo true;
    }else{
		echo false;
	}
}
checkAnno();
?>
 
