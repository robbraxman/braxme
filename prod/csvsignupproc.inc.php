<?php
require_once("config.php");
require_once("aws.php");
require_once("SmsInterface.inc");
require_once("sendmail.php");
require_once("crypt.inc.php");

require_once("signupfunc.php");

function ProcessUpload( $providerid, $upload_hdr, $roomid, $sponsor )
{
    $max_file_size = 200000000; 
    //$max_file_size = 504800; 
    $num_of_uploads=1;
    $file_types_array=array("txt","xml","csv");
        
    //$upload_hdr="photolib";    
    $upload_dir=$upload_hdr."/";    
    mkdir($upload_dir."files/".$providerid);

        
    $status = true;
    $UploadNo = 0;
    $count=0;
        
    //Check Max File Size of Entire Batch
    $sizetotal = 0;
    foreach($_FILES["file"]["error"] as $key => $value){

        $sizetotal += $_FILES["file"]["size"][$key];
    }
    
    if($sizetotal > $max_file_size ){

        echo( "Batch ($sizetotal) exceeded Max Size limit. Upload cancelled<br />"); 
        $status = false;
        return false;
    }
    
        
    foreach($_FILES["file"]["error"] as $key => $value){
        
        $UploadNo +=1;
            
        if($_FILES["file"]["name"][$key]!="" && $value == UPLOAD_ERR_OK){
            
            $origfilename = $_FILES["file"]["name"][$key]; 

            //echo "<br>Processed Upload: $origfilename<br> ";
            $origfilename = $_FILES["file"]["name"][$key]; 

            $filename = explode(".", $_FILES["file"]["name"][$key]); 
            $filenameext = strtolower($filename[count($filename)-1]); 
            unset($filename[count($filename)-1]); 
            $filename = implode(".", $filename); 
            $filename = substr($filename, 0, 15).".".$filenameext; 

                
            $file_ext_allow = FALSE; 
            for($x=0;$x<count($file_types_array);$x++){

                if(strtolower($filenameext)==$file_types_array[$x]){
                  $file_ext_allow = TRUE; 
                }           

            } 
            
            if(!$file_ext_allow){
                echo($origfilename." has an invalid extension<br>"); 
                $status = false;
                exit();
            }
                        
                            
            $tempfile = $_FILES["file"]["tmp_name"][$key];
            $fsize = filesize($tempfile);
            
            if($fsize == 0 ){
                
                echo($origfilename." is NULL<br>"); 
                $status = false;
                exit();
                
            }
            $base = $upload_dir."files/".$providerid."/$providerid";
            $target = $upload_dir."files/".$providerid."/$providerid.base";
            unlink( $target );
            move_uploaded_file($tempfile, $target);
            echo "CSV File Imported - Size $fsize<br>$target";
            $count++;
            //Read File and Split Up
            $max = 5000;
            $splitcount = 0;
            $reccount=0;
            $fd = fopen($target, "r");
            
            $splitfile = $base.".".$count.".csv";
            $fd2 = fopen($splitfile, "a");
            
            while ( $data = fgetcsv($fd)){
                //var_dump( $data);
                if ($data[0] && $data[0]!='') {
                    
                    $providerid = '';
                    $name = ucwords(mysql_safe_string(str_replace("'","", $data[0])));
                    $email = strtolower(mysql_safe_string(str_replace("'","", $data[1])));
                    $sms = mysql_safe_string(str_replace("'","", $data[2]));
                    $handle = strtolower(mysql_safe_string(str_replace("'","", $data[3])));
                    $password = mysql_safe_string(str_replace("'","", $data[4]));
                    $company = ucwords(mysql_safe_string(str_replace("'","", $data[5])));
                    
                    //$email = "";
                    if( filter_var($email, FILTER_VALIDATE_EMAIL)===false){
                        $email = "";
                    }
                    
                    if($splitcount >= $max){
                        $count++;
                        $splitcount = 0;
                        fclose($fd2);
                        $splitfile = $base.".".$count.".csv";
                        $fd2 = fopen($splitfile, "a");
                    }
                    $name = str_replace("&amp;","&",$name);
                    $company = str_replace("&amp;","&",$company);

                    $reccount++;
                    fwrite($fd2, "\"$name\",\"$email\",\"$sms\",\"$handle\",\"$password\",\"$company\"\r\n");

                    $splitcount++;
                    
                }
            }
            fclose($fd2);
            fclose($fd);
            
        }
    }
    
    if($count > 0 ){
        $file = $base.".count";
        $fd = fopen($file, "w");
        fwrite($fd,$count);
        fclose($fd);
    }
    unlink( $target );
   
    echo "$count files created<br>$reccount Entries<br>Proceed with Step 2<br>";
    return $status;
}

