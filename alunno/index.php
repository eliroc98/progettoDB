<?php
require_once("../comuni/utility.php");
require_once("../comuni/header.php");
$con = connect_DB();
$query = "CREATE OR REPLACE VIEW vista1 AS SELECT PA.cf as cf, PA.nome as nome, PA.cognome as cognome, A.indirizzo as indirizzo, A.datadinascita as datadinascita, A.zonaresidenza as zonaresidenza, A.pre as pre, A.post as post,F.classe, GP1.nome as nomeG1, GP1.cognome as cognomeG1, GP1.cf as cfG1 FROM Persona as PA, Alunno as A, Persona as GP1, Referente as R1, Frequentazione as F,ContattoG as Co WHERE PA.cf = A.cf AND GP1.cf = R1.genitore AND A.cf = R1.alunno AND  F.alunno = A.cf AND GP1.cf = Co.genitore;";
$query.="CREATE OR REPLACE VIEW vista2 AS SELECT G.cf, P.nome, P.cognome, alunno FROM Genitore as G, Referente, Persona as P WHERE G.cf=P.cf AND G.cf = genitore;";
$query.= "SELECT vista1.cf as cf, vista1.nome as nome, vista1.cognome as cognome, vista1.indirizzo as indirizzo, vista1.datadinascita as datadinascita, vista1.zonaresidenza as zonaresidenza, vista1.pre as pre, vista1.post as post, vista1.classe as classe, vista1.nomeg1 as nomegenitore1, vista1.cognomeg1 as cognomegenitore1, vista1.cfg1 as cfgenitore1, vista2.nome as nomegenitore2, vista2.cognome as cognomegenitore2, vista2.cf as cfgenitore2 FROM vista1 left JOIN vista2 ON vista1.cfg1 != vista2.cf AND vista1.cf = vista2.alunno"; 
$query_res=pg_query($con, $query);
if(!$query_res)  {
	echo "Errore: ".pg_last_error($con);
	exit;
}

echo <<<STAMPA
<h3>Alunni</h3>
<table class="elenco">
<tr>
<th>cf</th>
<th>Nome</th>
<th>Cognome</th>
<th>Indirizzo</th>
<th>Data di nascita</th>
<th>Zona di residenza</th>
<th>Pre</th>
<th>Post</th>
<th>Classe</th>
<th>Nome genitore 1</th>
<th>Cognome genitore 1</th>
<th>cf genitore 1</th>
<th>Nome genitore 2</th>
<th>Cognome genitore 2</th>
<th>cf genitore 2</th>
</tr>
STAMPA;
$pari=true;
while($alunno = pg_fetch_assoc($query_res)){
    if ($pari) $color="row0";
	else $color="row1";
    $cf = $alunno["cf"];
    $nome = $alunno["nome"];
    $cognome = $alunno["cognome"];
    $indirizzo = $alunno["indirizzo"];
    $datadinascita = $alunno["datadinascita"];
    $zonaresidenza = $alunno["zonaresidenza"];
    $pre = $alunno["pre"];
    $post = $alunno["post"];
    $classe = $alunno["classe"];
    $nomeg1 = $alunno["nomegenitore1"];
    $cognomeg1 = $alunno["cognomegenitore1"];
    $cfg1 = $alunno["cfgenitore1"];
    $nomeg2 = $alunno["nomegenitore2"];
    $cognomeg2 = $alunno["cognomegenitore2"];
    $cfg2 = $alunno["cfgenitore2"];

    echo '<tr class="'.$color.'">';
    echo '<td class="'.$color.'">'.$cf.'</td>';
    echo '<td class="'.$color.'">'.$nome.'</td>';
    echo '<td class="'.$color.'">'.$cognome.'</td>';
    echo '<td class="'.$color.'">'.$indirizzo.'</td>';
    echo '<td class="'.$color.'">'.$datadinascita.'</td>';
    echo '<td class="'.$color.'">'.$zonaresidenza.'</td>';
    echo '<td class="'.$color.'">'.$pre.'</td>';
    echo '<td class="'.$color.'">'.$post.'</td>';
    echo '<td class="'.$color.'">'.$classe.'</td>';
    echo '<td class="'.$color.'">'.$nomeg1.'</td>';
    echo '<td class="'.$color.'">'.$cognomeg1.'</td>';
    echo '<td class="'.$color.'">'.$cfg1.'</td>';
    echo '<td class="'.$color.'">'.$nomeg2.'</td>';
    echo '<td class="'.$color.'">'.$cognomeg2.'</td>';
    echo '<td class="'.$color.'">'.$cfg2.'</td>';
    echo '<td class="'.$color.'">
    <form action="edit.php" method="get">
    <input type="hidden" name="cf" value="'.$cf.'" />
    <input type="submit" value="Modifica" />
    </form>
    </td>' ;
    echo '</tr>';
    $pari=!$pari;
}
echo <<< STAMPA
<tr><td><a href="insert.php">Inserisci un nuovo alunno</td></tr>
</table>
STAMPA;
?>


