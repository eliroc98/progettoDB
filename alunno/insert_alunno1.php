<?php
require_once("../comuni/utility.php");
function insert_alunno(){
    if(isset($_POST['cf'])){
        $con = connect_DB();

        $cf=$_POST['cf'];
        $nome =isset($_POST['nome'])?$_POST['nome']:NULL;
        $cognome =isset($_POST['cognome'])?$_POST['cognome']:NULL;
        $indirizzo =isset($_POST['indirizzo'])?$_POST['indirizzo']:NULL;
        $datadinascita =isset($_POST['datadinascita'])?$_POST['datadinascita']:NULL;
        $zona =isset($_POST['zona'])?$_POST['zona']:NULL;
        //c'è anche la classe frequentata e l'anno scolastico
        $scuola =isset($_POST['scuola'])?$_POST['scuola']:NULL;
        $inizio = isset($_POST['datadiiscrizione'])?$_POST['datadiiscrizione']:NULL;
        $pre =isset($_POST['pre'])?$_POST['pre']:NULL;
        $post =isset($_POST['post'])?$_POST['post']:NULL;

        $query="INSERT INTO Persona VALUES('$cf','$nome','$cognome');";
        $query.="INSERT INTO Alunno VALUES('$cf','$indirizzo','$datadinascita','$zona',null,null);";
        $query.="INSERT INTO Frequentazione VALUES('$scuola','$cf','$inizio',null);";

        $queryTipo = "SELECT * FROM Classe WHERE codice = '$scuola';";
        $queryTipo_res = pg_query($con,$queryTipo);
        if(!$queryTipo_res)
        {
            echo "Errore: ".pg_last_error($con)."<br><a href='index_alunno.php'>Torna alla pagina index per gli alunni.";
            exit;
        }
        if(pg_num_rows($queryTipo_res)>0){
            $tipo = (pg_fetch_assoc($queryTipo_res))["tiposcuola"];
            if($tipo=='1'){
                $query.="UPDATE Alunno SET pre = '$pre', post='$post' WHERE cf = '$cf';";
            }
        }

        $gen1 =isset($_POST['selG1'])?$_POST['selG1']:NULL;
        if($gen1=='no')
        {
            $cfG1 =isset($_POST['cfG1'])?$_POST['cfG1']:NULL;
            $nomeG1 =isset($_POST['nomeG1'])?$_POST['nomeG1']:NULL;
            $cognomeG1 =isset($_POST['cognomeG1'])?$_POST['cognomeG1']:NULL;
            $professioneG1 =isset($_POST['professioneG1'])?$_POST['professioneG1']:NULL;
            $titoloG1 =isset($_POST['titoloG1'])?$_POST['titoloG1']:NULL;
            $numeroG1 =isset($_POST['numeroG1'])?$_POST['numeroG1']:NULL;
            $tipotelG1 =isset($_POST['tipotelG1'])?$_POST['tipotelG1']:NULL;
            $usernameG1 =isset($_POST['usernameG1'])?$_POST['usernameG1']:NULL;
            $passwordG1 =isset($_POST['passwordG1'])?$_POST['passwordG1']:NULL;

            $query.="INSERT INTO Persona VALUES('$cfG1','$nomeG1','$cognomeG1');";
            $query.="INSERT INTO Genitore VALUES('$cfG1','$professioneG1','$titoloG1');";

            $query.="INSERT INTO Telefono VALUES('$numeroG1','$tipotelG1');";
            $query.="INSERT INTO ContattoG VALUES('$cfG1','$numeroG1');";

            $query.="INSERT INTO Referente VALUES('$cf','$cfG1');";

            $query.="INSERT INTO Credenziali VALUES('$usernameG1','$passwordG1','$cfG1');";
        }
        else{
            $query.="INSERT INTO Referente VALUES('$cf','$gen1');";
        }
        $chkgen2= isset($_POST['checkgen2'])?$_POST['checkgen2']:NULL;
        if($chkgen2=="si"){
            $gen2 =isset($_POST['selG2'])?$_POST['selG2']:NULL;
            if($gen2=='no'){
                $cfG2 =isset($_POST['cfG2'])?$_POST['cfG2']:NULL;
                $nomeG2 =isset($_POST['nomeG2'])?$_POST['nomeG2']:NULL;
                $cognomeG2 =isset($_POST['cognomeG2'])?$_POST['cognomeG2']:NULL;
                $professioneG2 =isset($_POST['professioneG2'])?$_POST['professioneG2']:NULL;
                $titoloG2=isset($_POST['titoloG2'])?$_POST['titoloG2']:NULL;
                $numeroG2 =isset($_POST['numeroG2'])?$_POST['numeroG2']:NULL;
                $tipotelG2 =isset($_POST['tipotelG2'])?$_POST['tipotelG2']:NULL;
                $usernameG2 =isset($_POST['usernameG2'])?$_POST['usernameG2']:NULL;
                $passwordG2 =isset($_POST['passwordG2'])?$_POST['passwordG2']:NULL;

                $query.="INSERT INTO Persona VALUES('$cfG2','$nomeG2','$cognomeG2');";
                $query.="INSERT INTO Genitore VALUES('$cfG2','$professioneG2','$titoloG2');";

                if($numeroG2!=NULL&&$tipotelG2!=NULL){
                    $query.="INSERT INTO Telefono VALUES('$numeroG2','$tipotelG2');";
                    $query.="INSERT INTO ContattoG VALUES('$cfG2','$numeroG2');";
                }
                
                $query.="INSERT INTO Referente VALUES('$cf','$cfG2');";
                if($usernameG2!=NULL&&$passwordG2!=NULL){
                    $query.="INSERT INTO Credenziali VALUES('$usernameG2','$passwordG2','$cfG2');";
                }
                
            } 
            else{
                $query.="INSERT INTO Referente VALUES('$cf','$gen2');";
            }
 
        }

        $query_res=pg_query($con,$query);
        if($query_res) echo "<div>Inserimento alunno avvenuto con successo</div><br><a href='index_alunno.php'>Torna alla pagina index per gli alunni.";
        else echo "<div>Inserimento alunno fallito</div><br><a href='index_alunno.php'>Torna alla pagina index per gli alunni.";
    }
    else echo "<div>'Inserimento alunno fallito</div><br><a href='index_alunno.php'>Torna alla pagina index per gli alunni.";
}
insert_alunno();
?>