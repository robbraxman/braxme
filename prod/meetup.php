<?php
session_start();
require_once("config-pdo.php");

    //$replyflag = tvalidator("PURIFY",$_POST[replyflag]);
    $providerid = @tvalidator("ID",$_POST['providerid']);
    $mode = @tvalidator("PURIFY",$_POST['mode']);
    
    if($mode == 'A') //Add Connect Request
    {
        $appname = @tvalidator("PURIFY",$_POST['appname']);
        $appidentity = @tvalidator("PURIFY",$_POST['appidentity']);
        $greeting = @tvalidator("PURIFY",$_POST['greeting']);
        
        $appidentity= FormatHandle( $appname, $appidentity );
        
        pdo_query("1"," 
            delete from appmeetup where replyemail = '$_SESSION[replyemail]' and
                appname = '$appname' and appidentity='$appidentity'
                ");
        
        pdo_query("1"," 
            insert ignore into appmeetup 
            ( replyemail, appname, appidentity, greeting, status, reqdate )
            values
            ( '$_SESSION[replyemail]', '$appname', '$appidentity', '$greeting', 'Y', now() )
                ");
            //$arr = array('list'=> "Test1"
                        //);
            //echo json_encode($arr);
            //exit();
        
    }
    if($mode == 'E') //Add Identity for Current User
    {
        $appname = @tvalidator("PURIFY",$_POST['appname']);
        $appidentity = @tvalidator("PURIFY",$_POST['appidentity']);
        
        $appidentity= FormatHandle( $appname, $appidentity );
        
        pdo_query("1"," 
            delete from appidentity where replyemail = '$_SESSION[replyemail]' and
                appname = '$appname' and appidentity='$appidentity'
                ");
        
        $result = pdo_query("1"," 
            insert into appidentity 
            ( replyemail, appname, appidentity, status )
            values
            ( '$_SESSION[replyemail]', '$appname', '$appidentity', 'Y' )
                ");
        if(!$result)
        {
            //Someone stole identity!
            $list = "
                    <div style='background-color:gray;color:white'>
                       <span class='pagetitle2a' style='color:white;padding-left:10px;padding-right:10px;padding-top:5px;padding-bottom:5px'>Identity Problem Found</span>
                    </div>
                    <div style='background-color:white;padding-left:20px;padding-right:20px;padding-top:5px;padding-bottom:10px'>
                        Someone else is using this identity! <br><br>
                        Possible reasons:
                        <ul>
                        <li>
                        Either you mistyped the identity.
                        </li>
                        <li>
                        You are using this 
                        identity on a different account.
                        </li>
                        <li>
                        Someone
                        is misusing your social media identity. 
                        </li>
                        </ul>
                        <br>
                        Contact Tech Support if you feel that someone has misappropriated your social media ID.
                        <br><br>
                        <div class='divbutton3 meetuplist'>
                        Back to Find People
                        </div>
                    </div>
                    ";
            $arr = array('list'=> "$list"
                        );
            echo json_encode($arr);
            exit();
        }
    }
    if($mode == 'D') //Delete Identity
    {
        $appname = @tvalidator("PURIFY",$_POST['appname']);
        $appidentity = @tvalidator("PURIFY",$_POST['appidentity']);
        
        pdo_query("1"," 
            delete from appidentity where replyemail = '$_SESSION[replyemail]' and
                appname = '$appname' and appidentity='$appidentity'
                ");
        
    }
    if($mode == 'D1') //Delete Connect Request
    {
        $appname = @tvalidator("PURIFY",$_POST['appname']);
        $appidentity = @tvalidator("PURIFY",$_POST['appidentity']);
        
        pdo_query("1"," 
            delete from appmeetup where replyemail = '$_SESSION[replyemail]' and
                appname = '$appname' and appidentity='$appidentity'
                ");
        
    }
    if($mode == 'C') //Connect listed Request
    {
        //$targetid = @tvalidator("PURIFY",$_POST['targetid']);
        $id = @tvalidator("PURIFY",$_POST['id']);
        
        pdo_query("1"," 
            update appmeetup set status='N' where id = $id
                ");
        
        //Add to My Contact List
        $result = pdo_query("1"," 
            select providerid, providername, alias, handle, replyemail from provider where replyemail =
            (select replyemail from appmeetup where id =$id )
            and active = 'Y'
                ");
        if($row = pdo_fetch($result)){
            $targetid = $row['providerid'];
            $name = $row['providername'];
            if($row['alias']!=''){
                $name = $row['alias'];
            }
            $email = $row['replyemail'];
            $handle = '';
            if(strlen($row['handle']) > 1 )
            {
                $email = '';
                $handle = $row['handle'];
                
            }
            if($email!='' || $handle!='')
            {
                pdo_query("1","
                    insert ignore into contacts (providerid, contactname, email, sms, handle, friend, imapbox, source,createdate )
                    values ( $providerid, '$name', '$email', '', '$handle', 'Y', null, 'Z', now() )
                        ");
            }
        }
        
        //Add Me to their contact list
        $result = pdo_query("1"," 
            select providername, alias, handle, replyemail from
            provider where providerid = $providerid
                ");
        if($row = pdo_fetch($result))
        {
            $name = $row['providername'];
            if($row['alias']!=''){
                $name = $row['alias'];
            }
            $email = $row['replyemail'];
            $handle = '';
            if(strlen($row['handle']) > 1 )
            {
                $email = '';
                $handle = $row['handle'];
                
            }
            if($email!='' || $handle!='')
            {
                pdo_query("1","
                    insert ignore into contacts (providerid, contactname, email, sms, handle, friend, imapbox, source )
                    values ( $targetid, '$name', '$email', '', '$handle', 'Y', null, 'Z' )
                        ");
            }
        }
        $mode = '';
        //exit();
    }
    
    if($mode == 'CX') //Remove Connect Request
    {
        //$targetid = @tvalidator("PURIFY",$_POST['targetid']);
        $id = @tvalidator("PURIFY",$_POST['id']);
        
        pdo_query("1"," 
            update appmeetup set status='X' where id = $id
                ");
        $mode = '';
        //exit();
    }

    /*************************/
    /* Manage Supported Apps */
    /*************************/
    
    $applist = "
            <option value='periscope' >Periscope</option>
            <option value='facebook' >Facebook</option>
            <option value='instagram' >Instagram</option>
            <option value='snapchat' >Snapchat</option>
            <option value='twitter' >Twitter</option>
            <option value='youtube' >Youtube</option>
            <option value='tumblr' >Tumblr</option>
            <option value='snapchat' >Snapchat</option>
            <option value='telegram' >Telegram</option>
            <option value='meerkat' >Meerkat</option>
            <option value='reddit' >Reddit</option>
            <option value='google+' >Google+</option>
            <option value='ashley-madison' >Ashley-Madison</option>
            <option value='friendlife' >Friendlife</option>
            <option value='brax.me' >Brax.me</option>
            ";
    
    
    function FormatHandle( $appname, $appidentity ){
        //Add @ if not provided
        if(
            strtolower($appname) == 'instagram' ||
            strtolower($appname) == 'periscope' ||
            strtolower($appname) == 'tumblr' ||
            strtolower($appname) == 'snapchat' ||
            strtolower($appname) == 'friendlife' ||
            strtolower($appname) == 'telegram' ||
            strtolower($appname) == 'meerkat' ||
            strtolower($appname) == 'busker' ||
            strtolower($appname) == 'twitter' ||
            strtolower($appname) == 'brax.me' 
           ){
            if( $appidentity[0]!='@')
            {
                $appidentity = '@'.$appidentity;
                $appidentity = str_replace(" ","",$appidentity);
            }
        }
        return $appidentity;
        
    }
    /******************************/
    /* End- Manage Supported Apps */
    /******************************/
    $add = "<img class='icon20 tapped2' src='../img/add-circle-128.png' style='' />";
    $add2 = "<img class='icon20 tapped2' src='../img/add-circle-128.png' style='' />";
    $connect = "<img class='icon20 meetupadd tapped2' src='../img/add-circle-128.png' style='' />";
    $establish = "<img class='icon20 identityadd hidearea tapped2' src='../img/add-circle-128.png' style='' />";


    //<div class='divbutton3 divbutton_unsel textsend'>SMS Poke - Hey, Testing Only!</div>
   $result = pdo_query("1",
   "
       select appname, appidentity from appidentity where replyemail = '$_SESSION[replyemail]'
   ");
   $appidentities = "";
   while($row = pdo_fetch($result))
   {
       if($appidentities!=''){
           $appidentities .= "";
       }
       if($appidentities==''){
           $appidentities .= "";
       }
       $appidentities .= 
                        "<div class='identitydelete smalltext rounded' style='width:300px;margin:2px;background-color:#68809f;padding-left:10px;padding-top:5px;padding-bottom:5px;padding-right:10px;;cursor:pointer;color:white'
                         data-appname='$row[appname]' data-appidentity='$row[appidentity]'
                         >
                        $row[appidentity] on $row[appname]
                        </div>";
   }
   if( $appidentities !=''){
       $appidentities = "<br><span class='smalltext' >Listening for these Identities</span><br>".$appidentities."<br>";
   }
   if( $appidentities ==''){
       $appidentities = "<div class='smalltext' style='color:firebrick;width:500px;max-width:80%;max-width:90%'>"
               . "<br>Allow people to find you using your publicly known social media identities. "
               . "Contacts need to know "
               . "your social media handles in advance since this is not broadcasted.<br><br>"
               . "You are invisible to the requestor until you accept a connection. "
               . "</div>  "
               . "<br>";
   }
    $list = "
    <div class='gridstdborder formobile' style='background-color:#a1a1a4;color:white;padding-left:20px;padding-right:20px;padding-bottom:3px;margin:0;' >
        <img class='icon20' src='../img/brax-meetup-round-storm-128.png' 
             />
        <span class='pagetitle2a' style='color:white'>Find People</span> 
    </div>
    <div class='gridnoborder suspendchatrefresh' style='padding:0;margin:0;background-color:white'>
        <div style='padding:10px;text-align:left'>
            <span class='nonmobile pagetitle' style='font-weight:bold'>
                <img class='icon30' src='../img/brax-meetup-round-storm-128.png' 
                    style='top:5px' />
                <b>Find People on $appname</b>
            </span>
            <br><br>
            <div class='smalltext' style='width:500px;max-width:90%'>
                Connect with each other using publicly known social media handles/identities without
                revealing emails and phone numbers.
                <br><br>
                You can initiate chat by tapping on the accepted contact.
            </div>
            ";
   $result = pdo_query("1",
    
        "
        select 
        DATE_FORMAT(date_add(appmeetup.reqdate, interval (-7)*(60) MINUTE), '%b %d %h:%i%p') as reqdate, 
        provider.providername, provider.alias, provider.avatarurl, provider.companyname,
        provider.providerid,provider.handle,
        appmeetup.replyemail,
        appmeetup.appname,
        appmeetup.appidentity,
        appmeetup.greeting,
        appmeetup.id, 
        (select concat(concat(appidentity,' on '),appname) from appidentity where appidentity.appname = appmeetup.appname
         and appidentity.replyemail = appmeetup.replyemail 
         limit 1 ) as sourceappidentity
        from appidentity
        left join appmeetup on appidentity.appidentity = appmeetup.appidentity 
			and appidentity.appname = appmeetup.appname
        left join provider on provider.replyemail = appmeetup.replyemail and provider.active = 'Y'
        where appidentity.replyemail = (select replyemail from provider 
        where providerid = $providerid and active='Y')
        and appmeetup.status = 'Y'
        order by providername
        "
    );

    
    
    $i1 = 0;
    $count = 0;
    while($row = pdo_fetch($result))
    {
        $header = false;
        if($count == 0)
        {
            $list .= "
            
            <br><br>
            <div style='background-color:white;color:black'>
                <span class='pagetitle2a' style='color:steelblue;padding-left:0px;padding-right:10px;padding-top:5px;padding-bottom:5px'>
                    People who want to connect
                </span>
            </div>
            <br>
            ";
            
        }
        
        $providername = $row['providername'];
        if($row['alias']!=''){
            $providername = $row['alias'];
        }
        
        $count++;
        
        $avatar = "";
        
        $avatar = $row['avatarurl'];
        if($avatar == "$rootserver/img/faceless.png" || $avatar == ''){
            $avatar = "$rootserver/img/egg-blue.png";
        }

        $add2 = "<img class='icon20 meetupconnect tapped2' 
                data-name='$providername'
                data-id='$row[id]'
                src='../img/add-circle-128.png' 
                style='cursor:pointer;position:relative;top:0px;;width:auto;
                padding-top:0;padding-right:2px;padding-bottom:0px;' />";
        
        $delete = "<img class='icon20  meetupdelete tapped2' 
                data-name='$providername'
                data-id='$row[id]'
                src='../img/delete-circle-128.png' 
                style='cursor:pointer;position:relative;top:0px;width:auto;
                padding-top:0;padding-right:2px;padding-bottom:0px;' />";
        
        $source = $row['sourceappidentity'];
        if($source == '' && $row['handle']==''){
            $source = $row['replyemail'];
        } else
        {
            $source = $row['handle'];
        }
        
        

        $greeting = substr($row['greeting'],0,80);
        $list .= 
            "   
            <div class='smalltext gridstdborder chatlistbox'
                style='padding:5px;position:relative;display:inline-block;text-align:left;
                overflow:hidden;
                color:black;background-color:white;
                cursor:pointer;font-weight:300;margin-bottom:20px;word-wrap:break-word'>
                <div style='padding:10px;position:absolute;top:0px;left:0px;height:10px;width:100%;color:black;background-color:white;overflow:hidden'>
                </div>
                <div style='position:absolute;top:0px;left:0px;padding:5px;overflow:hidden'>
                    <img src='$avatar' style='height:120px;width:auto;display:inline' />
                    <div>$providername</div>
                    <div style='overflow:hidden'>$source</div>
                    <div>$greeting</div>
                    <br>
                    &nbsp;$add2 &nbsp;&nbsp; $delete
                </div>
            </div>

         ";
    }

        
    $list .= 
            "
            <br>
            <hr>
            <span class='pagetitle2a'
                style='color:black;padding-left:10px;
                padding-right:10px;padding-top:0px;padding-bottom:0px'>
                <span class='meetupconnectshow' style='cursor:pointer'>
                    <div class='divbutton6 mainfont'>
                        Send a Connect Request
                        <img class='icon15' src='../img/arrowhead-right-white-01-128.png' style='top:3px' />
                    </div>
                    <div class='smalltext' style='padding-left:20px;padding-top:10px'>
                        <br>
                        Find people via known social media identities. 
                    </div>
                </span>
            </span>
            
            <div style='background-color:white;padding-left:20px;padding-right:20px;padding-top:5px;padding-bottom:10px'>
                <span class='meetupconnectarea' style='display:none'>
                <div class='pagetitle2'><b>Send a Connect Request to Someone on $appname</b></div>
                    <br>I want to connect with<br>
                    <input class='mainfont connectappidentity' placeholder='Handle / Identity' type='text' size='20' style='max-width:80%' /> from 
                    <select class='connectappname'>
                    $applist
                    </select>
                    <br>
                    <input class='mainfont connectgreeting' placeholder='Greeting' type='text' size='30' /> 
                    $connect
                    <br><br>
                    <div class='smalltext' style='max-width:300px'>
                        Make sure the @handle or name is exactly as used in the social media app,
                        or the party will not see your request. Provide sufficient
                        information in your request to identify you to the other party. Otherwise, your request 
                        may be ignored.
                    
                    </div>
                </span>
            </div>
            ";
    
   /*
    * This idea of launching to the chat automatically (if unread) seems to not work right
    * if you want to go to some other chat discussion
    * If you have two discussions going, it may become difficult to switch back and forth
    * 
    */
    $result = pdo_query("1","
        select appname, appidentity from appmeetup where replyemail = '$_SESSION[replyemail]'
            and status in ('Y','X')
            ");
    $count = 0;
    while($row = pdo_fetch($result)){
        if($count == 0){
            $list .= "
                <br>
            <div style='background-color:white;color:black'>
                <span class='pagetitle2a' style='color:steelblue;padding-left:10px;padding-right:10px;padding-top:5px;padding-bottom:5px'>
                    <b>Pending outgoing requests</b>
                </span>
            </div>
            ";
            
        }
        $count++;
        $list .= "
            <div style='text-align:left'>
                <ul>
                        <li class='identitydeleterequest' style='cursor:pointer;color:firebrick'
                         data-appname='$row[appname]' data-appidentity='$row[appidentity]'
                         >
                        <u>$row[appidentity] on $row[appname]</u> 
                        </li>
                </ul>
            </div>
            ";
    }    
    //#72b6e4
    $list .= 
            "<br>
             <hr>
            <span class='pagetitle2a' style='color:steelblue;padding-left:10px;padding-right:10px;padding-top:0px;padding-bottom:0px'>
                <span class='identityaddshow' style='cursor:pointer'>
            
                    <div class='identityaddshow divbutton6'>Listen for connect requests
                            <img class='icon15' src='../img/arrowhead-right-white-01-128.png' style='top:3px' />
                    </div>
                </span>
            </span>
            <span class='identityarea' style='display:none'>
                <div class='hidearea' style='background-color:white;padding-left:20px;padding-right:20px;padding-top:5px;padding-bottom:10px'>
                   <span class='pagetitle3' style='color:black;padding-left:20px;padding-right:20px;padding-top:10px;padding-bottom:10px'>
                    <div class='pagetitle2'><b>Listen for Connect Requests</b></div>
                    <br>Enter your social media identity<br>
                   </span>
                    <input class='mainfont establishconnectappidentity' placeholder='Handle' type='text' size='20' style='max-width:80%' /> on
                    <select class='establishconnectappname'>
                    $applist
                    </select>
                    $establish
                    <br>
                    <br>
                    <span class='smalltext'>Tip: People misspell so enter all possible variations.</span>
                </div>
            </span>
            <div style='background-color:white;padding-left:20px;padding-right:20px;padding-top:5px;padding-bottom:5px'>
                $appidentities
            </div>
            ";
   
    
    
    
    
    
   /*
    * Recent Requests
    * 
    */
    $result = pdo_query("1","
        select 
        DATE_FORMAT(date_add(appmeetup.reqdate, interval (-7)*(60) MINUTE), '%b %d %h:%i%p') as reqdate, 
        provider.providername, provider.alias, provider.avatarurl, provider.companyname,
        provider.providerid, provider.handle,
        appmeetup.replyemail,
        appmeetup.appname,
        appmeetup.appidentity,
        appmeetup.greeting,
        appmeetup.id, appmeetup.status,
        (select concat(concat(appidentity,' on '),appname) from appidentity where appidentity.appname = appmeetup.appname
         and appidentity.replyemail = appmeetup.replyemail 
         limit 1 ) as sourceappidentity
        from appidentity
        left join appmeetup on appidentity.appidentity = appmeetup.appidentity 
			and appidentity.appname = appmeetup.appname
        left join provider on provider.replyemail = appmeetup.replyemail and provider.active = 'Y'
        where appidentity.replyemail = (select replyemail from provider 
        where providerid = $providerid and active='Y' and providername!='' )
        and datediff(curdate(), appmeetup.reqdate) < 30
        and appmeetup.status in ('N','X')
        order by appmeetup.reqdate desc
            ");
    $count = 0;
    while($row = pdo_fetch($result)){
        if($count == 0){
            $list .= "
                <br>
                <hr>
            <div style='background-color:white;color:black'>
                <span class='pagetitle2a' style='color:steelblue;padding-left:10px;padding-right:10px;padding-top:5px;padding-bottom:5px'>
                    Recent connect requests (last 30 days)
                </span>
            </div>
            ";
            
        }
        $count++;
        $id = $row['handle'];
        if($id == ''){
            $id = $row['replyemail'];
        }
        $status = "Pending";
        if( $row['status']=='X'){
            $status = "Rejected"; 
        }
        if( $row['status']=='N'){
            $status = "Accepted"; 
        }
        //$greeting = substr($row['greeting'],0,50);
        $greeting = $row['greeting'];
        $list .= "
            <div style='text-align:left;word-wrap:break-word;max-width:300px'>
                <ul>
                        <li class='gridstdborder shadow chatinvite' style='cursor:pointer;color:black;padding:10px'
                         data-appname='$row[appname]' data-appidentity='$row[appidentity]'
                         data-providerid='$row[providerid]' data-name='$row[providername]'    
                         data-mode ='S'
                         >
                            <img class='icon50' src='$row[avatarurl]'/><br><br>
                           $row[providername]  $id  $row[sourceappidentity]
                               <br><span class='smalltext' style='color:gray;'>$greeting</span>
                               <br><b>$status</b>
                        </li>
                </ul>
            </div>
            ";
    }    
    
    
    
    
    
    
    $list .="   
            </div></div>";

    $arr = array('list'=> "$list"
                );
        
    
    echo json_encode($arr);
     
?>