<?php

$hostName = "localHost";
$dbUser = "root";
$dbPassword = "";
$dbName = "login_register";

//Aviso para caso esteja com algo de errado com o Banco de Dados 
$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);
if(!$conn){
    die("something went wrong;");
}
?>