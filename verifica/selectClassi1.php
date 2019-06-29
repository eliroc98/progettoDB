<?php
require_once("../comuni/utility.php");
function selectClasse(){
    if(isset($_GET['codScuola'])){
        $con = connect_DB();

        $codScuola = $_GET['codScuola'];
        $annoC = date('Y');
		if(date('m') < 8)
			$annoScolastico = ($annoC - 1).'/'.$annoC;
		else
			$annoScolastico = ($annoC).'/'.($annoC + 1);

        $query= "SELECT * FROM Classe WHERE SCUOLA='$codScuola' AND annoscolastico='$annoScolastico'";
        $result = pg_query($con, $query);
		$array=array();
		while($classi = pg_fetch_assoc($result)){
			$key=$classi['codice'];
			if($classi['livello']==0) $classi['livello']="";
			$value=$classi['livello'].$classi['nome'];
			$array[$key] = $value;
		}
		echo json_encode($array);
  
    }else{
		return false;
	}
}
selectClasse();
?>