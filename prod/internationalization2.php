<?php
require_once("config-pdo.php");

if($_SESSION['language']==''){
    $_SESSION['language']='english';
}
//$_SESSION['language']='english';

if($_SESSION['language']=='english'){
    $lang_passwordtip = "
        Use a password that has a minimum of 8 characters, 
        utilizes upper/lower case, numbers, and special characters. 
        Repeating values lowers password strength. Avoid dictionary words.
                ";
}
if($_SESSION['language']=='spanish'){
    $lang_passwordtip = "

        Utilice una contraseña que tenga un mínimo de 8 caracteres, 
        utiliza mayúsculas/minúsculas, números y caracteres especiales. los valores de repetición 
        reducen la fuerza de la contraseña. Evite las palabras del diccionario.
                ";
}
if($_SESSION['language']=='danish'){
    $lang_passwordtip = "
        Brug et kodeord på mindst 8 tegn, 
        Anvend store og små bogstaver, tal og specialtegn. 
        Gentagede tegn sænker adgangskodens styrke. 
        Undgå at bruge ord fra en ordbog.        
            ";
}

?>