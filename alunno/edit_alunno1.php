<?php
require_once("../comuni/utility.php");
function edit_alunno(){
    if(isset($_POST['cf'])){
        $con = connect_DB();
        $query='';
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
        $check_alunno = "SELECT * FROM Alunno WHERE cf = '$cf';";
        $check_alunno_res = pg_query($con,$check_alunno);
        if(!$check_alunno_res){
            echo "Errore: ".pg_last_error($con);
            exit; 
        }

        $query .= "UPDATE Persona SET nome = '$nome', cognome = '$cognome' WHERE cf = '$cf';";
        $query .= "UPDATE Alunno SET indirizzo = '$indirizzo', datadinascita = '$datadinascita',zonaresidenza = '$zona', pre = null, post = null WHERE cf = '$cf'; ";
        $query_frequentazione = "SELECT * FROM Frequentazione WHERE alunno = '$cf' ORDER BY inizio DESC LIMIT 1;";
        $query_frequentazione_res = pg_query($con, $query_frequentazione);
        if(pg_num_rows($query_frequentazione_res)>0){
            $x = pg_fetch_assoc($query_frequentazione_res);
            if($x["classe"]!=$scuola){
                $ini = $x["inizio"];
                $query .= "UPDATE Frequentazione SET fine = CURRENT_DATE WHERE alunno = '$cf' AND inizio = '$ini' ;";
            }
            
        }
        else{
            $query .= "INSERT INTO Frequentazione VALUES('$scuola','$cf','$inizio',null);";
        }
        

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
        if ($gen1==NULL) echo 'gen1 null';
        $cfG1 =isset($_POST['cfG1'])?$_POST['cfG1']:NULL;
        $nomeG1 =isset($_POST['nomeG1'])?$_POST['nomeG1']:NULL;
        $cognomeG1 =isset($_POST['cognomeG1'])?$_POST['cognomeG1']:NULL;
        $professioneG1 =isset($_POST['professioneG1'])?$_POST['professioneG1']:NULL;
        $titoloG1 =isset($_POST['titoloG1'])?$_POST['titoloG1']:NULL;
        $numeroG1 =isset($_POST['numeroG1'])?$_POST['numeroG1']:NULL;
        $tipotelG1 =isset($_POST['tipotelG1'])?$_POST['tipotelG1']:NULL;
        $usernameG1 =isset($_POST['usernameG1'])?$_POST['usernameG1']:NULL;
        $passwordG1 =isset($_POST['passwordG1'])?$_POST['passwordG1']:NULL;
        if($gen1=='no')
        {
            $query.="INSERT INTO Persona VALUES('$cfG1','$nomeG1','$cognomeG1');";
            $query.="INSERT INTO Genitore VALUES('$cfG1','$professioneG1','$titoloG1');";

            $query.="INSERT INTO Telefono VALUES('$numeroG1','$tipotelG1');";
            $query.="INSERT INTO ContattoG VALUES('$cfG1','$numeroG1');";

            $query.="INSERT INTO Referente VALUES('$cf','$cfG1');";

            $query.="INSERT INTO Credenziali VALUES('$usernameG1','$passwordG1','$cfG1');";
        }
        else{
            if($gen1==$cfG1){
                $query .= "UPDATE Persona SET nome = '$nomeG1', cognome = '$cognomeG1' WHERE cf = '$cfG1';";
                $query .= "UPDATE Genitore SET professione = '$professioneG1', titolo = '$titoloG1' WHERE cf = '$cfG1';";
                $queryTel = "SELECT numero FROM Telefono as T, ContattoG as C WHERE T.numero = C.telefono AND C.genitore = '$cfG1';";
                $queryTel_res = pg_query($con,$queryTel);
                if(!$queryTel_res){
                    echo "Errore: ".pg_last_error($con)."<br><a href='index_alunno.php'>Torna alla pagina index per gli alunni.";
                    exit;
                }
                $num= $queryTel_res["numero"];
                $query .= "DELETE FROM ContattoG WHERE telefono ='$num';";
                $query .= "DELETE FROM Telefono WHERE numero ='$num';";
                $query.="INSERT INTO Telefono VALUES('$numeroG1','$tipotelG1');";
                $query.="INSERT INTO ContattoG VALUES('$cfG1','$numeroG1');";
                $queryCred = "SELECT nomeutente FROM Credenziali as C WHERE C.genitore = '$cfG1';";
                $queryCred_res = pg_query($con,$queryTel);
                if(!$queryTel_res){
                    echo "Errore: ".pg_last_error($con)."<br><a href='index_alunno.php'>Torna alla pagina index per gli alunni.";
                    exit;
                }
                $ut = $queryCred_res["nomeutente"];
                $query .= " DELETE FROM Credenziali WHERE nomeutente ='$ut'; ";
                $query.="INSERT INTO Credenziali VALUES('$usernameG1','$passwordG1','$cfG1');";
            }
            else{
                $queryGen = "SELECT * FROM Referente as R, Genitore as G, ContattoG as C WHERE R.genitore = G.cf AND C.genitore = G.cf AND R.genitore = C.genitore AND R.alunno = '$cf'; ";
                $queryGen_res = pg_query($con, $queryGen);
                if(pg_num_rows($queryGen_res)>0){
                    $gg=$queryGen_res["genitore"];
                    $query .= "UPDATE Referente SET genitore = '$gen1' WHERE genitore='$gg' AND alunno ='$cf';";
                }
            }
            
        }
        $chkgen2= isset($_POST['checkgen2'])?$_POST['checkgen2']:NULL;
        if($chkgen2=="si"){
            $gen2 =isset($_POST['selG2'])?$_POST['selG2']:NULL;
            $cfG2 =isset($_POST['cfG2'])?$_POST['cfG2']:NULL;
            $nomeG2 =isset($_POST['nomeG2'])?$_POST['nomeG2']:NULL;
            $cognomeG2 =isset($_POST['cognomeG2'])?$_POST['cognomeG2']:NULL;
            $professioneG2 =isset($_POST['professioneG2'])?$_POST['professioneG2']:NULL;
            $titoloG2=isset($_POST['titoloG2'])?$_POST['titoloG2']:NULL;
            $numeroG2 =isset($_POST['numeroG2'])?$_POST['numeroG2']:NULL;
            $tipotelG2 =isset($_POST['tipotelG2'])?$_POST['tipotelG2']:NULL;
            $usernameG2 =isset($_POST['usernameG2'])?$_POST['usernameG2']:NULL;
            $passwordG2 =isset($_POST['passwordG2'])?$_POST['passwordG2']:NULL;
            if($gen2=='no'){
                $query.="INSERT INTO Persona VALUES('$cfG2','$nomeG2','$cognomeG2');";
                $query.="INSERT INTO Genitore VALUES('$cfG2','$professioneG2','$titoloG2');";

                if($numeroG1!=NULL&&$tipotelG2!=NULL){
                    $query.="INSERT INTO Telefono VALUES('$numeroG2','$tipotelG2');";
                    $query.="INSERT INTO ContattoG VALUES('$cfG2','$numeroG2');";
                }
                
                $query.="INSERT INTO Referente VALUES('$cf','$cfG2');";
                if($usernameG2!=NULL&&$passwordG2!=NULL){
                    $query.="INSERT INTO Credenziali VALUES('$usernameG2','$passwordG2','$cfG2');";
                }
                
            } 
            else{
                if($gen2==$cfG2){
                    $query .= "UPDATE Persona SET nome = '$nomeG2', cognome = '$cognomeG2' WHERE cf = '$cfG2';";
                    $query .= "UPDATE Genitore SET professione = '$professioneG2', titolo = '$titoloG2' WHERE cf = '$cfG2';";
                    if($numeroG2!=NULL && $tipotelG2!=NULL){
                        $queryTel = "SELECT numero FROM Telefono as T, ContattoG as C WHERE T.numero = C.telefono AND C.genitore = '$cfG2';";
                        $queryTel_res = pg_query($con,$queryTel);
                        if(!$queryTel_res){
                            echo "Errore: ".pg_last_error($con)."<br><a href='index_alunno.php'>Torna alla pagina index per gli alunni.";
                            exit;
                        }
                        $num=$queryTel_res["numero"];
                        $query .= " DELETE FROM ContattoG WHERE telefono ='$num'; ";
                        $query .= " DELETE FROM Telefono WHERE numero ='$num'; ";
                        $query.="INSERT INTO Telefono VALUES('$numeroG1','$tipotelG1');";
                        $query.="INSERT INTO ContattoG VALUES('$cfG1','$numeroG1');";
                    }
                    if($usernameG2!=NULL&&$passwordG2!=NULL){
                        $queryCred = "SELECT nomeutente FROM Credenziali as C WHERE C.genitore = '$cfG1';";
                        $queryCred_res = pg_query($con,$queryTel);
                        if(!$queryCred_res){
                            echo "Errore: ".pg_last_error($con)."<br><a href='index_alunno.php'>Torna alla pagina index per gli alunni.";
                            exit;
                        }
                        $ut = $queryCred_res["nomeutente"];
                        $query .= " DELETE FROM Credenziali WHERE nomeutente ='$ut'; ";
                        $query.="INSERT INTO Credenziali VALUES('$usernameG1','$passwordG1','$cfG1');";
                    }
                    
                }
                else{
                    $queryGen = "SELECT genitore as cfG FROM Referente WHERE alunno = '$cf' EXCEPT SELECT R.genitore as cfG FROM Referente as R, Genitore as G, ContattoG as C WHERE R.genitore = G.cf AND C.genitore = G.cf AND R.genitore = C.genitore AND R.alunno = '$cf'; ";
                    $queryGen_res = pg_query($con, $queryGen);
                    if(pg_num_rows($queryGen_res)>0){
                        $gg=$queryGen_res["cfG"];
                        $query .= "UPDATE Referente SET genitore = '$gen2' WHERE genitore='$gg' AND alunno ='$cf';";
                    }
                }
            }
        }
        else{
            $gen2 =isset($_POST['selG2'])?$_POST['selG2']:NULL;
            $query.="DELETE FROM Referente WHERE genitore = '$gen2';";
        }
        $query_res=pg_query($con,$query);
        if($query_res) echo "<div>Modifica alunno avvenuta con successo</div><br><a href='index_alunno.php'>Torna alla pagina index per gli alunni.";
        else echo "<div>Modifica alunno fallita</div><br><a href='index_alunno.php'>Torna alla pagina index per gli alunni.";
    }
    else echo "<div>Modifica alunno fallita</div><br><a href='index_alunno.php'>Torna alla pagina index per gli alunni.";
}
edit_alunno();
?>