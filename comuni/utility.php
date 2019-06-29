<?php
function checksession(){
    if(isset($_SESSION["username"])) return true;
    else return false;
}
/* creo connessione al DB*/
function connect_DB() {
$con = pg_connect("host=localhost port=5432 dbname=progetto user=postgres password=unimi");
/* in laboratorio usare
   $con = pg_connect("host=localhost port=5432 dbname=progetto user=postgres password=postgres"); 
   verificando il nome del DB */
if (!$con) {
	echo "Errore nella connessione al database: " . pg_last_error($con);
    exit;
}
return $con;
}

function print_banner(){
echo<<<STAMPA
<!DOCTYPE html>
<html>
<head>
<title>Gestione Istituti scolastici</title>
<link rel="stylesheet" type="text/css" href="../comuni/stile.css"/>
</head>

<body>
<div class="banner">
<h1><a href="index.php" title="Torna all'homepage">Gestione istituti scolastici</a></h1>
STAMPA;

echo '</div>';
}

function print_menu(){
echo <<<STAMPA
<div id="menu">
 <a href="http://127.0.0.1/progetto/index.php">Homepage</a>
| <a href="http://127.0.0.1/progetto/scuola/index.php">Scuole</a>
| <a href="http://127.0.0.1/progetto/alunno/index.php">Alunni</a>
| <a href="http://127.0.0.1/progetto/personale/index.php">Personale</a>
| <a href="http://127.0.0.1/progetto/registro/index.php">Registro</a>
STAMPA;
if(checksession()){
echo' | Benvenuto '.$_SESSION["username"].'!
    | <a href="../registro/logout.php">Logout</a>';
}
echo '</div>';
if(!checksession()){
    print_form_login();
}

}
function print_form_login(){
echo<<<STAMPA
<div>
    <form action="http://127.0.0.1/progetto/registro/login.php" method="POST">
        <table id="login">
        <th id="trLog">Login</th>
            <tr>    
                <td>Username</td>
                <td><input type="text" name ="nomeutente" title="Inserire lo username" size="50"/></td>
            </tr>
            <tr>    
                <td>Password</td>
                <td><input type="password" name ="pwd" title="Inserire la password" size="50"/></td>
            </tr>
            <tr>    
                <td><input type="reset" value="Cancella"/></td>
                <td><input type="submit" value="Login"/></td>
            </tr>
        </table>
    </form>
</div>
STAMPA;
}
?>