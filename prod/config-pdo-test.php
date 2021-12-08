<?php

    
    echo "start test";
    
    
    function pdo_sql_connect( $sqlurl, $usr, $pwd, $database )
    {
        global $sql_cert;
        global $sql_key;
        global $sql_ca;
        global $sql_globalcert;

        
        $dsn = "mysql:host=$sqlurl:3306;dbname=$database;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            
            /* Amazon AWS Version */
                       
            //PDO::MYSQL_ATTR_SSL_CA => $sql_globalcert,
            //PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
              
             
              
             
            
        ];
        $pdo = null;
        try {
            echo "<br>$dsn<br>$usr, <br>cert $sql_globalcert<br>";
            var_dump($options);
             $pdo = new PDO($dsn, $usr, $pwd, $options);
        } catch (\PDOException $e) {
             echo "<br>Connection: {$e->getMessage()}";
             throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }        
        return $pdo;
        
    }

    function pdo_query( $pdo, $query, $varlist )
    {

        if($varlist == null){
            echo "<br>Varlist is null<br>";
        }
        $stmt = $pdo->prepare($query);
        $stmt->execute($varlist);
        if(!$stmt){
            //echo "$query<br>";
            echo "<br>Execute Error<br> ";
            //exit();
        } else {
            echo "<br>OK<br>";
        }
        return $stmt;

    }
    
    function pdo_fetch($stmt ){
        if(!$stmt)
        {
            echo "<br>No stmt";
            return false;
        }
        if($stmt->rowcount() == 0){
            echo "<br>No results";
        //    return false;
        }
        
        $row =  $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }

    $url = "braxencrypted01.c0v3fmih5g1c.us-west-2.rds.amazonaws.com:3306";
    $usr = "braxuser";
    $pwd = base64_decode("VGhlUm9hZExhbWIkNzEyNTVIYXZlVHJhdmVs");
    $database = "braxproduction";
    $sql_globalcert = "/var/www/html/rds-combined-ca-bundle.pem";
    //$sql_globalcert = "rds-combined-ca-bundle.pem";
    $sql_cert = "/var/www/html/client-cert.pem";
    $sql_key = "/var/www/html/client-key.pem";
    $sql_ca = "/var/www/html/ca.pem";

    
    $pdo = pdo_sql_connect( $url, $usr, $pwd, $database, $sql_globalcert  );
    if($pdo){
        echo "<br>connected";
    } else {
        echo "<br>no connect";
    }

    $stmt = pdo_query($pdo,"select username from bytzvpn where username=?",array("booboo"));
    
    while ($row = pdo_fetch($stmt)){
        echo "{$row[username]}<br>";
        var_dump($row);
        echo "<br><br>";
    }
