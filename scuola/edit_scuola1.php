<?php
require_once("../comuni/utility.php");
function edit_scuola(){
    if(isset($_POST['codice'])&&isset($_POST['nome'])&&isset($_POST['indirizzo'])){
        $con = connect_DB();

        $codice = $_POST['codice'];
        $nome = $_POST['nome'];
        $indirizzo = $_POST['indirizzo'];
        $anno = isset($_POST['anno'])?$_POST['anno']:NULL;
        $infanzia = ($_POST['infanzia'])?TRUE:FALSE;
        $elementare = isset($_POST['elementare'])?TRUE:FALSE;
        $media = isset($_POST['media'])?TRUE:FALSE;

        $check_query2="SELECT * FROM Scuola WHERE nome = '$nome' AND indirizzo = '$indirizzo'";
        $check_result2 = pg_query($con,$check_query2);
        if(!$check_result2)
        {
            echo "Errore: ".pg_last_error($con);
            exit;
        }


        $query = "UPDATE Scuola SET nome = '$nome', indirizzo='$indirizzo', annofondazione = '$anno' WHERE codice = '$codice'";
        $result = pg_query($con, $query);
        if($result==TRUE)
        {
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
            #SISTEMA QUA: CAPIRE COME FUNZIONA L'UPDATE E INSERT DI RISTRUTTURAZIONE
            if($infanzia){
                $codR=isset($_POST['codR'])?$_POST['codR']:NULL;
                $annoR=isset($_POST['annoR'])?$_POST['annoR']:NULL;
                $tipoR=isset($_POST['tipoR'])?$_POST['tipoR']:NULL;
                $check_query3="SELECT * FROM Ristrutturazione WHERE codice = '$codR'";
                $check_result3 = pg_query($con,$check_query3);
                echo  $check_result3 ;
                if(!$check_result3)
                    {
                        $queryR="INSERT INTO Ristrutturazione VALUES('$codR','$codice','$annoR','$tipoR')";
                        echo 'insert';
                        $resultR = pg_query($con, $queryR);
                        if($resultR=FALSE)
                        {
                            echo "Errore: ".pg_last_error($con)."<br><a href='index_scuola.php'>Torna alla pagina index per le scuole.";
                            exit;
                        }
                    }
                else{
                        $queryR="UPDATE Ristrutturazione SET scuola='$codice',anno='$annoR',tipo='$tipoR' WHERE codice='$codR'";
                        echo 'update';
                        $resultR = pg_query($con, $queryR);
                        if($resultR=FALSE)
                        {
                            echo "Errore: ".pg_last_error($con)."<br><a href='index_scuola.php'>Torna alla pagina index per le scuole.";
                            exit;
                        }
                    }
                if($infanzia1==FALSE)
                {
                    $query1 = "INSERT INTO TipoOspitato VALUES('$codice','1')";
                    pg_query($con,$query1);
                    
                }
                
            }
            
            
            if($elementare1==FALSE && $elementare == TRUE )
            {
                $query1 = "INSERT INTO TipoOspitato VALUES('$codice','2')";
                pg_query($con,$query1);
            }

            if($media1==FALSE && $media == TRUE )
            {
                $query1 = "INSERT INTO TipoOspitato VALUES('$codice','3')";
                pg_query($con,$query1);
            }
            if($infanzia1==TRUE && $infanzia == FALSE )
            {
                $query1 = "DELETE FROM TipoOspitato WHERE scuola ='$codice' AND tipo_scuola='1'";
                pg_query($con,$query1);
            }
            
            if($elementare1==TRUE && $elementare == FALSE )
            {
                $query1 = "DELETE FROM TipoOspitato WHERE scuola ='$codice' AND tipo_scuola='2'";
                pg_query($con,$query1);
            }

            if($media1==TRUE && $media == FALSE )
            {
                $query1 = "DELETE FROM TipoOspitato WHERE scuola ='$codice' AND tipo_scuola='3'";
                $queryM = "UPDATE Scuola SET nome = '$nome', indirizzo='$indirizzo', annofondazione = NULL WHERE codice = '$codice'";
                pg_query($con,$query1);
                pg_query($con,$queryM);
            }

            echo "<div>Modifica scuola avvenuta con successo</div><br><a href='index_scuola.php'>Torna alla pagina index per le scuole.";
        }
        else echo "<div>'Modifica scuola fallita</div><br><a href='index_scuola.php'>Torna alla pagina index per le scuole.";
        
       
        

    }
}
edit_scuola();
?>