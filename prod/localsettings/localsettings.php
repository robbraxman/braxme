<?php
/* This allows for multiple instances of the App on the Machine for
 * different enviroments
 */

//No slash at Ends
$rootserver = "https://brax.me";
$homepage = "https://brax.me";
$prodserver = "https://brax.me";
$filepath = "/var/www/html";
$installfolder = "prod";
$appname = "Brax.Me";
$batchruns = "Y";
$_SESSION['rootserver'] = "https://brax.me";
$_SESSION['installfolder'] = "prod";
$httpmode = "https";
$enterpriseapp = "Enteprise";
$startupphp = "l.php";
$customsite = false;
$applogo = "https://sand.brax.me/img/logo-b2.png";
$domainsuffix = "brax.me";
//Color Specifics
$icon_braxmenu =    "<img class='icon25'  title='Main Menu' src='../img/brax-menu-round-white-128.png' />";
$icon_braxlive =    "<img class='icon25'  title='Live' src='../img/brax-live-round-white-128.png' />";
$icon_braxchat =    "<img class='icon25'  title='Chat' src='../img/brax-chat-round-white-128.png' />";
$icon_braxroom =    "<img class='icon25'  title='Rooms' src='../img/brax-room-round-white-128.png'  />";
$icon_braxphoto =  "<img class='icon25'  title='My Photos' src='../img/brax-photo-round-white-128.png' />";
$icon_braxdoctor =    "<img class='icon25'  title='My Files' src='../img/brax-doctor-round-white-128.png'  />";
$icon_braxreport =    "<img class='icon25'  title='My Files' src='../img/brax-reports-round-white-128.png'  />";
$icon_braxpeople =    "<img class='icon25'  title='Find People' src='../img/brax-people-round-white-128.png'  />";
$icon_braxsettings =    "<img class='icon25'  title='Settings' src='../img/brax-settings-round-white-128.png' />";

$icon_braxstop =    "<img class='icon25' src='../img/Stop-Music-White_120px.png'  />";
$icon_braxlogout =    "<img class='icon25' src='../img/logout-circle-128.png' />";

$global_menu_color = '#3e4749';//gray
$global_banner_color = 'black';//gray
$global_titlebar_color = '#a1a1a4';//gray

if(isset($_SESSION['superadmin']) && $_SESSION['superadmin']=='Y'){
    $global_menu_color = '#00aeef';//azure
    $global_banner_color = '#004990';//michigan
    
}


/*
 * security note - these databases are restricted to VPC and specific IP so they
 * cannot be accessed externally
 * 
 */

    $_SESSION['database'] = "braxproduction";
    $session_sqlurl = 'localhost';
    $session_sqlusr = 'user';
    $session_sqlpwd = 'password';
    $_SESSION['sqlurl'] = 'localhost';
    $_SESSION['sqlusr'] = 'user';
    $_SESSION['sqlpwd'] = 'password';

    $_SESSION['database4'] = "braxcrypt";
    $session_sqlurl4 = 'localhost';
    $session_sqlusr4 = 'user';
    $session_sqlpwd4 = 'password';
    $_SESSION['sqlurl4'] = 'localhost';
    $_SESSION['sqlusr4'] = 'user';
    $_SESSION['sqlpwd4'] = 'password';

    $_SESSION['databaser'] = "braxproduction";
    $session_sqlurlr = 'localhost';
    $session_sqlusrr = 'user';
    $session_sqlpwdr = 'password';
    $_SESSION['sqlurlr'] = 'localhost';
    $_SESSION['sqlusrr'] = 'user';
    $_SESSION['sqlpwdr'] = 'password';



    /* This section is what's used in a Live
     * brax.me site. The login information
     * is on a separate encrypted server
     * 
     * This is commented out so the credentials
     * are noted above
     
    
    $credentials = RetrieveDatabaseKeysAll();
    //Do only once if not in SESSION VARS
    if($credentials!== false){
        
        

        $_SESSION['database'] = "braxproduction";
        $session_sqlurl = $credentials->db1['database'];
        $session_sqlusr = $credentials->db1['user'];
        $session_sqlpwd = $credentials->db1['password'];
        $_SESSION['sqlurl'] = $credentials->db1['database'];
        $_SESSION['sqlusr'] = $credentials->db1['user'];
        $_SESSION['sqlpwd'] = base64_decode($credentials->db1['password']);

        $_SESSION['database4'] = "braxcrypt";
        $session_sqlurl4 = $credentials->db4['database'];
        $session_sqlusr4 = $credentials->db4['user'];
        $session_sqlpwd4 = $credentials->db4['password'];
        $_SESSION['sqlurl4'] = $credentials->db4['database'];
        $_SESSION['sqlusr4'] = $credentials->db4['user'];
        $_SESSION['sqlpwd4'] = base64_decode($credentials->db4['password']);

        $_SESSION['databaser'] = "braxproduction";
        $session_sqlurlr = $credentials->dbr['database'];
        $session_sqlusrr = $credentials->dbr['user'];
        $session_sqlpwdr = $credentials->dbr['password'];
        $_SESSION['sqlurlr'] = $credentials->dbr['database'];
        $_SESSION['sqlusrr'] = $credentials->dbr['user'];
        $_SESSION['sqlpwdr'] = base64_decode($credentials->dbr['password']);


    }
    */

    $_SESSION['servertimezone'] = "0"; //Pacific - GMT Offset

    $app_smtp_host = "hosturl";
    $app_smtp_port = 465;
    $app_smtp_username = "username";
    $app_smtp_password = 'password';
    $app_smtp_secure = 'ssl';
    $app_smtp_email = "noreply@brax.me";
    $app_smtp_mailname = "Brax.Me Message";
    
        
    
    function RetrieveDatabaseKeysAll()
    {

        if(isset($_SESSION['sqlurl'])){
            return false;
        }

        $data_string = "database=all&apikey=authorizationkey";
        $ch = curl_init('https://vpcinstance/prod/sqlkeys.php');
        if($ch ){
            
            curl_setopt($ch, CURLOPT_POST, true);

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            $databasekeys = curl_exec($ch);

            //close connection
            curl_close($ch);
        }
        
        if(rtrim($databasekeys) === ''){
            echo "<br>Sorry - Server Maintenance is Underway - We'll be back shortly.<br>";
            exit();
        }
        
        $databases = explode("|",$databasekeys);
        $credentials = array();
        foreach ($databases as $database ){
            if($database!=''){
                $keys = explode("^",$database);

                $credentials["$keys[0]"]['database']=$keys[1];
                $credentials["$keys[0]"]['user']=$keys[2];
                //$credentials['password']=base64_decode($temp[2]);
                $credentials["$keys[0]"]['password']=$keys[3];
            }
        }
        
        return (object) $credentials;
        
    }

?>
