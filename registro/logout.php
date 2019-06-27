<?php
session_start();
session_unset();

header("location: index_registro.php");
?>