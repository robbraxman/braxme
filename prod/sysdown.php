<?php


    /******************************************
     * 
     * Check to see if System Down
     * 
     ******************************************/
    
    $result = pdo_query("1", "
            SELECT active, announcement from 
            service where msglevel='STATUS' /*sysdown*/
            ");
    if(!$result)
    {
        echo "<br>SQL Execute Error<br>";
        exit();
    }
    if ($row = pdo_fetch($result)) 
    {
        if($row['active']=='N')
        {
            echo "<br><br>$row[announcement]\n<br>";
            echo "<br><br>$row[announcement]\n<br>";
            $_SESSION['status'] = "N";
            exit();
        }
    }
