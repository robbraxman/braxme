<?php
require_once("config.php");
require_once("aws.php");
require_once("crypt.inc.php");


function ProcessUpload( $providerid, $encoding, $subject, $upload_hdr, $uploadtype, $folder, $sendemail, $roomid )
{
        $max_file_size = 200000000; 
        //$max_file_size = 504800; 
        $num_of_uploads=1;
        $file_types_array=array("txt","xml","csv");
        
        
        $filefolder = '';
        if(isset($_SESSION['filefolder'])){
            $filefolder = $_SESSION['filefolder'];
        }
        //who is other?
        $uploadprovider = '';
        $result = do_mysqli_query("1", 
          "select providerid from provider where
              (replyemail = '$sendemail' or handle = '$sendemail') and '$sendemail' != '' and active='Y' "
        );
        if( $row = do_mysqli_fetch("1",$result))
        {
            $uploadprovider = $row['providerid'];
        }
        
        //$upload_hdr="photolib";    
        $upload_dir=$upload_hdr."/";    

        
        
        
        
        $status = true;
        
        $attachmentpath=addslashes($upload_dir);
        $attachmentfilename="";
        
        $UploadNo = 0;
        
        //Check Max File Size of Entire Batch
        $sizetotal = 0;
        foreach($_FILES["file"]["error"] as $key => $value)
        { 
            $sizetotal += $_FILES["file"]["size"][$key];
        }
        if($sizetotal > $max_file_size )
        {
            echo( "Batch exceeded Max Size limit. Upload cancelled<br />"); 
            $status = false;
            return false;
        }
        
        foreach($_FILES["file"]["error"] as $key => $value)
        { 
            $UploadNo +=1;
            
            if($_FILES["file"]["name"][$key]!="")
            { 
                $origfilename = $_FILES["file"]["name"][$key]; 
                
                if($value==UPLOAD_ERR_OK)
                { 
                        $result = do_mysqli_query("1","select room from statusroom where owner=providerid and roomid=$roomid ");
                        if($row = do_mysqli_fetch("1",$result)){
                            $room = $row['room'];
                        }
                        //echo "<br>Processed Upload: $origfilename<br> ";
                        $origfilename = $_FILES["file"]["name"][$key]; 
                        
                        $filename = explode(".", $_FILES["file"]["name"][$key]); 
                        $filenameext = strtolower($filename[count($filename)-1]); 
                        unset($filename[count($filename)-1]); 
                        $filename = implode(".", $filename); 
                        $filename = substr($filename, 0, 15).".".$filenameext; 
                        $file_ext_allow = FALSE; 
                        for($x=0;$x<count($file_types_array);$x++)
                        { 
                            if(strtolower($filenameext)==$file_types_array[$x])
                            { 
                              $file_ext_allow = TRUE; 
                            }           
                        } 
                        if($file_ext_allow)
                        { 
                            if($_FILES["file"]["size"][$key]<$max_file_size)
                            { 
                                    $tempfile = $_FILES["file"]["tmp_name"][$key];
                                    $fsize = filesize($_FILES["file"]["tmp_name"][$key]);
                                    $count=0;
                                    if( $fsize > 0 )
                                    {
                                        $fd = fopen($tempfile, "r");
                                        do {
                                                if ($data[0] && $data[0]!='') {
                                                    
                                                    if(DupCheck($data[0], $providerid, $roomid, $room ))
                                                    {
                                                        $data[1] = CleanPhone($data[1]);
                                                        do_mysqli_query("1",
                                                            "INSERT INTO csvtemp (email, sms, name, ownerid, uploaded, roomid) VALUES
                                                            (
                                                                '".ltrim(addslashes($data[0]))."',
                                                                '".ltrim(addslashes($data[1]))."',
                                                                '".ltrim(addslashes($data[2]))."',
                                                                '".$providerid."', now(), $roomid
                                                            )
                                                        ");
                                                        
                                                        if( !strstr($data[0],"@test.test" ) && $data[1]!='+13105551212')
                                                        {
                                                            
                                                            do_mysqli_query("1",
                                                                "delete from invites where roomid = $roomid and 
                                                                 providerid = $providerid and email = ".ltrim(addslashes($data[0]))."

                                                            ");
                                                            
                                                        
                                                            do_mysqli_query("1",
                                                                "INSERT ignore INTO invites (email, sms, name, providerid, invitedate, roomid, status, retries) VALUES
                                                                (
                                                                    '".ltrim(addslashes($data[0]))."',
                                                                    '".ltrim(addslashes($data[1]))."',
                                                                    '".ltrim(addslashes($data[2]))."',
                                                                    '".$providerid."', now(), $roomid, 'Y', 0
                                                                )

                                                            ");
                                                        }
                                                        
                                                        
                                                        $count++;
                                                    }
                                                }
                                        } 
                                        while ($data = fgetcsv($fd));                                        
                                        
                                        echo("<br>$count CSV items uploaded successfully. <br>"); 
                                        
                                    }
                                    
                            }
                            else
                            { 
                                echo($origfilename." was too big, not uploaded<br>"); 
                                $status = false;
                            } 
                        }
                        else
                        { 
                           echo($origfilename." had an invalid file extension, not uploaded<br>"); 
                            $status = false;
                        } 
                }
                else
                { 
                    echo($origfilename." was not successfully uploaded<br />"); 
                    $status = false;
                } 
            } 
        } 
        return $status;
}

    function DupCheck($email, $owner, $roomid, $room )
    {
        if($email=='')
        {
            return false;
        }
        $result = do_mysqli_query("1",
                "
                    select providerid from provider where replyemail = '$email' and active='Y'
                "
                );
        if($row = do_mysqli_fetch("1",$result))
        {
            //Existing User Found - Do not Add to csv file
            $providerid = $row['providerid'];

            //See if user is already a member of the room
            $result = do_mysqli_query("1","
                select * from statusroom where providerid = $providerid and roomid = $roomid
                    ");
            //If not a member let's add as a member automatically
            if(!$row = do_mysqli_fetch("1",$result))
            {
                $result = do_mysqli_query("1","
                    insert into statusroom 
                    (roomid, room, owner, providerid, status, createdate, creatorid )
                    values
                    ($roomid, '$room', $owner, $providerid,'', now(), $owner )
                    ");
                
            }
            
            
            return false;
        }
        return true; //No Dups
        
    }
    function CleanPhone( $phone )
    {
        $phone = str_replace( "(", "", $phone );
        $phone = str_replace( "/", "", $phone );
        $phone = str_replace( ")", "", $phone );
        $phone = str_replace( " ", "", $phone );
        $phone = str_replace( "-", "", $phone );
        $phone = str_replace( ".", "", $phone );
        if($phone[0]!='+' && $phone!='+1' && $phone!='')
        {
            $phone2 = '+1'.$phone;
        }
        else
            $phone2 = $phone;   
        
        return $phone2;
    }

?>