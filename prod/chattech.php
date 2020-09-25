<?php
session_start();
require_once("config-pdo.php");
require_once("internationalization.php");

    //$replyflag = tvalidator("PURIFY",$_POST[replyflag]);
    $lasttime = @tvalidator("PURIFY",$_POST['lasttime']);
    $providerid = @tvalidator("ID",$_POST['providerid']);
    $handle = @tvalidator("PURIFY",$_SESSION['handle']);
    
    /*
    $result = pdo_query("1",
        "
        update notification set displayed = 'Y' where notifytype='CP' and displayed!='Y'
        ");
    */

 
    $add = "<img class='unreadicon' src='../img/add-new-128.png' style='height:12px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;' />";
    $dot = "<img class='unreadicon' src='../img/graydot-128.png' style='height:3px;width:auto;padding-left:10px; padding-top:0;padding-right:2px;padding-bottom:0px;' />";
    $flag = "<img class='chatalert' src='../img/check-yellow-128.png' style='height:15px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;' />";
    $flagred = "<img class='chatalert' src='../img/check-red-128.png?2' style='height:15px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;' />";

    $braxchat = "<img src='../img/braxchat.png' style='height:30px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;margin:0' />";
    $braxchat2 = "<img src='../img/braxchat-square.png' style='position:absolute;top:0px;height:20px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;margin:0' />";

    //<div class='divbutton3 divbutton_unsel textsend'>SMS Poke - Hey, Testing Only!</div>
    $id = $_SESSION['replyemail'];
    if( $handle!=''){
        $id = $handle;
    }
    $list = "
            ";
    
    
    $modeaction = 'chatinvite';
    
    
    $i1 = 0;
    $count = 0;
    $chatid = "";
        
        
        /*
         * 
         *  Search for Current Available Tech Support
         */
    
         //To Randomize:
         // order by rand()
        $result = pdo_query("1",
        "
            select distinct provider.replyemail, provider.providername as name, provider.companyname, 
            provider.alias, provider.providerid, provider.handle,
            provider.avatarurl, provider.createdate, provider.techname
            from provider where techsupport = 'Y' and active='Y' 
            order by providerid desc
        ",null
            

        );
             
    $list = 
        "<div class='pagetitle2a gridstdborder' 
            style='background-color:$global_titlebar_color;padding-top:0px;
            padding-left:20px;padding-bottom:3px;
            text-align:left;color:white;margin:0'> 
            
            <img class='icon20 settingsbutton mainbutton' Title='Back to Menu' src='../img/Arrow-Left-in-Circle-White_120px.png' 
                style='' />
            &nbsp;
            $icon_braxchat2
            $menu_techsupport
            <br>
        </div>
        ";
    $list .=   "   <div style='color:$global_textcolor;margin:auto;text-align:center'>";
    
    while($row = pdo_fetch($result))
    {
        
        $id = $row['replyemail'];
        if( $row['handle']!=''){
            $row['replyemail']='';
            $id = $row['handle'];
        }
        if($id == ''){
            continue;
        }
        
        $name = substr("$row[name]", 0, 30);
        if($count == 0){
            $list .=
            "   
                    <br><br><br>
                    <span class='pagetitle' style='color:$global_textcolor'>$menu_techsupport<br></span><br>
                
            
               ";
            if($_SESSION['language']=='english'){
            $list .=
            "   
                    <span class='pagetitle3' style='color:$global_textcolor'>Available Staff</span><br>
                    <span class='smalltext2' style='color:$global_textcolor'>Chat Conversations may not be live.</span><br><br>
                
            
               ";
            }
            
        }
        $count++;
        $list .=
            "
                    <div class='roomlistbox pagetitle3 $modeaction tapped2 gridstdborder' 
                        data-chatid='$chatid' 
                        style='position:relative;display:inline-block;
                        overflow:hidden;
                        color:black;background-color:white;
                        cursor:pointer;font-weight:300;margin-bottom:10px'
                        data-email=''
                        data-handle='$row[handle]'
                        data-name='Tech Support' 
                        data-techsupport='Y'
                        data-passkey = ''
                        data-mode='S'                        
                    >
                        <div style='background-color:#58585B;text-align:center'>
                        <img class='chatlistphoto1' src='$rootserver/img/techsupport.jpg' style='width:100%;height:auto' />
                            </div>
                        <div class='smalltext' style=';left:0;text-align:center;overflow:hidden'>
                            <br>
                            <b>Queue $count</b><br>
                            $row[techname]<br>

                        </div>
                    </div>
                
            
               ";
            
        
        
        
    }
   /*
    * This idea of launching to the chat automatically (if unread) seems to not work right
    * if you want to go to some other chat discussion
    * If you have two discussions going, it may become difficult to switch back and forth
    * 
    */
    
    if($count>0)
    {
        $list .="   </div></div>
                </div>";
    }
    $list .= "<br><br><div class='roomjoin pagetitle2' data-handle='#techsupport' data-mode='J' data-caller='none' style='cursor:pointer;margin:auto;text-align:center;color:$global_activetextcolor'>FAQ Common Questions</div> ";

    if($count == 0)
    {
    }
    
    $arr = array('list'=> "$list",
                 'chatid'=> "$chatid"
                );
        
    
    echo json_encode($arr);
     
?>