<?php
require_once("../comuni/utility.php");
function selectPersonale(){
    if(isset($_GET['scuola']) && isset($_GET['anno'])){
        $con = connect_DB();

        $scuola = $_GET['scuola'];
		$anno = $_GET['anno'];
		$anno=explode("/", $anno);
		$inizio=$anno[0].'-09-01';
		$fine=$anno[1].'-08-31';
        
        $query= "SELECT * FROM Impiego 
		JOIN Personale ON Impiego.personale=Personale.cf 
		JOIN Persona ON Persona.cf=Personale.cf 
		JOIN TipoPersonale ON TipoPersonale.codice=Personale.tipoPersonale 
		WHERE scuola='$scuola'";
        $result = pg_query($con, $query);
		$array=array();
		while($personale = pg_fetch_assoc($result)){
			if($personale['inizio']>$inizio || $personale['inizio']<$fine){
				if($personale['mansione']==null) $personale['mansione']="";
				$array[]=$personale;
			}
		}
		echo json_encode($array);
  
    }else{
		echo false;
	}
}
selectPersonale();
?>
 
