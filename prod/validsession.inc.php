<?php
if(!isset($_SESSION['validsession'])){
    require_once("config-pdo.php");
    include("../error404.php");
    //header('This is not the page you are looking for', true, 404);
    exit();
}