<?php
//connexion à la base de données !!!
 session_start();

    $server="localhost";
    $bdname="lorem";
    $username="root";
    $password="";

    $bdd= new PDO("mysql:host=$server;dbname=$bdname",$username,$password);

?>