function ProcessUpload2( $providerid, $upload_hdr, $roomid, $sponsor )
{
    $upload_dir=$upload_hdr."/";    
    
    $base = $upload_dir."files/".$providerid."/$providerid";
    $filecount = intval(file_get_contents($base.".count"));
    unlink($base.".count");
    
    $count = 0;

    for( $i=1; $i <= $filecount;$i++){
        
        $filename = "$base.$i.csv";
        if(file_exists($filename)){
            
            echo "Processing File $i/$filecount<br>";
        
            $fd = fopen($filename, "r");
            $count = 0;
            $looped = 0;
            while ( $data = fgetcsv($fd)){
                $looped++;
            
                if ($data[0] && $data[0]!='') {

                    $signup = new SignUp;
                    
                    //$providerid = '';
                    $name = ucwords(mysql_safe_string(str_replace("'","", $data[0])));
                    $email = strtolower(mysql_safe_string(str_replace("'","", $data[1])));
                    $sms = mysql_safe_string(str_replace("'","", $data[2]));
                    $handle = strtolower(mysql_safe_string(str_replace("'","", $data[3])));
                    $password = mysql_safe_string(str_replace("'","", $data[4]));
                    $company = ucwords(mysql_safe_string(str_replace("'","", $data[5])));
                    
                    $name = str_replace("&amp;","&",$name);
                    $company = str_replace("&amp;","&",$company);
                    

                    if( 
                            
                        $signup->QueueCreateAccount(
                            $providerid, 
                            $name, 
                            $email, 
                            $sms, 
                            $handle, 
                            $password, 
                            $roomid,
                            $sponsor,
                            $company
                        )

                    ){
                        $count++;
                    } else {
                    }

                    unset( $signup );
                }
            }
            fclose($fd);
            unlink($filename);
            //Process Only One File at a time
            //break;
        }
    }
    echo "$count items processed<br>Repeat Step 2 until 0 items are processed.";
    $status = true;
    return $status;
}

function ProcessUpload2a( $providerid, $upload_hdr, $roomid, $sponsor )
{
    do_mysqli_query("1","update csvsignup set status='N' where status='U' ");
    echo "Batch creation of accounts now ENABLED. This will run in the background on the Batch Server.";
    $status = true;
    rmdir($upload_dir."files/".$providerid);
    return $status;
}


function ProcessUpload3( $providerid, $upload_hdr, $roomid, $sponsor )
{
    $status = true;
    $signup = new SignUp;
    $signup->BatchCreateAccount($providerid, "LIMIT 20000");
    unset( $signup );
    return $status;
}


function exportCSV($sponsor)
{
    header("Content-Type: text/plain");
    header('Content-Disposition: attachment; filename="members.csv" ');
    
    $result = do_mysqli_query("1","
        select providername, replyemail, handle, companyname, sponsor
        from provider where active='Y' and sponsor = '$sponsor'
        order by providername 
    ");
    print "\"Name\",\"Email\",\"SMSPhone\",\"Handle\",\"Password\",\"Company\",\"SponsorCode\" ";
    print "\r\n";
    while( $row = do_mysqli_fetch("1",$result)){
        
        
        print "\"$row[providername]\",\"$row[replyemail]\",\"\",\"$row[handle]\",\"\",\"$row[companyname]\",\"$row[sponsor]\" ";
        print "\r\n";
    }
    

        
}
?>