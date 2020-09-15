<?php
require_once("config-pdo.php");
require_once("colorscheme.php");

function AccountPlanCheck( $providerid )
{
    global $installfolder;
    
        $expiredays = 0;
        $active = '';
        $filesize = 0;
        $bandwidth = 0;
        $excessflag = "";
        $providername = "";
        $handle = "";
        $bandwidthplan = '';
        $limit = 4;
        $mode = "";
        $storagelimit = 0;
        $memberlimit = 0;
        $enterprise = "";
        $sponsor = "";
        $industry = "";
        $roomcreator = "";
        $broadcaster = "";
        $store = "";

        //No Msgplan check - Add one
        $result = pdo_query("1", "select * from msgplan where providerid = ?",array($providerid));
        if(!$row = pdo_fetch( $result)){

            if($_SESSION['enterprise']=='Y' || $_SESSION['enterprise']=='C'){
                
                $tier = 'N';
                
            } else {
                
                $tier = 'F';//Free
                
            }
            $result = pdo_query("1", 
              " 
                  insert into msgplan (providerid, planid, createdate, datestart, dateend, count, active, storage )
                  values (?, 'STD',
                   (select createdate from provider where providerid=?), 
                   (select createdate from provider where providerid=?), 
                   (select createdate from provider where providerid=?), 
                    1, ?,0)",
                    array($providerid,$providerid,$providerid,$providerid,$tier)
              );
            if($tier == 'F'){
                $mode = "free";
            }
        }
        
        
        /* MSGPLAN only gets populated for Enterprise Accounts 
         * 
         */
        $result = pdo_query("1","
            select msgplan.id, msgplan.planid, msgplan.datestart,  
            date_format(msgplan.dateend,'%m/%d/%y') as dateend,
            msgplan.count,
            msgplan.active, msgplan.storage, msgplan.memberlimit,
            timestampdiff( DAY, now(), msgplan.dateend ) as expiredays,
            timestampdiff( DAY, provider.createdate, now() ) as accountage,
            provider.active as activeaccount, provider.replyemail, provider.sponsor,
            provider.providername, provider.enterprise, provider.handle,
            provider.trackerid, provider.roomcreator, provider.broadcaster, provider.store,
            provider.web,
            
            msgplan.bandwidthplan,
            (select industry from sponsor where sponsor = provider.sponsor) as industry
            from msgplan
            left join provider on msgplan.providerid = provider.providerid
            where msgplan.providerid=?  order by dateend desc limit 1  
            ",array($providerid));
        if($row = pdo_fetch($result)){

            $providername = $row['providername'];
            $handle = $row['handle'];
            $trackerid = $row['trackerid'];
            $roomcreator = $row['roomcreator'];
            $broadcaster = $row['broadcaster'];
            $store = $row['store'];
            $web = $row['web'];
            $expiredays = $row['expiredays'];
            $bandwidthplan = rtrim($row['bandwidthplan']);
            $planid = rtrim($row['planid']);
            $active = $row['active'];
            $datestart = $row['datestart'];
            $dateend = $row['dateend'];
            $storagelimit = $row['storage']*250;
            $limit = 100;
            if($row['storage']>1){
                $limit = 0;
            }
            $memberlimit = $row['memberlimit'];
            //$active = N means unpaid
            if($row['enterprise']=='Y'){
                $enterprise = 'Enterprise';
            }
            $sponsor = $row['sponsor'];
            $industry = $row['industry'];
            $enterprise = $row['enterprise'];
            //First Level Plan then $50 for each Level
            
            if($active == 'F'){
                $limit = 4;
                $storagelimit = 0;
                $mode = "free";
                $dateend = "";
                $expiredays = 0;
                $memberlimit = 0;
            }
            $limit = $limit + $storagelimit;
        }


        //Compute Current Filesize from Filelib - Can have 0 Views
        $result = pdo_query("1", 
           "select sum(filesize) as filesize from filelib 
            where filelib.providerid = ? and status='Y' 
           ",array($providerid));
        if($row = pdo_fetch($result)){
            $filesize = round(($row['filesize'])/1000000000,2);
        } 

        
        $result = pdo_query("1", 
           "select sum(views*filesize) as bandwidth from fileviews 
            where fileviews.providerid = ? and status='Y' and viewdate >= ?",
                array($providerid, $datestart)
           );
        if($row = pdo_fetch($result)){
            
            $bandwidth = round(($row['bandwidth'])/1000000000,2);
        } 

        //Compute Excess
        if($filesize > $limit || $bandwidth > $limit){
            $excessflag = 'Y';
        } else {
        }
        if($enterprise=='Y'  && $active == 'N'){
            $mode = 'unpaid';
        }
        if($enterprise=='C' && $active == 'N'){
            $mode = 'unpaidcommercial';
        }
        if($enterprise=='C' && $active == 'Y' && $excessflag == 'Y'){
            $mode = 'excess';
        }
        if($expiredays < 0 && $active == 'Y'){ 

            $mode = 'renew';
        }
        if($active == 'F' && $excessflag == 'Y'){
            $mode = 'free_exceeded';
        }
        if($active == 'Y' && $excessflag == 'Y'){
            $mode = 'excess';
        }
        
        if($mode == ''){
            $mode = 'upgrade';
        }
        if($enterprise=='Y'  && $expiredays < 30 && $expiredays > 0 ){
            $mode = 'expiring';
        }
        
        
        $plan['dateend'] = $dateend;
        $plan['expiredays'] = $expiredays;
        $plan['active']=$active;
        $plan['mode'] = $mode;
        $plan['bandwidth'] = $bandwidth;
        $plan['bandwidthplan'] = $bandwidthplan;
        $plan['planid'] = $planid;
        $plan['filesize'] = $filesize;
        $plan['excessflag'] = $excessflag;
        $plan['providername'] = $providername;
        $plan['handle']=$handle;
        $plan['limit'] = $limit;
        $plan['memberlimit'] = $memberlimit;
        $plan['enterprise'] = $enterprise;
        $plan['sponsor'] = $sponsor;
        $plan['industry'] = $industry;
        $plan['trackerid'] = $trackerid;
        $plan['roomcreator'] = $roomcreator;
        $plan['broadcaster'] = $broadcaster;
        $plan['store'] = $store;
        $plan['web'] = $web;
        return (object) $plan;
}
function AccountStatusCheck($pid)
{
    global $appname;
    global $global_textcolor;
    global $global_background;
    
    $costexempt = "";
    $result = pdo_query("1","select costexempt, datediff(now(), createdate) as diff, enterprise from provider where providerid=? ",array($pid));
    if($row = pdo_fetch($result)){
        $costexempt = $row['costexempt'];
        $diff = $row['diff'];
        $enterprise = $row['enterprise'];
    }
    
    //turn off
    if( $_SESSION['superadmin']!='Y' && $enterprise!='Y' && $enterprise!='C' ){// && (costexempt =='Y' || $diff > 2 )){
        return true;
    }
    $include_free = false;
    $source = 'E';
    $content = StoreFront($pid, $include_free, $source );
    if($content == ""){
        return true;
    }
    
    echo "</head>
           <body 
           style='color:$global_textcolor;background-color:$global_background;padding:20px;
           background:url(../img/background-wool-gray2.jpg);background-size:cover;background-repeat:no-repeat;' 
           >";
    echo "$content";
    echo "</body></html>";
    return false;
}
function OpenStoreFront()
{
    $include_free = true;
    StoreFront( $_SESSION['pid'],$include_free,"E");
}
function StoreFront($pid, $include_free, $source)
{
    global $appname;
    global $applogo;
    global $enterpriseapp;
    global $rootserver;
    global $installfolder;
    global $global_activetextcolor;
    global $global_background;
    global $global_textcolor;
    global $global_titlebar_color;
    
    $content = "";
    //$include_free = "";// undefined ?
    
    $plan = AccountPlanCheck($pid);
    
    
    $storeoptions = StoreOptions($plan);

    
    if($include_free && $plan->mode == 'free' ){
        
        /* 
         * 
         * UPGRADE FROM FREE ACCOUNT 

         * 
         *          */
        $ad = " $enterpriseapp is a premium version of $appname which allows you establish your own private social media following. You do this by 
            creating a personalized domain, setting up a website,
            creating a private partitioned social media space, enabling monetization, having unrestricted chat history, and
            having an increased storage capacity (100GB).
            <br><br>
            Unlike a static website, $enterpriseapp promotes engagement by adding a full social media platform to your websites with mobile app capability.
            <br><br>
             $enterpriseapp removes the 60 day limit on chat messages. Only a SocialVision account may be used for business purposes.
            <br><br>
            Use the one-time use coupon 'DEMO101' to start your 7 day free trial.
            ";
        $adcontent = AdFormat("Upgrade to $enterpriseapp",$ad).
        $storeoptions->coupon.
        $storeoptions->contact.
        $storeoptions->continue;
    
        $content  .= $adcontent;
        
        if($source == 'E'){
            $content  .= " 
                $storeoptions->script
            ";
        }
        return $content;
    }
    if($source!='E' && ($plan->planid == 'DEMO' || $plan->planid == 'STD') ){
    
        $content  .= " 
        <div class='tipbubble pagetitle3' style='background-color:$global_background;color:$global_textcolor;z-index:100;padding:20px;text-align:center;margin:auto'>
            $storeoptions->planheader
            <br><br>
            This free trial is expiring soon. Please visit our website store to upgrade your subscription to avoid a service interruption.  
            If your trial expires, you will lose access to your websites and domain.
            <br><br>
            $storeoptions->featureset
            
        </div>
        <br><br>
        <center>
        <a href='$rootserver/$installfolder/host.php?f=_store&h=app&p=$_SESSION[pid]' target=_blank >
        <div class='pagetitle3 divbuttontilebar2 tapped2' id='' 
            style='background-color:$global_titlebar_color;color:white'>
                                    Website Store
        </div>
        </a>
        </center>
        <hr>
        $storeoptions->downgrade
        $storeoptions->continue 
        ";
        return $content;
    } else
    if($plan->mode == 'expiring' && $source!='E' && $plan->planid == 'STD' ){
    
        $content  .= " 
        <div class='tipbubble pagetitle3' style='background-color:$global_background;color:$global_textcolor;z-index:100;padding:20px;text-align:center;margin:auto'>
            $storeoptions->planheader
            <br><br>
            This account has an active subscription.
            
        </div>
        
        $storeoptions->contact
        $storeoptions->continue
        ";
        return $content;
    } else
    if($plan->mode == 'expiring' && $source!='E' && $plan->planid == 'PREMIUM' ){
    
        $content  .= " 
        <div class='tipbubble pagetitle3' style='background-color:$global_background;color:$global_textcolor;z-index:100;padding:20px;text-align:center;margin:auto'>
            $storeoptions->planheader
            <br><br>
            This account has an active subscription for the current month.
            
        </div>
        
        $storeoptions->contact
        $storeoptions->continue
        ";
        return $content;
    } else
    if($plan->mode == 'expiring' && $source!='E' ){
    
        $content  .= " 
        <div class='tipbubble pagetitle3' style='background-color:$global_background;color:$global_textcolor;z-index:100;padding:20px;text-align:center;margin:auto'>
            $storeoptions->planheader
            <br><br>
            $plan->planid<br>
            This account is expiring soon. 
            Please use Paypal below to upgrade the subscription to avoid service interruption. Or enter 
            a provided Coupon code. Contact your sales rep to receive an invoice instead.
            
        </div>
        
        $storeoptions->coupon
        $storeoptions->paypal
        $storeoptions->paypal3
        $storeoptions->contact
        $storeoptions->continue
        ";
        return $content;
    }
    if($plan->mode == 'free_exceeded' ){
        BlockDownloads();
    
        $content  .= " 
        <div class='tipbubble pagetitle3' style='background-color:$global_background;color:$global_textcolor;z-index:100;padding:20px;text-align:center;margin:auto'>
            $storeoptions->planheader
            <br><br>
            This account has exceeded the limits of the free tier.
            Please use Paypal below to start a new subscription to gain access. Or enter 
            a provided Coupon code. Contact your sales rep to receive an invoice instead.
        </div>
        
        $storeoptions->paypal
        $storeoptions->paypal2
        $storeoptions->coupon
        $storeoptions->contact
        $storeoptions->continue
        ";
        if($source == 'E'){
            $content  .= " 
                $storeoptions->script
                ";
        }
        return $content;
    }
    if($plan->mode == 'upgrade' && $source!='E' ){
    
        $content  .= " 
        <div class='tipbubble pagetitle3' style='background-color:$global_background;color:$global_textcolor;z-index:100;padding:20px;text-align:center;margin:auto'>
            $storeoptions->planheader
            <br><br>
            This account is within the limits of your plan. If you wish to upgrade your features, please visit the website store.
            <br><br>
            $storeoptions->featureset
            
        </div>
            <br><br>
            <center>
            <a href='$rootserver/$installfolder/host.php?f=_store&h=app&p=$_SESSION[pid]' target=_blank >
            <div class='pagetitle3 divbuttontilebar2 tapped2' id='' 
                style='background-color:$global_titlebar_color;color:white'>
                                        Website Store
            </div>
            </a>
            </center>
            <br><br>
        
        $storeoptions->continue
        ";
        return $content;
    }

    
    if($plan->mode == 'unpaid' ){
        BlockDownloads();
    
        $content  .= " 
        <div class='tipbubble pagetitle3' style='background-color:$global_background;color:$global_textcolor;z-index:100;padding:30px;text-align:center;margin:auto'>
            <a href='$rootserver'>
            <img class='icon50' src='$applogo' style='padding:10px;' />
            </a>
            <br><br>
            
            <span class='pagetitle2'  style='color:$global_textcolor'>
            Hello $plan->handle, welcome to the $appname $enterpriseapp Signup. Visit our website store to
            activate your premium free trial account.
            </span>
            <br><br>
            <br><br>
            <a href='$rootserver/$installfolder/host.php?f=_store&h=app&p=$_SESSION[pid]' target=_blank >
            <div class='pagetitle3 divbuttontilebar2 tapped2' id='' 
                style='background-color:$global_titlebar_color;color:white'>
                                        Website Store
            </div>
            </a>
            <br><br>
            $storeoptions->downgrade
        ";
        if($source == 'E'){
            $content  .= " 
                $storeoptions->script
                ";
        }
        return $content;
    }
    if($plan->mode == 'renew' && $plan->enterprise == 'Enterprise' && $plan->industry =='' ){
        
        BlockDownloads();
    
        $content  .= " 
        <div class='tipbubble pagetitle3' style='z-index:100;padding:20px;text-align:center;margin:auto'>
            $storeoptions->planheader
            <br><br>
            This enterprise account has an expired subscription. 
            Please use Paypal below to start a new subscription to gain access.
            Or enter a provided Coupon code.  Contact your sales rep to receive an invoice instead.  
        </div>
        
        $storeoptions->paypal
        $storeoptions->paypal2
        $storeoptions->coupon
        $storeoptions->contact
        $storeoptions->continue
        ";
        if($source == 'E'){
            $content  .= " 
                $storeoptions->script
                ";
        }
        return $content;
    }
    if($plan->mode == 'renew' && $plan->enterprise == 'Enterprise' && $plan->industry !=='' ){
        
        BlockDownloads();
    
        $content  .= " 
        <div class='tipbubble pagetitle3' style='background-color:$global_background;color:$global_textcolor;z-index:100;padding:20px;text-align:center;margin:auto'>
            $storeoptions->planheader
            <br><br>
            This enterprise account has an expired subscription. Contact your Sales representative 
            regarding your invoice.
        </div>
        
        $storeoptions->coupon
            
        $storeoptions->contact
        $storeoptions->continue
        ";
        if($source == 'E'){
            $content  .= " 
                $storeoptions->script
                ";
        }
        return $content;
    }    
    if($plan->mode == 'renew' && $plan->enterprise !== 'Enterprise' ){
        
        BlockDownloads();
        
    
        $content  .= " 
        <div class='tipbubble pagetitle3' style='background-color:$global_background;color:$global_textcolor;z-index:100;padding:20px;text-align:center;margin:auto'>
            $storeoptions->planheader
            <br><br>
            This account has an expired subscription. Please visit the Store to renew your subscription.
        </div>
            <br><br>
            <br><br>
            <center>
            <a href='$rootserver/$installfolder/host.php?f=_store&h=app&p=$_SESSION[pid]' target=_blank >
            <div class='pagetitle3 divbuttontilebar2 tapped2' id='' 
                style='background-color:$global_titlebar_color;color:white'>
                                        Website Store
            </div>
            </a>
            </center>
            <br><br>
            $storeoptions->downgrade
        
        $storeoptions->continue
        ";
        if($source == 'E'){
            $content  .= " 
                $storeoptions->script
                ";
        }
        return $content;
    }    
    if($plan->mode == 'excess' ){
    
        BlockDownloads();
        $content  .= " 
        <div class='tipbubble pagetitle3' style='background-color:$global_background;color:$global_textcolor;z-index:100;padding:20px;text-align:center;margin:auto'>
            $storeoptions->planheader
            <br><br>
            This account has exceeded the limits of the current subscription. 
            Please use Paypal below to start a new subscription to gain access. Or enter 
            a provided Coupon code.
        </div>
        $storeoptions->paypal3
        $storeoptions->coupon
        $storeoptions->contact
        $storeoptions->continue
            
        ";
        if($source == 'E'){
            $content  .= " 
                $storeoptions->script
                ";
        }
        return $content;
    }
    
    return $content;
}
function BlockDownloads()
{
        pdo_query("1"," 
            update provider set blockdownload = 'Y'
            where providerid = ?
                ",array($_SESSION['pid']));
}
function StoreOptions($plan)
{
    global $appname;
    global $applogo;
    global $rootserver;
    global $installfolder;
    global $global_textcolor;
    global $global_background;
    global $global_activetextcolor;
    global $global_titlebar_color;
    global $startupphp;
    global $customsite;
    global $enterpriseapp;
    
    $accountstatus = "Upgrade $enterpriseapp";
    if($plan->planid == 'premium'){
        $accountstatus = "Account Status";
    }
    
    $planheader = "
            <b>
            <div class='pagetitle' style='color:$global_textcolor'>
            <img class='icon50' src='$applogo' style='padding:10px;' />
            <br>
            $accountstatus
            </div>
            <br>
            ";
    
    $featureset = "";
    if($plan->roomcreator=='N'){
        $featureset .= "Upgrade to Room Creator-- ";
    }
    if($plan->broadcaster=='N'){
        $featureset .= "Upgrade to Broadcaster-- ";
    }
    if($plan->web=='N'){
        $featureset .= "Upgrade to Website Creator-- ";
    }
    if($plan->store=='N'){
        $featureset .= "Add your own Website Store-- ";
    }
    if($featureset!=''){
        $featureset = "UPGRADE OPTIONS: ".$featureset;
    }
    
    if($plan->planid!='DEMO'){
        
        $planheader .= "
            $plan->providername $plan->handle<br> 
            $plan->sponsor $plan->industry 
            ";
        
        if($plan->filesize>0){
            $planheader .= "File Storage Used: $plan->filesize GB<br>";
        }
        if($plan->bandwidth>0){
            $planheader .= "Download Bandwidth Used: $plan->bandwidth GB<br>";
        }
        $planheader .= "
                Limit: $plan->limit GB<br>
                    ";
    }
    
    
    if($plan->dateend!='' && $plan->expiredays > 0){
        $planheader .= "
            Expires: $plan->dateend $plan->expiredays days<br>
        ";
    } else 
    if($plan->expiredays < 0 ){
        $planheader .= "Expired<br>";
    }
    $planheader .= "</b>";
    
    
    $contact = "
        <br><br>
        <center>
        
        <a href='mailto:sales@brax.me'>
            <div class='divbuttontext' style='background-color:$global_titlebar_color;font-family:helvetica;width:100%;min-width:300px;text-align:center;color:$global_textcolor'>
                Email Customer Service        
            </div>
        </a>
        </center>
        <br>
        
        ";
    $contact = "";
    
    $continue = "
        <br><br>
        <center>
        <a href='$rootserver/$startupphp'>
            <div class='divbuttontext pagetitle2a' style='background-color:$global_titlebar_color;margin:auto;text-align:center;color:$global_textcolor'>
                Continue
            </div>
        </a>
        <br><br>
        <br><br>
        <a href='$rootserver/$startupphp?a=logout'>
            <div class='divbuttontext pagetitle2a' style='background-color:$global_titlebar_color;margin:auto;text-align:center;color:$global_textcolor'>
                Logout
            </div>
        </a>
        </center>
        <br><br><br>
        ";
    
    $script = "
                <script>
                    try {
                        localStorage.removeItem('swt');
                        localStorage.removeItem('password');
                        localStorage.removeItem('pw');
                        localStorage.removeItem('lchat');
                        localStorage.removeItem('chat');

                    } catch(err) {
                    }
                </script>
                ";
    //script off
    $script = "";


    $redeem = "Redeem coupons before making a payment";
    if($plan->mode == 'unpaid'){
        $redeem = '';
    }
    
    $coupon = "
        <br>
        
        <div style='width:100%;min-width:300px;text-align:center'>
            <div class='mainfont' style='max-width:500px;margin:auto;color:$global_textcolor'><b>Redeem a Coupon Code</b>
            <br></div>

            <form action='$rootserver/$installfolder/coupon.php' method='post' target='_top'>
            <input class='dataentry' type='text' name='couponcode' value='' placeholder='Coupon Code' style='padding:5px;height:35px;max-width:100px' >
            <input type='hidden' name='providerid' value='$_SESSION[pid]'>
            <input type='submit'  value='Apply' style='background-color:$global_titlebar_color;color:white;height:45px;padding:10px;cursor:pointer'>
            <div class='pagetitle3' style='color:$global_textcolor'>$redeem</div>
            </form>

        
        </div>
        ";
    if($plan->mode!='free' && $plan->mode!='free_exceeded'){
    $downgrade = "
        <br>
        <br>
        
        <div style='width:100%;min-width:300px;text-align:center'>
            <div class='mainfont' style='max-width:500px;margin:auto;color:$global_textcolor'><b>Downgrade Account to Free Tier</b>
            <br></div>

            <form action='$rootserver/$installfolder/downgrade.php' method='post' target='_top'>
            <input type='hidden' name='providerid' value='$_SESSION[pid]'>
            <input type='submit'  value='Downgrade' style='background-color:$global_titlebar_color;color:white;height:45px;padding:10px;cursor:pointer'>
            <div class='smalltext' style='margin:auto;max-width:200px'>Warning: This will remove access to domains, websites and private channels previously created.</div>
            </form>
        <br>
        <br>

        
        </div>
        ";
    }
    
    if($plan->enterprise == 'Y'){
        //$paypal = $paypale;
        //$paypal2 = $paypal2e;
        $paypal2 = '';
        $paypal3 = "";
    }
    
    
    $array['coupon']=$coupon;
    $array['downgrade']=$downgrade;
    $array['script']=$script;
    $array['contact']=$contact;
    $array['planheader']=$planheader;
    $array['continue']=$continue;
    $array['featureset']=$featureset;
    return( (object) $array );
}




function TokenStoreOptions()
{
    global $appname;
    global $rootserver;
    global $installfolder;
    global $global_textcolor;
    global $global_background;
    global $global_activetextcolor;
    global $global_titlebar_color;
    global $startupphp;
    
    $body = "
            <b>
            <div class='pagetitle'>
            <img class='icon50' src='../img/logo-b2.png' style='padding:10px;' />
            <br>
            Brax Token Store</div><br>
            </b>
            ";
    
    
    $continue = "
        <br><br>
        <center>
        <div class='divbuttontext pagetitle2a' style='background-color:$global_titlebar_color;margin:auto;text-align:center;color:$global_textcolor'>
            <a href='$rootserver/$startupphp'>Continue</a>
        </div>
        <br><br>
        <br><br>
        <div class='divbuttontext pagetitle2a' style='background-color:$global_titlebar_color;margin:auto;text-align:center;color:$global_textcolor'>
            <a href='$rootserver/$startupphp?a=logout'>Logout</a>
        </div>
        <br><br><br>
        </center>
        ";
    
    $paypal = "
        <br><br>
        
        <div style='width:100%;min-width:300px;text-align:center'>
            <div class='pagetitle2' style='max-width:500px;margin:auto;color:black'>
            100 Pack Brax Tokens $9.99
            </div>

<form action='https://www.paypal.com/cgi-bin/webscr' method='post' target='_top'>
<input type='hidden' name='cmd' value='_s-xclick'>
<input type='hidden' name='hosted_button_id' value='HYLMGE38QSX3L'>
<input type='image' src='https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif' border='0' name='submit' alt='PayPal - The safer, easier way to pay online!'>
<img alt='' border='0' src='https://www.paypalobjects.com/en_US/i/scr/pixel.gif' width='1' height='1'>
</form>
        
        </div>
        ";
    
    $paypal2 = "
        <br><br>
        
        <div style='width:100%;min-width:300px;text-align:center'>
            <div class='pagetitle2' style='max-width:500px;margin:auto;color:black'>
            250 Pack Brax Tokens $24.99
            </div>

<form action='https://www.paypal.com/cgi-bin/webscr' method='post' target='_top'>
<input type='hidden' name='cmd' value='_s-xclick'>
<input type='hidden' name='hosted_button_id' value='3UFX6UXKWGEES'>
<input type='image' src='https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif' border='0' name='submit' alt='PayPal - The safer, easier way to pay online!'>
<img alt='' border='0' src='https://www.paypalobjects.com/en_US/i/scr/pixel.gif' width='1' height='1'>
</form>
        
        </div>
        ";

    $paypal3 = "
        <br><br>
        
        <div style='width:100%;min-width:300px;text-align:center'>
            <div class='pagetitle2' style='max-width:500px;margin:auto;color:black'>
            500 Pack Brax Tokens $49.99
            </div>

<form action='https://www.paypal.com/cgi-bin/webscr' method='post' target='_top'>
<input type='hidden' name='cmd' value='_s-xclick'>
<input type='hidden' name='hosted_button_id' value='D4ZZMTRPMM9EU'>
<input type='image' src='https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif' border='0' name='submit' alt='PayPal - The safer, easier way to pay online!'>
<img alt='' border='0' src='https://www.paypalobjects.com/en_US/i/scr/pixel.gif' width='1' height='1'>
</form>
        
        </div>
        ";
    

    $paypaltest = "
        <br><br>
        
        <div style='width:100%;min-width:300px;text-align:center'>
            <div class='pagetitle2' style='max-width:500px;margin:auto;color:$black'>
            10 Pack Brax TEST Tokens FREE
            </div>

<form action='$rootserver/prod/tokenstore-test.php' method='post' target='_top'>
<input type='image' src='https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif' border='0' name='submit' alt='PayPal - The safer, easier way to pay online!'>
</form>
        
        </div>
        ";
    
    
    $array['paypal']=$paypal;
    $array['paypal2']=$paypal2;
    $array['paypal3']=$paypal3;
    $array['paypaltest']=$paypaltest;
    $array['continue']=$continue;
    return( (object) $array );
}


function AdFormat($adtitle, $adtext)
{
    global $global_background;
    global $global_textcolor;

        $ad = "
                    <div class='pagetitle3' 
                        style='padding:20px;text-align:center;margin:auto;max-width:80%px;width:600px;color:$global_textcolor;background-color:transparent'>
                        <div class='circular3' style=';overflow:hidden;margin:auto'>
                            <img class='' src='../img/agent.jpg' style='width:100%;height:auto' />
                        </div>
                        <div class='tipbubble pagetitle2a' style='padding:30px;color:black;background-color:whitesmoke'>
                            <b>$adtitle</b><br><br>
                            <span class='mainfont' style='color:black'>
                            $adtext
                            </span>
                            <br><br>
                        </div>
                    </div>
                    ";
        return $ad;
}

?>