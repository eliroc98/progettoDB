<?php
require_once("../comuni/utility.php");
function insert_scuola(){
    if(isset($_POST['codice'])&&isset($_POST['nome'])&&isset($_POST['indirizzo'])){
        $con = connect_DB();

        $codice = $_POST['codice'];
        $nome = $_POST['nome'];
        $indirizzo = $_POST['indirizzo'];
        $anno = isset($_POST['anno'])?$_POST['anno']:NULL;
        $infanzia = isset($_POST['infanzia'])?TRUE:FALSE;
        $elementare = isset($_POST['elementare'])?TRUE:FALSE;
        $media = isset($_POST['media'])?TRUE:FALSE;
        $check_query1="SELECT * FROM Scuola WHERE codice = '$codice'";
        $check_query2="SELECT * FROM Scuola WHERE nome = '$nome' AND indirizzo = '$indirizzo'";

        $check_result1 = pg_query($con,$check_query1);
        $check_result2 = pg_query($con,$check_query2);
        if(!$check_result1)
        {
            echo "Errore: ".pg_last_error($con)."<br><a href='index_scuola.php'>Torna alla pagina index per le scuole.";
            exit;
        }
        if(!$check_result2)
        {
            echo "Errore: ".pg_last_error($con);
            exit;
        }
        if($media&&!isset($_POST['anno'])){
            echo "Errore: inserire anno di fondazione<br><a href='index_scuola.php'>Torna alla pagina index per le scuole.";
            exit;
        }
        $query=null;
        if($infanzia)
        {
            $codR=isset($_POST['codR'])?$_POST['codR']:NULL;
            $annoR=isset($_POST['annoR'])?$_POST['annoR']:NULL;
            $tipoR=isset($_POST['tipoR'])?$_POST['tipoR']:NULL;
            $check_query3="SELECT * FROM Ristrutturazione WHERE codice = '$codR'";
            $check_result3 = pg_query($con,$check_query3);
            if(!$check_result3||$codR==NULL)
            {
                echo "Errore: ".pg_last_error($con)."<br><a href='index_scuola.php'>Torna alla pagina index per le scuole.";
                exit;
            }
            $query.="INSERT INTO Ristrutturazione VALUES('$codR','$codice','$annoR','$tipoR');";
        }
        if($infanzia)
        {
            $query.= "INSERT INTO TipoOspitato VALUES('$codice','1');";
        }
        if($elementare)
        {
            $query.= "INSERT INTO TipoOspitato VALUES('$codice','2');";
        }
        if($media)
        {
            $query1.= "INSERT INTO TipoOspitato VALUES('$codice','3');";
        }
        $query.= "INSERT INTO Scuola VALUES('$codice','$nome','$indirizzo','$anno');";
        $result = pg_query($con, $query);
        if($result==TRUE) echo "<div>Inserimento scuola avvenuto con successo</div><br><a href='index_scuola.php'>Torna alla pagina index per le scuole.";
        else echo "<div>'Inserimento scuola fallito</div><br><a href='index_scuola.php'>Torna alla pagina index per le scuole.";
  
    }
    else echo "<div>'Inserimento scuola fallito</div><br><a href='index_scuola.php'>Torna alla pagina index per le scuole.";
}
insert_scuola();
?>
