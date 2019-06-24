<?php
require_once("../comuni/utility.php");
function edit_scuola(){
    if(isset($_POST['codice'])&&isset($_POST['nome'])&&isset($_POST['indirizzo'])){
        $con = connect_DB();

        $codice = $_POST['codice'];
        $nome = $_POST['nome'];
        $indirizzo = $_POST['indirizzo'];
        $anno = isset($_POST['anno'])?$_POST['anno']:NULL;
        $infanzia = isset($_POST['infanzia'])?TRUE:FALSE;
        $elementare = isset($_POST['elementare'])?TRUE:FALSE;
        $media = isset($_POST['media'])?TRUE:FALSE;

        $check_query2="SELECT * FROM Scuola WHERE nome = '$nome' AND indirizzo = '$indirizzo'";
        $check_result2 = pg_query($con,$check_query2);
        if(!$check_result2)
        {
            echo "Errore: ".pg_last_error($con);
            exit;
        }

        #VERIFICA DEI TIPI DI SCUOLA GIA OSPITATI 
        $query1 = "SELECT tipo_scuola AS tipo FROM TipoOspitato  WHERE scuola = '$codice' ";
        $query_res1 = pg_query($con,$query1);
        if(!$query_res1){
            echo "Errore: ".pg_last_error($conn);
            exit;
        }
        $infanzia1 = FALSE;
        $elementare1 = FALSE;
        $media1 = FALSE;
        while ($tipo = pg_fetch_assoc($query_res1)){
            if($tipo["tipo"]=='1') $infanzia1=TRUE;
            if($tipo["tipo"]=='2') $elementare1=TRUE;
            if($tipo["tipo"]=='3') $media1 =TRUE;
        }
        #GESTIONE DELLA RISTRUTTURAZIONE
        $query=null;
        if($infanzia){
            $codR=isset($_POST['codR'])?$_POST['codR']:NULL;
            $annoR=isset($_POST['annoR'])?$_POST['annoR']:NULL;
            $tipoR=isset($_POST['tipoR'])?$_POST['tipoR']:NULL;
            $check_query3="SELECT * FROM Ristrutturazione WHERE codice = '$codR'";
            $check_result3 = pg_query($con,$check_query3);
            if(!$check_result3){
                echo "Errore: ".pg_last_error($conn);
                exit;
            }
            if(pg_num_rows($check_result3)==0)
            {
                $query.="INSERT INTO Ristrutturazione VALUES('$codR','$codice','$annoR','$tipoR');";
            }
            else
            {
                $query.="UPDATE Ristrutturazione SET scuola='$codice',anno='$annoR',tipo='$tipoR' WHERE codice='$codR';";
            }
            if($infanzia1==FALSE)
            {
                $query.= "INSERT INTO TipoOspitato VALUES('$codice','1');";
                
            }
        }
        else{
            $check_query3="SELECT * FROM Ristrutturazione WHERE scuola = '$codice';";
            $check_result3 = pg_query($con,$check_query3);
            if(!$check_result3){
                echo "Errore: ".pg_last_error($conn);
                exit;
            }
            if(pg_num_rows($check_result3)>0)
            {
                $query .= "DELETE FROM Ristrutturazione WHERE scuola = '$codice';";
            }
        }
        if($elementare1==FALSE && $elementare == TRUE )
        {
            $query.= "INSERT INTO TipoOspitato VALUES('$codice','2');";
        }

        if($media1==FALSE && $media == TRUE )
        {
            $query .= "INSERT INTO TipoOspitato VALUES('$codice','3');";
        }

        if($infanzia1==TRUE && $infanzia == FALSE )
        {
            $query .= "DELETE FROM TipoOspitato WHERE scuola ='$codice' AND tipo_scuola='1';";
        }
        
        if($elementare1==TRUE && $elementare == FALSE )
        {
            $query.= "DELETE FROM TipoOspitato WHERE scuola ='$codice' AND tipo_scuola='2';";
        }

        if($media1==TRUE && $media == FALSE )
        {
            $query.= "DELETE FROM TipoOspitato WHERE scuola ='$codice' AND tipo_scuola='3';";
            $query.= "UPDATE Scuola SET nome = '$nome', indirizzo='$indirizzo', annofondazione = NULL WHERE codice = '$codice';";
        }

        $query .= "UPDATE Scuola SET nome = '$nome', indirizzo='$indirizzo', annofondazione = '$anno' WHERE codice = '$codice';";
        $result = pg_query($con, $query);

        if($result==TRUE) echo "<div>Modifica scuola avvenuta con successo</div><br><a href='index_scuola.php'>Torna alla pagina index per le scuole.";
        else echo "<div>'Modifica scuola fallita</div><br><a href='index_scuola.php'>Torna alla pagina index per le scuole.";
    }
    else echo "<div>'Modifica scuola fallita</div><br><a href='index_scuola.php'>Torna alla pagina index per le scuole.";
}
edit_scuola();
?>