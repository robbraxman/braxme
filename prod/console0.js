    
    /* Console0 Functions */

    var ImapItems = ImapCount;
    var ImapLastRefresh = 1;
    var ImapFlag = "1";
    var ImapName = "";
    var ImapFolder = "INBOX";
    var inactivity = 0;
    var appStoreCheck = 0;
    var idleTime = 0;
    var idleTimeTrigger = 10;
    var xhr = null;
    var xhrload = null;
    var xhralerts = null;
    var xhrchat = null;
    var xhrapp = null;
    var FAST = 1;
    var SYNC = 0;
    var SentFlag = "N";
    var ReceivedFlag = "N";
    var ChatId = "";
    var ChannelId = ""
    var MaxPingCount = 100000;
    var PingCount = 0;
    var move_uid="";
    var move_folder="";
    var move_imap="";
    var lastloadmsg;
    var LastFolder="";
    var StandAloneMode = false;
    var MobileCapable = false;
    var MobileType = "";
    var MobileDivide = false;
    var DeviceCode = '';
    var Browser = "";
    var defaultAlbum = '';
    var TimedOut = false;
    var ToggleChatShow = false;
    var ToggleMembersShow = false;
    var ChatTraffic = 0;
    var ActiveSessionTime = 0;
    var LastCommentShareId='';
    var CordovaCommand = "Test";
    var Rotation = 0;
    var RotationLeft = 0;
    var RotationRight = 0;
    var nowAlerting = false;
    var lastcommentid;
    var lastcommentheaderid;
    
    var CurrentPanel=0;
    var LastPanel=0;
    var isActive = false;

    var video;
    var canvas;
    var ctx;
    var dragging = false;
    var startX,
        startY,
        tap;    

    var slowDownerCount = 0;
    var slowDownerLimit = 1;
    var iScore =0;
    var sized = false;
    
    var OKMessage = "&nbsp;&nbsp;-&nbsp;&nbsp;";//
    //var OKMessage = "<img class='buttonicon' src='../img/check-box-128.png' style='height:20px;width:auto;padding-top:0px;padding-bottom:0px;padding-right:10px;margin:0'  alt='No Alert' title='No Alert'/>";
    var TextFlag = "<img class='textalert' src='../img/flag-01-128.png' style='height:15px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;' />";
    var imapmovemenu =  null;//$( "#imapmovemenu" ).menu().hide();
    var photomenu = null;
    var mobileDevice = 0;
    var LastClock=0;
    var filesScrollPos = 0;
    var chatpasskey='';
    var chatinputpasskey = "";
    var cameraChatPrompt = false;
    var cameraRoomPrompt = false;
    var MobileZoomLevel = 1;
    var LoadingGIF = "<img class='icon50 restarthome' src='../img/loading-blue.gif' style='height:100px;margin:10px' />";
    var LoadingGIF2 = "<img class='icon50 restarthome' src='../img/loading.gif' style='height:100px;margin:10px' />";
    var backButton = "<img class='icon20' src='../img/Arrow-Left-in-Circle_120px.png' style='padding-left:10px' >";
    var popupwin = null;
    var visibleChatPanel = false;
    var videoActive = false;
    var TimerFieldCount = 0;
    var TryPin = "";
    var pintries = 0;
    var lastinnerwidth=0;
    var lastinnerheight=0;
    var lastCheckIn = 0;
    var audiostreamactive=false;
    var stdEntryHeight = 20;
    var SecondsCount = 0;
    var LastChatScrollBottom = 0;
    var chatScrollSuspend = false;
    var chatEditing = false;
    var CaseId = 0;
    var statusBarHeight = 0;
    var internetLost = 0;
    
        function ShowBanner()
        {
            setTimeout(function(){
                $('body').animate({scrollTop:$('.bannerflush').offset().top}, 'fast');
            },10);
            
        }

        function Sizing( activeflag )
        {
            var innerHeight = 0;
            var innerWidth = 0;
            var mobileplus = 0;
            var headingheight = 0;
            
            if(pinlock ==='Y' && pin!==''){
                //return;
            }
            if($('#functioniframe').is(':visible')){
                return;
            }
           
           innerHeight = window.innerHeight;
           innerWidth = window.innerWidth;
           //$('.mainview').height("100%");
           //innerHeight = $('body').height();
           
            //$(".mainview").width("100%");
            //innerWidth = $(".mainview").width();
           
           
           if(MobileType ==='I' && screen.height !== screen.width ){
               if(window.orientation === 0 || window.orientation === 180) {
                    innerHeight = screen.height;
                    innerWidth = screen.width;
               } else {
                    innerHeight = screen.width;
                    innerWidth = screen.height;
               }
           } 
           if(MobileType ==='I' && screen.height !== screen.width ){
                innerHeight = screen.height;
                innerWidth = screen.width;
           } 
           if(MobileType ==='A'){
               innerHeight = window.outerHeight;
               innerWidth = window.outerWidth;
           }

            var viewportScale = MobileZoomLevel;
           //$('.sizingheight').html(innerWidth+" x "+innerHeight+" "+viewportScale );
            mobileplus = 0;
            //Store Current Sizing
            $("#viewport").attr("content","user-scalable=no, initial-scale="+viewportScale+", width=device-width");        
            if( (MobileDivide && MobileType === 'A')  ){
            
                innerHeight = innerHeight /  window.devicePixelRatio;
                innerWidth = innerWidth /  window.devicePixelRatio;
            }
                
            stdEntryHeight = 25;
            if( innerWidth >= 2500 ){
                $('.sizingplan').val('2500');
                stdEntryHeight = 40;
            } else
            if( innerWidth >= 1900 ){
                $('.sizingplan').val('1900');
                stdEntryHeight = 30;
            } else
            if( innerWidth >= 1600 ){
                $('.sizingplan').val('1600');
            } else
            if( innerWidth >= 1400 ){
                $('.sizingplan').val('1400');
            } else
            if( innerWidth >= 1200 ){
                $('.sizingplan').val('1200');
            } else
            if( innerWidth >= 1000 ){
                $('.sizingplan').val('1000');
            } else
            if( innerWidth >= 750 ){
                $('.sizingplan').val('750');
            } else
            if( innerWidth >= 600 ){
                $('.sizingplan').val('600');
            } else
            if( innerWidth >= 512 ){
            
                $('.sizingplan').val('512');
            } else
            if( innerWidth >= 414 ){
            
                $('.sizingplan').val('414');
            } else
            if( innerWidth >= 375 ){
            
                $('.sizingplan').val('375');
            }
            if( innerWidth < 375 ){
            
                $('.sizingplan').val('320');
            }
            mobileplus = -10;
            statusBarHeight = 0;
            
            if( MobileType === 'I' ){
                
                statusBarHeight = 0;
                mobileplus = 40;
                //if(mobileversion !== '000' && mobileversion!== ''){
                    //Status Bar in Version 200 Mobile IOS
                    if(innerHeight >= 1024  ){
                        statusBarHeight = 18;
                    } else
                    if(innerHeight >= 812  ){
                        statusBarHeight = 38;
                    } else {
                        statusBarHeight = 18;
                        
                    }
                //}
            }
            if(MobileCapable===false){
                mobileplus = 20;
            }
            if( MobileType === 'A'){
            
                //mobileplus = 25 - 15;
                //mobileplus = -10;
                 //mobileplus = mobileplus + 25;
            }
        
            if(innerWidth >= 1200 ){
                $('#banner').hide();
                bannerHeight = 0;
            } else {
                $('#banner').show();
                bannerHeight = $('#banner').height();
            }
            
            //Orig
            heightOffset = 10;
            if(MobileCapable === false){
                maxheight = innerHeight - bannerHeight-heightOffset-mobileplus-statusBarHeight;
            } else {
                maxheight = innerHeight - bannerHeight-heightOffset-mobileplus-statusBarHeight;
            }
            
            $("#functioniframe").css("min-height", maxheight);
            $("#functioniframe").height(maxheight*5);
            $("#functioniframe").css("width","100%");
            
            
            $("#showmessage1").css("min-height", maxheight);
            $("#showmessage1").height(maxheight*20);
            $("#showmessage1").css("width","100%");
           
            $(document).height(innerHeight);
            $(document).width(innerWidth);
            //$(".consolebody").height(maxheight);
            $(".mainview").height(maxheight);
            $(".tileview").height(maxheight);
            $(".settingsview").height(maxheight);
            $(".roomsview").height(maxheight);
            $(".notificationsview").height(maxheight);
            $('#socialwindow').height(maxheight);
            $('#popupwindow').height(maxheight);
            $('#shareitwindow').height(maxheight);
            $('.noteareadiv2').height(parseInt(maxheight*(.12)));
            $('.noteareadiv').height(maxheight- $('.noteareadiv2').height());
            
            //if($('.chatheading').html()===''){
            //    $('.chatheading').hide();
            //} else {
                $('.chatheading').show();
                //$('.chatheading').height(50);
            //}
            //$('.chatheading').height(headingheight);

            if( innerWidth < 750 || mobileDevice === 'P'){
            
                $('.sidebar').height(0);
                $('.sidebar').width(0);
                $('.sidebar').hide();
                $('.notearea').width(0);
                $('.noteareadiv').width(0);
                //$('#banner').show();
            } else
            if( innerWidth >= 2400 ){
            
                $('.sidebar').height(maxheight);
                $('.sidebar').show();
                $('.sidebar').width(300);
                $('.notearea').width(1400);
                $('.noteareadiv').width(1400);
                //$('#banner').hide();
            } else
            if( innerWidth >= 2200 ){
            
                $('.sidebar').height(maxheight);
                $('.sidebar').show();
                $('.sidebar').width(300);
                $('.notearea').width(1100);
                $('.noteareadiv').width(1100);
                //$('#banner').hide();
            } else
            if( innerWidth >= 1900 ){
            
                $('.sidebar').height(maxheight);
                $('.sidebar').show();
                $('.sidebar').width(300);
                $('.notearea').width(850);
                $('.noteareadiv').width(850);
                //$('#banner').hide();
            } else
            if( innerWidth >= 1600 ){
            
                $('.sidebar').height(maxheight);
                $('.sidebar').show();
                $('.sidebar').width(250);
                $('.notearea').width(750);
                $('.noteareadiv').width(750);
                //$('#banner').hide();
            } else 
            if( innerWidth >= 1400 ){
            
                $('.sidebar').height(maxheight);
                $('.sidebar').show();
                $('.sidebar').width(220);
                $('.notearea').width(700);
                $('.noteareadiv').width(700);
                //$('#banner').hide();
            } else
            if( innerWidth >= 1200 ){
            
                $('.sidebar').height(maxheight);
                $('.sidebar').show();
                $('.sidebar').width(250);
                $('.notearea').width(500);
                $('.noteareadiv').width(500);
                //$('#banner').hide();
            } else 
            if( innerWidth >= 1000 ){
            
                $('.sidebar').height(0);
                $('.sidebar').hide();
                $('.sidebar').width(0);
                $('.notearea').width(500);
                $('.noteareadiv').width(500);
                //$('#banner').hide();
            } else {
                $('.sidebar').height(0);
                $('.sidebar').width(0);
                $('.sidebar').hide();
                $('.notearea').width(0);
                $('.noteareadiv').width(0);
                //$('#banner').hide();
            }
            
            
            $('.noteareadiv2').width(0);
            $('.noteareadiv2').show();
            if($('.maincontentarea').not(':visible')){
                $('.notearea').width(innerWidth);
                $('.noteareadiv2').hide();
            }
            if(visibleChatPanel){
                $('.noteareadiv').width($('.notearea').width());
                $('.noteareadiv2').width($('.notearea').width());
                $('.videoframe').width($('.notearea').width());
                $('.noteareadiv2').show();
            }
            
            CheckChatPanel(visibleChatPanel);
            ResizeChatWindow(maxheight);
            var scrollarea=0;
            if(innerWidth >=1200 && !visibleChatPanel){
                scrollarea=20;
            }

            $(".notificationsview").width(innerWidth);
            $('#socialwindow').width(innerWidth-$('.sidebar').width()-scrollarea);
            $('#popupwindow').width(innerWidth-$('.sidebar').width());
            $('#shareitwindow').width(innerWidth-$('.sidebar').width());
            $(".roomsview").width(innerWidth-$('.sidebar').width());
            //Make Chat window scroll bar visible on Desktops
            $('#chatwindow').width(innerWidth-$('.sidebar').width()-$('.notearea').width()-scrollarea);
                
            //$('#chatwindow').width(innerWidth - $('.sidebar').width());
            localStorage.sizing = $('.sizingplan').val();
            //var innerWidth = innerWidth;
            if( xhrload && xhrload.readyState !== 4){
                xhrload.abort();
            }
            //Sleep Check - If slept then Restart
            var TimeDiff = ( new Date() - lastCheckIn ) /1000;
            if( hostedmode === false && lastCheckIn > 0 && TimeoutSeconds > 0 && TimeDiff > TimeoutSeconds ){
                lastCheckIn = new Date();
                if(pin!==''){
                    TimedOutHandler();
                    return;
                }
            } 
            //Restart if inactive for 4 hours in case the Session Info expires
            if( lastCheckIn > 0 && TimeoutSeconds > 0 && TimeDiff > 60*60*4 ){
                window.location = rootserver1+"l.php?s="+source+"&e="+enterprise+"&v="+mobileversion+"&apn="+apn+"&gcm="+gcm;
            } 
            
            
            if( activeflag!=='Y' && (MobileType ==='A' ||  MobileType==='I' || pinlock==='Y') ){
                
                if( TimeDiff < 60*5 ){
                    //No change so reduce traffic - skip
                    if( lastinnerwidth + lastinnerheight > 0 && 
                        lastinnerwidth === innerWidth && 
                        lastinnerheight === innerHeight ){
                        //return;
                    }
                }
                lastinnerwidth = innerWidth;
                lastinnerheight = innerHeight;
                
            }
            
            if(parseInt(ChannelId)>0){
                //Execute Live Channels every Second
                CheckRefreshLoop( activeflag, innerWidth, innerHeight);
            } else {
                //Execute Chat and Sidebar every 3 Seconds
                if(SecondsCount === 0 || activeflag ==='Y'){
                    CheckRefreshLoop( activeflag, innerWidth, innerHeight);
                }
            }
                
            SecondsCount++;
            if(SecondsCount > 3){
                SecondsCount = 0;
            }
            
            
        }
        function CheckRefreshLoop( activeflag, innerWidth, innerHeight)
        {
            if( !activeflag){
                return;
            }
            
            if(internetLost === 10){
                internetLost++;
                //alert('Internet drop detected');
                xhrload = null;
                //$('.consolebody').html(ConnectError);
                //return;
            }
            if(xhrload!==null){
                return;
            }
            lastCheckIn2 = new Date();
            xhrload =  $.ajax({
                url: rootserver+'check.php',
                context: document.body,
                timeout: 2000,
                type: 'POST',
                data: 
                 { 'sizing': $('.sizingplan').val(), 
                   'mobile' : MobileType,
                   'device' : mobileDevice,
                   'mobilecapable' : MobileCapable,
                   'innerwidth' : innerWidth,
                   'innerheight' : innerHeight,
                   'pixelratio' : window.devicePixelRatio,
                   'active' : activeflag,
                   'chatid' : ChatId,
                   'timezoneoffset' : $('#timezone').val()

                 }
                 
            }).done(function(data){
                internetLost = 0;
                xhrload = null;
                sized = true;
                //lastCheckIn = new Date();
                //$('.admintrace').html(lastCheckIn);
                $('.admintrace2').html(lastCheckIn2+' Done');
                if(data!==''){
                }
                if( data==='timeout' ){
                    if(tester==='Y'){
                        alert('Session Timeout');
                    }
                    TimedOutHandler();
                }
                if( data==='chat' ){
                    if( parseInt(ChatId) > 0){
                        if(ActiveChat(false, '' )){
                        }
                        //SideBarList(true);
                        return;
                    }
                    
                }
                if( data==='sidebar' ){
                    /*
                    if( parseInt(ChatId) > 0 && chatScrollSuspend === false){
                        if(ActiveChat(false, '' )){
                        }
                        SideBarList(true);
                        return;
                    }
                    */
                    SideBarList(true);
                }
            }).fail(function(data){
                xhrload = null;
                internetLost++;
                $('.admintrace2').html(lastCheckIn2+' Fail');
            });
            
        }
        function CheckChatPanel(visibleChatPanel)
        {
            if(!visibleChatPanel){
                $('.notearea').width(0);
                $('.noteareadiv').width(0);
                $('.noteareadiv2').width(0);
            }
            
        }
        function ResizeChatWindow(maxheight)
        {
           
           
           var headingheight = 0;
           var height = maxheight;
           if( height === null){
               height = $('.chatarea').height();
           }
           if( $('.chatheading').is(':visible')){
                headingheight = $('.chatheading').height();
                if(headingheight === 0 ){
                }
            }
            
           //Compute Video Height
           var videoheight = 350;
           var chatwidth = $('.chatarea').width();
           
           if(maxheight <= 1024){
               videoheight = chatwidth*(9/16);
           }
           if(maxheight <= 736){
               videoheight = chatwidth*(9/16);
           }
           if(maxheight <= 568){
               videoheight = chatwidth*(9/16);
               //videoheight = 200;
           }
            
            
            
            
            var entryheight = $('.chatentry').height();
            
            videoheight = 0;
            if($('.mobilenoteareadiv').html()!==''){
                var mobileheight = $('.mobilenoteareadiv').height();
                videoheight = mobileheight;
                
                if(MobileType ==='I' && screen.height !== screen.width ){
                    if(window.orientation === 90 || window.orientation === 270 ) {
                        videoheight = maxheight;
                         $('#banner').hide();
                         //$('.chatentry').hide();
                         entryheight = 0;
                    }
                }
                
                
                //if(videoheight < mobileheight){
                    
                    /*Remove for now - causes constant resizing - flashing */
                    //$('.mobilenoteareadiv').height(videoheight);
                    //$('.videoframe').height(videoheight);
                //} 
               $('.chatarea').width("100%");
               
            } 
            if(MobileCapable){
                $('.chatarea').height(height);
                $('.chatwindow').height(height-videoheight - headingheight-entryheight);
            } else {
                $('.chatarea').height(height);
                $('.chatwindow').height(height-videoheight - headingheight-entryheight);
                
            }
            
        }
        

        
        function TimerJob()
        {
            if(!SlowDowner()){
                return;
            }
            Sizing('Y');
            if( MobileType === 'I'){
            
                //$('.camera').hide();
            }
            try {
                if( localStorage.mobilenotified === 'Y'){
                
                    if(!$('.notificationspopup').is(':visible')){
                        //localStorage.mobilenotified = '';
                        $('#trigger_notificationpopup').click();
                    }
              
                }
            } catch (err) {}
        }
        
        
        function PanelShow(panel)
        {
            /* Google Analytics Pageview */
            //ga('send', 'pageview');
            
            
            if( xhr && xhr.readyState !== 4){
                xhr.abort();
            }
            Sizing('Y');
            $('.timestamp').val(timeStamp());
            
            //maxheight = $('body').height()-150;
            $('.suspendchatrefresh').prop("visible","false");
            $('.suspendchatrefresh').attr("visibility","none");

            $('.commandzone').hide();
                        
            
            $('.sidebararea').show();
           
            visibleChatPanel = false;
            videoActive = false;
            
            $('.actionarea').hide();
            $('#messagescontent').hide();
            $('.messagescontainer').hide();
            $('.hidemessagearea').hide();
            $('.hidemessage').hide();
            $('.commandarea').hide();
            $('#showmessage1').hide();
            $('#functioniframe').hide();
            $('#streamiframe').hide();
            $('.chatarea').hide();
            $('.chatactionarea').hide();
            $('#socialwindow').hide();
            $('.roomwindow').hide();
            $('#roominnerwindow').hide();
            $('#popupwindow').hide();
            $('#prestart').hide();
            $('#firsttime').hide();
            $('#shareitwindow').hide();
            $('.settingsview').hide();
            $('.roomsview').hide();
            $('.notificationsview').hide();
            $('.notificationspopup').hide();
            $('#chatbottom').hide();
            
            //Video
            $('.notearea').hide();
            $('.noteareadiv').html('');
            $('.mobilenoteareadiv').hide();
            $('.mobilenoteareadiv').html('');
            $('.maincontentarea').show();
            

            if(panel!==9 && panel !==3 && panel!==10 && panel!==25){
                //$('#chatwindow').html("");
            }
            
            $('#imapmovemenu').hide();
            
            var version = appname;
            if(enterprise ==='Y'){
                version = appname;
            }
            
            //Restore Last Panel
            if(panel === -1 ){
                panel = LastPanel;
                $('#functioniframe').prop('src',rootserver+'blank.php');
            }
            
            if( panel === 1 && ImapCount === 0 ){
            
                panel = 0;
                $('.tileview').show();
                $('.settingsview').hide();
                $('.mainview').hide();
            }
            
            $(document).prop('title', version);                
            if(panel===0 ){ //Mail
            
                $('.actionarea').html("");
                $('.commandzone').hide();
                $('.commandarea').hide();
                $('#messagescontent').hide();
                $('.messagescontainer').hide();
            }
            
            
            if(panel===1 ){ //Mail
            
                $('.actionarea').html("");
                $('.commandzone').show();
                $('.commandarea').show();
                $('#messagescontent').show();
                $('.messagescontainer').fadeIn("800");
                $(document).prop('title', version);                
            }
            if(panel===2 ){ //IFrame
            
                $('.commandzone').show();
                $('.hidemessagearea').show();
                $('.hidemessage').show();
                $('.actionarea').show();
                $('#showmessage1').show();
            }
            if(panel===3 ){ //Chat
            
                $('.suspendchatrefresh').prop("visible","true");
                $('.suspendchatrefresh').attr("visibility","visible");
                $('.commandzone').hide();
                $('.hidemessagearea').hide();
                //$('.hidemessage').show();
                $('.chatactionarea').hide();
                $('.chatarea').show();
                $(document).prop('title', version+" Chat");                
                $('.notearea').show();
            }
            if(panel===4 ){ //Rooms
            
                $(document).prop('title', version+" Blogs");                
                if(hostedmode==true){
                    $(document).prop('title', hostedroomname);                
                }
                $('#roomwindow').show();
                $('#roominnerwindow').show();
            }
            if(panel===5 ){ //Social
            
                $('.mainview').fadeIn("800");
                $('#prestart').fadeIn("800");
            }
            if(panel===6 ){ //Social
            
                $('#firsttime').show();
            }
            if(panel===7 ){ //IFrame
            
                $('.commandzone').show();
                $('.hidemessagearea').show();
                action = "&nbsp;<div class='mainfont backtoshares tapped'>&nbsp;"+backButton+
                        "&nbsp;Manage Shares&nbsp;&nbsp;"+
                        "</div>"; 
                $('.actionarea').html( action );
                $('.actionarea').show();
                $('#functioniframe').show();
            }
            if(panel===8 ){ //Shares
            
                $('#socialwindow').fadeIn("800");
            }
            if(panel===9 ) {
            
                $('#popupwindow').fadeIn("800");
                $('#popupwindow').scrollTop(0);
            }
            if(panel===10 ){ //Upload Photo
            
                $('.commandzone').show();
                $('.hidemessagearea').show();
                action = "&nbsp;<div class='mainfont photolibrary tapped' data-album='"+UploadToday+"' data-deletefilename='' data-save='' >&nbsp;"+backButton+
                        "&nbsp;My Photos&nbsp;&nbsp;"+
                        "</div><br>"; 
                $('.actionarea').html( action );
                $('.actionarea').show();
                $('#functioniframe').show();
            }
            if(panel===11 ){ //Upload Photo - Called from Select
            
                $('.hidemessage').hide();
                $('.commandzone').show();
                $('.hidemessagearea').show();
                action = "&nbsp;<div class='mainfont photoselectback tapped' >&nbsp;"+backButton+
                        "&nbsp;Photo Select&nbsp;&nbsp;"+
                        "</div>"; 
                $('.actionarea').html( action );
                $('.actionarea').show();
                $('#functioniframe').show();
            }
            if(panel===12 ){ //TextPhoto
            
                $('.commandzone').show();
                $('.hidemessagearea').show();
                action = "&nbsp;<div class='mainfont photolibrary tapped' data-album='TextPics' data-deletefilename='' data-save='' >&nbsp;"+backButton+
                        "&nbsp;My Photos&nbsp;&nbsp;"+
                        "</div>"; 
                $('.actionarea').html( action );
                $('.actionarea').show();
                $('#functioniframe').fadeIn("800");
            }
            if(panel===13 ){ //Photos
            
                $('#socialwindow').show();
                $(document).prop('title', version+" Photos");                
            }
            if(panel===14 ){ //Catchall Social
            
                $('#socialwindow').show();
            }
            
            if(panel===15 ){ //Texting
            
                $('#socialwindow').show();
                $(document).prop('title', version+"");                
            }
            if(panel===16 ){ //Shareit
            
                $('#shareitwindow').show();
            }
            if(panel===18 ){ //Secure Messages
            
                $('.actionarea').html("");
                $('.commandzone').show();
                $('.commandarea').show();
                $('#messagescontent').show();
                $('.messagescontainer').show();
                $(document).prop('title', version+" Secure");                
            }
            if(panel===20 ){ //IFrame
            
                $('.commandzone').hide();
                $('.hidemessagearea').hide();
                $('.hidemessage').hide();
                $('.actionarea').hide();
                
                $('#functioniframe').show();
                $('.mainview').fadeIn("800");
            }
            if(panel===21 ){ //IFrame New Message/Mail
            
                $('.commandzone').show();
                $('.hidemessagearea').show();
                $('.hidemessage').show();
                $('.actionarea').show();
                $('#functioniframe').fadeIn("800");
            }
            if(panel===22 ){ //IFrame New Message/Mail
            
                $('#functioniframe').fadeIn("800");
            }
            if(panel===23 ){ //Upload File
            
                $('.commandzone').show();
                $('.hidemessagearea').show();
                action = "&nbsp;<div class='mainfont doclib tapped' data-deletefilename='' data-mode='R' >&nbsp;"+backButton+
                        "&nbsp;Back to My Files&nbsp;&nbsp;"+
                        "</div>"; 
                $('.actionarea').html( action );
                $('.actionarea').show();
                $('#functioniframe').show();
            }
            if(panel===24 ){ //Upload File from Select
            
                $('.commandzone').show();
                $('.hidemessagearea').show();
                action = "&nbsp;<div class='mainfont fileselect tapped' data-deletefilename='' data-mode='R' >&nbsp;"+backButton+
                        "&nbsp;Select Files&nbsp;&nbsp;"+
                        "</div><br><br>"; 
                $('.actionarea').html( action );
                $('.actionarea').show();
                $('#functioniframe').show();
            }
            if(panel===25 ){ //Upload File from Select
            
                $('.commandzone').show();
                $('.hidemessagearea').show();
                action = "&nbsp;<div class='mainfont setchatsession tapped' data-chatid='"+ChatId+"' >&nbsp;"+backButton+
                        "&nbsp;&nbsp;&nbsp;"+
                        "</div><br><br>"; 
                $('.actionarea').html( action );
                $('.actionarea').show();
                $('#functioniframe').fadeIn("800");
            }
            if(panel===26 ){ //Notifications View
            
                $('.notificationsview').show();
                $('.mainview').hide();
            }
            if(panel===27 ){ //Upload Photo
            
                $('.commandzone').show();
                $('.hidemessagearea').show();
                action = "&nbsp;<div class='mainfont photoselect tapped' data-album='"+UploadToday+"' data-deletefilename='' data-save='' >"+backButton+
                        "&nbsp;Photo Select&nbsp;&nbsp;"+
                        "</div><br><br>"; 
                $('.actionarea').html( action );
                $('.actionarea').show();
                $('#functioniframe').fadeIn("800");
            }
            if(panel===28 ){ //Upload Photo
            
                $('.commandzone').show();
                $('.hidemessagearea').show();
                action = "&nbsp;<div class='mainfont  mainbutton leaveslideshow tapped'  >"+backButton+
                        "&nbsp;&nbsp;&nbsp;"+
                        "</div>"; 
                $('.actionarea').html( action );
                $('.actionarea').show();
                $('#functioniframe').fadeIn("800");
            }
            if(panel===29 ){ //back to Tile MenuUpload Photo
            
                $('.commandzone').show();
                $('.hidemessagearea').show();
                action = "&nbsp;<div class='mainfont  tilebutton tapped'  >"
                        "<span class='pagetitle2' style='color:firebrick'><b>Action Required&nbsp;</b></span> \n\ "+
                        backButton+
                        "&nbsp;Exit / Not Finalized;&nbsp;"+
                        "</div>"; 
                $('.actionarea').html( action );
                $('.actionarea').show();
                $('#functioniframe').show();
            }
            if(panel===30 ){ //back to Tile MenuUpload Photo
            
                $('.commandzone').show();
                $('.hidemessagearea').show();
                action = "<div class='mainfont restarthome tapped'  >"+backButton+
                        "&nbsp;&nbsp;&nbsp;"+
                        "</div>"; 
                $('.actionarea').html( action );
                $('.actionarea').show();
                $('#functioniframe').fadeIn("800");
                $('.sidebararea').hide();
           }
            if(panel===31 ){ //Catchall Social //Same as 14 except for suspendchatrefresh
            
                $('.suspendchatrefresh').prop("visible","true");
                $('.suspendchatrefresh').attr("visibility","visible");
                $('#socialwindow').fadeIn("800");
            }
            if(panel===32 ){ //iframe
            
                $('.commandzone').show();
                $('.hidemessagearea').show();
                action = "&nbsp;<div class='mainfont  mainbutton  tapped leaveslideshow'  >"+backButton+
                        "&nbsp;&nbsp;&nbsp;"+
                        "</div>"; 
                $('.actionarea').html( action );
                $('.actionarea').show();
                $('#functioniframe').fadeIn("800");
            }
            if(panel===33 ){ //iframe
                $('.settingsview').fadeIn("800");
            
            }
            if(panel===34 ){ //Social
            
                $('.sidebararea').hide();
                $('.mainview').fadeIn("800");
                $('#prestart').fadeIn("800");
            }
            if(panel===35 ){ //Stream Iframe
            
                $('.commandzone').show();
                $('.hidemessagearea').show();
                action = "&nbsp;<div class='mainfont setchatsession tapped' data-chatid='"+ChatId+"' >&nbsp;"+backButton+
                        "&nbsp;&nbsp;&nbsp;"+
                        "</div><br><br>"; 
                $('.actionarea').html( action );
                $('.actionarea').show();
                $('#streamiframe').fadeIn("800");
            }
            if(panel===36 ){ //Upload Photo - Called from Select
            
                $('.hidemessage').hide();
                $('.commandzone').show();
                $('.hidemessagearea').show();
                action = "&nbsp;<div class='mainfont restart tapped' >&nbsp;"+backButton+
                        "&nbsp;&nbsp;&nbsp;"+
                        "</div>"; 
                $('.actionarea').html( action );
                $('.actionarea').show();
                $('#functioniframe').show();
            }
            if(panel===37 ){ //Upload File to CaseFiles
            
                $('.commandzone').show();
                $('.hidemessagearea').show();
                action = "&nbsp;<div class='mainfont casefiles tapped' data-caseid='"+CaseId+"'  >&nbsp;"+backButton+
                        "&nbsp;&nbsp;&nbsp;"+
                        "</div>"; 
                $('.actionarea').html( action );
                $('.actionarea').show();
                $('#functioniframe').show();
            }
            if(panel===38 ){ //back to Tile 
            
                $('.commandzone').show();
                $('.hidemessagearea').show();
                action = "&nbsp;<div class='mainfont  tilebutton tapped'  >"+
                        backButton+
                        "&nbsp;Back&nbsp;"+
                        "</div>"; 
                $('.actionarea').html( action );
                $('.actionarea').show();
                $('#functioniframe').show();
            }
            
            LastPanel = CurrentPanel;
            CurrentPanel = panel;
            //$('.mainview').scrollTop(0);
            
        }

        function timeStamp()
        {
            return " "+Date.now();
        }

        function AlertDisplay( Alert )
        {
            $('.alertcolumn').html(Alert);
            if( Alert.length > 0){
                $('.alertrow').show();
            } else {
                $('.alertrow').hide();
            }
        }

        function getRandomArbitrary(min, max) {
        // Returns a random number between min (inclusive) and max (exclusive)
          return Math.random() * (max - min) + min;
        }
        function RefreshAtStart()
        {
            if( ImapItems === 0 ){
                return;
            }

            xhr = $.ajax({
                url: rootserver+'messagesimap_init.php?'+timeStamp(),
                context: document.body,
                type: 'POST',
                data: 
                 { 'providerid': $('#pid').val(), 
                   'timezoneoffset' : TimeZoneOffset,
                   'timestamp' : timeStamp()
                 }

            }).done(function(data){
                xhr = null;
            }).fail(function(data){
                xhr = null;
            });
        }
   /*****************************************************************************
    *
    *    * REFRESH MESSAGE LIST
    * @param {type} ImapFolder
    * @param {type} ImapFlagRun
    * @param {type} nForeground
    * @param {type} nPaging
    * @returns {unresolved}
    * 
    * 
    *****************************************************************************/
        
        
        function RefreshMessageList( ImapFolder, ImapFlagRun, nForeground, nPaging )
        {
            return;
        }
        function ChangeImap()
        {   
            return;

        }
        function GetFolders()
        {
            return;
        }
        function PleaseWait(display)
        {
            if(display){
            
                $("#loading-div-background").css({ opacity: 0.3 });
                $('#loading-div-background').show();
            } else {
            
                $('#loading-div-background').hide();
            }
        }
        function SendChat(passkey64, msgid, streaming)
        {
            var msg = $('#chatmessage').val().trim();
            var img = $('#chatmessage3').val();
            
            if( msg === '' && img === '' ){
                $('.chatcomment').html('');
                $(".chatcomment").show();
                $(".chatsendarea2").hide();
                $("#chatbottom").show();
                $('.chatextra').show();
                $('.chatextrahide').hide();
                return;
            }
            
            
            if(passkey64===''){
                chatinputpasskey = '';
            }
            
            $('.chatcomment').html('');
            $('.chatcomment').show(1000);
            $('.inputfocuscontent').show();
            $('.chatcommenthide').hide();
            $('.chatextra').show();
            $('.chatsendarea').hide();
            $('.chatsendarea2').hide();
            $('.chatsendareamobile').hide();
            $('#chatmessage').blur();
            $('.chatextraarea').hide();
            $('.chatextrahide').hide();
            
            
            if(xhrapp){
                //AbortAjax();
                //xhrapp = null;
            }
            
            
            $.ajax({
                url: rootserver+'chatsend.php',
                context: document.body,
                timeout: 10000,
                type: 'POST',
                data: 
                 { 
                    'providerid': $('#pid').val(),
                    'message' : msg,
                    'img' : img,
                    'chatid' : ChatId,
                    'msgid' : msgid,
                    'passkey64': passkey64,
                    'streaming' : streaming
                 }
                 
             }).done(function(html, status){
                 
                ActiveChat(true, chatinputpasskey );
                $('.chatcomment').html('');
                $('#chatbottom').show();
                $('#chatmessage').val("");
                $('#chatmessage3').val("");
                if( html==='Fail'){
                
                    ChatId = "";
                    $('#trigger_selectchat').trigger("click");
                    alertify.alert("Chat Session was ended. Please start a new session.");
                    return;
                }
                if( html==='Fail2'){
                
                    ChatId = "";
                    $('#trigger_selectchat').trigger("click");
                    alertify.alert("Invalid Session");
                    return;
                }
                if( html==='Fail3'){
                
                    $('#chatmessage').val(msg);
                    $('#chatmessage3').val(img);
                    ChatId = "";
                    $('#trigger_selectchat').trigger("click");
                    alertify.alert("Passkey Credential Failure.");
                    return;
                }            
                if(html!=='success'){
                    $('#chatmessage').val(msg);
                    $('#chatmessage3').val(img);
                    alertify.alert(' Chat connection failed. Check your network connection and retry.');
                    //$('.mainview').scrollTop(0);
                    return;
                }
                
                $('#chatmessage').val("");
                $('#chatmessage3').val("");
                if(html==='success'){
                    
                    $('.chatcomment').html('');
                    
                    $('.mainview').scrollTop(0);
                    if( MobileCapable){
                        $('.chatsendareamobile').hide();
                    }
                    if(streaming){
                        GiveStars();
                    }
                    
                } else {
                    
                    alertify.alert(status);
                    
                }
                 
             }).fail(function(){
                    alertify.alert(' Chat send failed. Check your network connection and retry.');
             });
            
            
        }
        function SideBarList( startup )
        {
            
            if( xhralerts ){
                xhralerts.abort();
            }
            

            xhralerts = $.ajax({
                url: rootserver+'sidebar-v2.php',
                context: document.body,
                timeout: 10000,
                type: 'POST',
                data: 
                 { 'providerid': $('#pid').val(),
                   'startup'   : startup,
                   'mobile' : MobileType,
                   'device' : mobileDevice,
                   'innerwidth' : innerWidth,
                   'innerheight' : innerHeight,
                   'pixelratio' : window.devicePixelRatio,
                   'devicecode' : DeviceCode,
                   'useragent' : navigator.userAgent,
                   'chatid' : ChatId
                 }

            }).done(function( data, status ) {
                SideBarHandling (data, startup);
                //Tip1();
                xhralerts = null;
            }).fail(function( data, status ) {
                xhralerts = null;
            });
        }
            
        function SideBarHandling (data, startup)
        {
                if(data === ''){
                    alert("No Data - Testing");
                    return;
                }
                lastCheckIn = new Date();
                $('.admintrace').html(lastCheckIn+' Side');
                
                
                var msg = jQuery.parseJSON(data);
                
                if( msg.notification==='Y'){
                
                    if( MobileCapable ) {
                        localStorage.mobilenotified = 'Y';
                    }   
                } else {
                    if(!startup){

                        return;
                    }
                }
                if( msg.sidebar!==''){
                    $('.sidebaralerts').html(msg.sidebar);
                    $('.sidemenuarea').html(msg.logo+msg.sidebar);
                } else {
                    //return;
                }
                if( msg.executescript!==''){
                }
                
                $('.alertchat').html(msg.alertchat);
                $('.alertlive').html(msg.alertlive);
                $('.alertroom').html(msg.alertroom);
                
                if( msg.tileview!==''){
                
                    $('.tileview').html(msg.tileview);
                }
                if( msg.roomsview!==''){
                
                    $('.roomsview').html(msg.roomsview);
                }
                if( msg.alertmessage!==''){
                
                    $('.sidebarmessage').show();
                    $('.sidebarmessage').html(msg.alertmessage);
                }
                
                if( msg.alarm === 'Y'){
                
                    /*
                    $("#favicon").attr("href","../img/check-yellow-128.png");                    
                    */
                    //PlaySound(false);
                    
                    //if chatlist area open - refresh that
                    if( $('.chatlistarea').visible(true) ){
                    
                        //$('#trigger_selectchat').trigger("click");
                        return;
                    } else {
                    
                        if( !MobileCapable){
                        
                            if( nowAlerting){
                                return;
                            }
                            nowAlerting = true;
                        }
                    }
                } else
                if( msg.alarm === 'T'){
                    
                    TimeOutTest();
                    
                
                    if( !TimedOut){
                        TimedOutHandler();
                    }
                    return;
                } else {
                
                }
                if( startup ){ //Initialization
                
                    if( msg.alarm==='Y'){
                    
                        //$('.selectchatlist').trigger('click');
                    } else
                    if( msg.alarm!=='Y' && msg.room!==''){
                    
                        Sizing();
                        $('.feed').trigger('click');
                    } else {
                    
                        Sizing();
                    }
                    if( MobileType === 'I' || MobileType==='A'){
                        
                    }
                }
                
                AlertDisplay(msg.status);
            
        }        
        function ActiveChat(scroll, passkey)
        {
            if( MobileType !== 'I' && MobileType !=='A'){
            
                $('.camera').hide();
                $('.cameratext').hide();
            } else {
                $('.camera').show();
                $('.cameratext').show();
                
            }
            
            //Busy
            if( xhrchat && !scroll){
                //alert('busy');
                return;
            }
            if(scroll){
                if(xhrchat){
                    xhrchat.abort();
                    xhrchat = null;
                }
                $('.chatentry').show();
                chatScrollSuspend = false;
            }
            
            
            //Use last passkey in passive refresh
            if(passkey==='' && !scroll){
                passkey = chatinputpasskey;
            } else {
                chatinputpasskey = passkey;
            }
            if(!scroll && $('.chatextraarea').is(":visible")){
                //chatScrollSuspend = true;
                return;
            }
            if(!scroll && chatEditing === true){
                //chatScrollSuspend = true;
                return;
            }
            
            var bottom = scroll;
            if(!scroll){
                bottom = false;
            } else {
            }
            if(scroll){
                $('.chatscrollsuspended').show();
                $('.chatscrollsuspended').html("<img class='icon15' src='../img/loading-blue.gif' />");
                $('.chatscrollactive').hide();
            } else {
                //ResetScrollSensor();
            }
            if(chatScrollSuspend!==false){
                return;
            }
            
            
            if(!chatScrollSuspend){
                bottom = true;
            }
            //Always scroll
            // bottom = true;
            if(bottom === false){
                setTimeout( function(){
                    ScrollChat();
                    ResetScrollSensor();
                    }, 1000 
                );
                return false;
                
            }
            
           
            ResizeChatWindow();

            $('.chatcomment').val('');
            LastFunc = 'C';

            if( parseInt(ChatId) === 0){
                //$('#trigger_selectchat').trigger('click');
                ResetScrollSensor();
                return "ChatID 0";
            }
            /*
             * This is a problem with SendChat so disable
             * 
             *
             */
            AbortAjax();
            if( !scroll && xhrchat ){
                //xhrchat.abort();
                ResetScrollSensor();
                return "Busy";
            }
            
            

            xhrchat = $.ajax({
                url: rootserver+'chatalert.php',
                context: document.body,
                type: 'POST',
                data: 
                { 'providerid': $('#pid').val(),
                   'chatid' : ChatId,
                   'force' : scroll,
                   'iscore' : localStorage.iscore,
                   'passkey' : passkey,
                   'togglechat' : ToggleChatShow,
                   'togglemembers' : ToggleMembersShow,
                   'videoactive' : videoActive,
                   'audiostreamactive' : audiostreamactive
                }

            }).done(function( data, status ) {

                xhrchat = null;

                var msg = jQuery.parseJSON(data);

                if(status!=="success"){
                    $('#chatwindow').html("Network Failure");
                }
                if(msg.error === 'restricted'){
                    $('#streamiframe').prop('src',rootserver+'blank.php');
                    alertify.alert('This broadcast is restricted at this time. Please join another broadcast later.');
                    $('#trigger_selectchat').trigger('click');

                    ResetScrollSensor();
                    return;
                }

                if(msg.error === 'notfound'){
                    ResetScrollSensor();
                    alertify.alert('Chat session not found');
                    return;
                }
                
                
                if( msg.scroll === 'Y' || scroll){
                    //alert( msg.scroll);

                    if( msg.chat!==''){
                        PlaySound(false);

                        $(".mainview").scrollTop(0);


                        $('#chatwindow').html(msg.chat);
                        imageLoading( scroll );
                        
                        /*if chatentry is not refreshed, update values */
                        $('.chatscrollsuspended').data('chatid', ChatId);
                        $('.chatentrybutton').data('passkey64', msg.passkey64);
                        /* ***** */
                        $('.chatheading').html(msg.chatheading);
                        if( typeof $('#chatmessage').val()==='undefined' || 
                             ( $('#chatmessage').val().trim()==='' )
                            ){

                            $('.chatentry').html(msg.chatentry);
                            ResetScrollSensor();

                            if(scroll){
                                $('#chatmessage').height( stdEntryHeight);
                                $('.chatentry').height(stdEntryHeight+30);
                                //ResizeChatWindow();
                                
                                setTimeout( function(){
                                    ResetScrollSensor();
                                    }, 1500 
                                );
                            
                            }
                            if( MobileType !== 'I' && MobileType!=='A'){

                                setTimeout( function(){
                                    $('#chatmessage').focus();
                                    }, 200 
                                );
                            }

                        }
                        
                        $('.chatheading').animate({opacity:1.0}, 0);
                        $('.chatheading').show();
                        if( (!visibleChatPanel && $('.mobilenoteareadiv').html()==='')
                            && msg.video==='V'){
                            if(MobileCapable===true){
                                $('.noteareadiv').html('');
                                if($('.mobilenoteareadiv').not(':visible') ){
                                    $('.mobilenoteareadiv').html('Loading');                                
                                    $('#trigger_audiopanel_mobile').data('chatid',ChatId);
                                    $('#trigger_audiopanel_mobile').click();
                                    ResizeChatWindow();
                                }
                            } else {
                                visibleChatPanel = true;
                                $('.noteareadiv').html(msg.panel);
                                $('.noteareadiv').show();
                                $('.mobilenoteareadiv').html('');                                
                                $('#trigger_audiopanel_desktop').data('chatid',ChatId);
                                $('#trigger_audiopanel_desktop').click();
                                
                            }
                            //ResizeChatWindow();
                        }
                        if( (!visibleChatPanel && $('.mobilenoteareadiv').html()==='')
                            && msg.video==='B'){
                            $('.audiopanelbroadcaster').click();
                            //ResizeChatWindow();
                        }
                        if( (!visibleChatPanel && $('.mobilenoteareadiv').html()==='')
                            && msg.video==='A' ){
                            visibleChatPanel = true;
                            $('.noteareadiv').html(msg.panel);
                            $('.noteareadiv').show();
                        }


                        /*
                        setTimeout( function(){
                            ScrollChat();
                            }, 1000 
                        );
                    */



                    } else {
                        ResetScrollSensor();

                    }
                    xhrchat = null;
                    return;
                    
                } else {
                    
                    //No Scroll Needed
                    ResetScrollSensor();

                }
                xhrchat = null;

                return;

            }).fail(function( data, status ) {

                xhrchat = null;

                ResetScrollSensor();

                if( status==='timeout'){
                    $('#chatwindow').html("Network Down");
                }
                if( status==='error'){
                }
                /*
                ScrollChat();
            */

                return;

            });
            return true;
            
        }
        function ResetScrollSensor()
        {
            $('.chatscrollsuspended').hide();
            $('.chatscrollsuspended').html("<img class='icon15' src='../img/Lock-White_120px.png' /> ");
            $('.chatscrollactive').show();
        }
        function ScrollChat()
        {
            //alert('scroll');
            $('.mainview').scrollTop(0);
            var scrolltop = $('#chatwindow')[0].scrollHeight+999999999;                
            $('#chatwindow').scrollTop(scrolltop);
            LastChatScrollBottom = $('#chatwindow').scrollTop();
            ResetScrollSensor();
            chatScrollSuspend = false;
        }
        function imageLoading( scroll )
        {
            if( $('.feedphotochat').length  > 0 ){
              
            }
            
            //return;
            var scrolltop;
            var bottom = scroll;
            if(!scroll){
                bottom = false;
            } else {
                bottom = true;
            }
            if(!chatScrollSuspend){
                bottom = true   ;
            }
            
            
            
            if(   typeof $('#chatmessage').val()!=='undefined' &&
                  (
                  $('#chatmessage').val()!=='' ||
                  $('#chatmessage3').val()!=='' 
                  )
               ){
                //bottom = false;
            }
            //Don't Scroll if Showing Members
            if(ToggleMembersShow && bottom){
                return;
            }
            ScrollChat();
            
            $('.feedphotochat').imagesLoaded( function(){
            }).progress( function() {
                    $('.feedphotochat').hide();
                /*
                    setTimeout( function(){
                        ScrollChat();
                    }, 250 );
                */
            }).done( function(){
                /*
                    setTimeout( function(){
                        ScrollChat();
                    }, 500 );
                    */

            }).always( function(){
                
                setTimeout( function(){
                    $('.feedphotochat').show();
                    ScrollChat();
                }, 500 );
                
                setTimeout( function(){
                    ScrollChat();
                }, 1000 );
                
            });       
            
            
                
            if( $('.feedphotochat').length  > 0 ){
                //ScrollChat();
                //setTimeout( function(){
                //    ScrollChat();
                //}, 1500 );
                //setTimeout( function(){
                //    ScrollChat();
                //}, 2000 );
            }
            if( $('.feedphotochat').length  === 0 ){
                ResizeChatWindow();
                
                setTimeout( function(){
                    ScrollChat();
                }, 500 );
                
            }
            
        }
        function SuspendChatNotification( providerid, ChatId )
        {
            $.ajax({
                url: rootserver+'notifysuspend.php',
                context: document.body,
                type: 'POST',
                data: 
                { 'providerid': providerid,
                   'chatid' : ChatId
                }

            });
            
        }
            
            
            
            
        function iComputeScore(success)
        {
            if( success ){
                iScore++;
            } else {
                iScore--;
            }
            if(iScore > 5){
                iScore = 5;
            }
            if(iScore < -5){
                iScore = -5;
            }
            localStorage.iscore = iScore;
        }

        function PlaySound(sound) 
        {
            if(ping ===''){
                return;
            }
            if(ping2 ===''){
                return;
            }
            if(sound){
                ping2.play();
            } else {
                
                //if( PingCount >= MaxPingCount){
                //    return;
                //}
                //ping.play();
                //PingCount++;
            }
        }


        function CheckMessages()
        {
            
            if( ImapItems === 0 ){
                return;
            }
            //This will check all
                
                        
            return;
            
            
            
        }
        
        function PrintIt()
        {
            window.frames["showmessage1"].focus();
            window.frames["showmessage1"].print();
        }
        function linkify( text ) 
        {
            
            replacedText = text;

            return replacedText;
        }
        

        function LoadingShowMessage1( primetask )
        {
            if( primetask){
            
                if( xhrload && xhrload.readyState !== 4){
                    xhrload.abort();
                }
            }
            
            
            var html = LoadingGIF;
            $('#showmessage1').contents().find('html').html(html);
        }

        function ResetTimeout()
        {
            inactivity = 0;
        }
        /* END - Idle Tracking */
       
        function htmlEscape(html)
        {
            html = html.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");           
            return html;
        }
        function htmlUnEscape(html)
        {
            html = html.replace(/&amp;/g, "&").replace(/&lt;/g, "<").replace(/&gt;/g, ">");           
            return html;
        }
        function scrollToAnchor(anchorName) 
        {
            if( typeof anchorName!=='undefined' && 
               anchorName!==''){
           
                var pos = $('#'+anchorName).offset().top;
                setTimeout(function()
                {
                    // animated top scrolling
                    $('.mainview').animate({scrollTop: pos},2000);               
                    //alertify.alert(anchorName);
                },500);

            }
        }   
        function ResetLastFunction()
        {
            AbortAjax();
            $.ajax({
                url: rootserver+'reset.php?'+timeStamp(),
                context: document.body,
                type: 'POST',
                data: 
                 { 'providerid': $('#pid').val(),
                   'timestamp' : timeStamp()
                 }
                 
            });
            
        }
        function ScrollMainTop(){
            $(".mainview").scrollTop(0);
        }

    function SaveToLocalStorage( command, data)
    {
        try {
            if( command === 'mobilecommand'){
            
                    if( localStorage.mobilecommand === data && data !== "" ){
                    
                        alertify.alert("Camera Event Listener Error");
                        return false;
                    }
            }
        } catch (err) {
            //alertify.alert(err);
            alertify.alert(err);
            return false;
        }
        
        try {
            if( command === 'mobilecommand'){
            
                    localStorage.mobilecommand = data;
                    if( localStorage.mobilecommand === data ){
                    
                        if( data === "camera")
                        {
                            alertify.set({ delay: 5000 });
                            alertify.log("Loading Camera"); 
                        }
                    }
            }
        } catch (err) {
            //alertify.alert(err);
            alertify.alert("Please make sure you are not blocking cookies to enable this Camera feature");
            return false;
            
        }
        return true;
        
    }
    function AbortAjax()
    {
        if( xhrload ){ //&& xhrload.readyState !== 4){
            xhrload.abort();
            xhrload = null;
        }
        if( xhralerts ){ //&& xhralerts.readyState !== 4){
            xhralerts.abort();
            xhralerts = null;
        }
        if( xhrapp ){ //&& xhralerts.readyState !== 4){
            xhrapp.abort();
            xhrapp = null;
        }
        if( xhr ){ //&& xhralerts.readyState !== 4){
            xhr.abort();
            xhr = null;
        }
        
    }
    function SlowDowner()
    {
        slowDownerCount++;
        if(slowDownerCount >= slowDownerLimit ){
        
            slowDownerCount = 0;
            return true;
        }
        return false;
    }
    function TimerFieldJob()
    {
        TimerFieldCount += 1;
        $('.timerfield').html(TimerFieldCount);
    }
    function TimeOutTest()
    {
        $('#trigger_pin').click();
    }
    function TimedOutHandler()
    {

        $('sidemenuarea').hide();
        if(TimeoutSeconds === 0 || pin ==='' ){
            
            PanelShow(15);
            $('#socialwindow').load( rootserver+"timeout.php",  {
                'providerid': $('#pid').val()
            }, function(html, status){
            });
            return;
            
        }
        
        if(
                typeof $('#chatmessage').val()!=='undefined' &&
                  (
                  $('#chatmessage').val()!=='' ||
                  $('#chatmessage3').val()!=='' 
                  )
          
                  ||
                  
                typeof $('#statuscomment').val()!=='undefined' &&
                  (
                  $('#statuscomment').val()!=='' 
                  )
                  
                  ||
                  
                  $('.chateditingactive').is(":visible")
                  
               ){
           return;
        }
        
        
        $('#trigger_pin').click();
        //alertify.alert('Timed out');
        //window.location.replace( rootserver1+"l.php");
        TimedOut = true;

    }
    function PreStartup()
    {
        
        $('#max').val('100');
        $("#page").text("1");
        $('#alertrow').hide();
                
        
        //setInterval( function(){ SideBarList(false); }, 30000);
        //setInterval( function(){ ActiveChat(false,''); }, 5000);
        setInterval( function(){ TimerJob(); }, 2000);
        //setInterval( function(){ TimerFieldJob(); }, 1000);
        $('#providername').load( rootserver+'providername.php',  {'providerid': $('#pid').val(), 'loginid' : $('#loginid').val() }); 
        $('#providername2').load( rootserver+'providername.php',  {'providerid': $('#pid').val(), 'loginid' : $('#loginid').val() }); 
        $('.searchcriteria').hide();
        $('#alert').html(OKMessage);

        $('.startchatbutton').show();
        $('.endchatbutton').hide();
        Sizing('Y');
        if( MobileType === 'I' || MobileType==='A'){
        
            $('.camera').show();
            $('.poweruser').hide();
        } else {
        
            $('.camera').hide();
            
        }
        
    }
    function RunAtStartup()
    {
        if(hostedmode === false){
            SideBarList(true);
        }
        
        //PanelShow(4);
            
        if(initmodule!==''){
            
            ChatId = parseInt(initmodule);
            $('#trigger_chat').data("chatid",ChatId);
            setTimeout(function() {
                $('#trigger_chat').trigger('click');    
            }, 500);            
            
        } else {
            JumpToLast();
            
           
            setTimeout(function() {
                SideBarList(true);
            }, 2000);            
            
            setTimeout(function() {
                SideBarList(true);
            }, 10000);            
            
            
        }
        
    }
    function JumpToLast(){
        
        if(hostedmode === true){
                $('#trigger_room').data("roomid", hostedroomid);
                setTimeout(function() {
                    $('#trigger_room').trigger('click');    
                }, 500);            
            return;
        }
        
        if(pinlock === 'Y'){
            //alert('pinlock');
            $('#trigger_pin').click();
            return;
        }
        if(chgpasswordflag === 'Y'){
            
            $('#trigger_settings').trigger("click");
            
            setTimeout(function() {
                $('#trigger_chgpassword').trigger("click");
            }, 1000);            
            if( onetimeflag === 'Y'){
                alertify.alert("You are using a one-time password. You must change your password immediately to retain access. ");
            } 
            onetimeflag = '';
            return;
        }
        //Startup Go Back to Last Function
        
        if( LastFunc === ''){

            $(".tileview").show();
            $(".settingsview").hide();
            $(".mainview").hide();
            $("#loading-div-background").hide();
            
        } else
        if( LastFunc === 'R'){

            //defaultRoomid = LastFuncParm1;
            $('#trigger_room').data("roomid",LastFuncParm1);
            setTimeout(function() {
                $('#trigger_room').trigger('click');    
            }, 500);            
        } else
        if( LastFunc === 'U'){

            //defaultRoomid = LastFuncParm1;
            $('#trigger_room').data("roomid",LastFuncParm1);
            setTimeout(function() {
                $('#trigger_room').trigger('click');    
            }, 500);            
        } else
        if( LastFunc === 'US'){

            //defaultRoomid = LastFuncParm1;
            $('#trigger_userstore').data("roomid",LastFuncParm1);
            setTimeout(function() {
                $('#trigger_userstore').trigger('click');    
            }, 500);            
        } else
        if( LastFunc === 'P'){

            $('#trigger_photo').data("album", LastFuncParm1);
            setTimeout(function() {
                $('#trigger_photo').trigger('click');    
            }, 500);            
        } else
        if( LastFunc === 'F'){

            setTimeout(function() {
                $('#trigger_file').trigger('click');    
            }, 500);            
        } else
        if( LastFunc === 'C'){

            ChatId = LastFuncParm1;
            $('#trigger_chat').data("chatid",ChatId);
            setTimeout(function() {
                $('#trigger_chat').trigger('click');    
            }, 500);            
        } else
        if( LastFunc === 'L'){

            ChatId = LastFuncParm1;
            setTimeout(function() {
                $('#trigger_selectlive').trigger('click');    
            }, 500);            
        } else
        if( LastFunc === 'W'){

            setTimeout(function() {
                $('#trigger_findpeople').trigger('click');    
            }, 500);            
        } else
        if( LastFunc === 'X'){
            var caseid = LastFuncParm1;
            $('#trigger_case').data("caseid",caseid);

            setTimeout(function() {
                $('#trigger_case').trigger('click');    
            }, 500);            
        } else
        if( LastFunc === 'V'){
            var userid = LastFuncParm1;
            $('#trigger_photolibshare').data("userid",userid);

            setTimeout(function() {
                $('#trigger_photolibshare').trigger('click');    
            }, 500);            
        } else
        if( LastFunc === 'E'){

            $('.imapbutton').trigger('click');    
        } else
        if( LastFunc === 'S'){

            $('#trigger_iotview').trigger('click');    
        } else
        if( LastFunc === 'A'){

            setTimeout(function() {
                $('#trigger_uploadavatar').trigger('click');    
            }, 500);            
        } else {

            $.ajax({
                url: rootserver+'lastfunc.php?'+timeStamp(),
                context: document.body,
                type: 'POST',
                data: 
                 { 'providerid': $('#pid').val(), 
                   'mode' : 'S',
                   'parm1' : '',
                   'lastfunc' : '',
                   'timestamp' : timeStamp()
                 }
             });

        }
        SaveToLocalStorage( 'mobilecommand', '');    
    }
    
    function DisplayTip()
    {

        $.ajax({
            url: rootserver+'tip.php',
            context: document.body,
            type: 'POST',
            data: 
             {

             }
         }).done(function( html ){
            if( html!==''){

                setTimeout(function() {
                    $('#trigger_about').trigger('click');    
                }, 500);            

            } else {

                JumpToLast();
            }

         });
        return true;
    }

    
    function TermsOfUseCheck()
    {
        //if(hostedmode === true){
        //    return true;
        //}
        if(termsofuse==='N'){
            //$('#trigger_termsofusedisplay').trigger('click');
            //return false;
        }
        return true;
        
    }
    function AppStoreCheck()
    {
        if(MobileType!=='A' && MobileType!=='I'){
            return false;
        }
        if(MobileCapable && (mobileversion === '000')  ){
            if(appStoreCheck > 0){
                return 2;
            }
            appStoreCheck+=1;
        
            //alert('Reminder\r\n\r\nPlease download the mobile app from the Appstore for a better experience.\r\n\r\nFor example, a browser does not have permissions to access your photos, or get notifications.');
            //$('.appstoredisplay').trigger('click');
            return 1;
        }
        if(MobileCapable && (DeviceCode==='iphone' || DeviceCode ==='android') ){
        }
        
        return false;
        
    }
    function UserAgentMatching()
    {
          
       
        MobileZoomLevel = 1;
        MobileCapable = false;
        MobileDivide = false;
        DeviceCode = '';
        if( navigator.userAgent.match(/Ubuntu Touch/i) && tester==='Y') {
            mobileDevice = "P";
            MobileCapable = false;
            MobileType = "U";
            MobileDivide = false;
            DeviceCode = 'ubuntutouch2';
            MobileZoomLevel = 4;
        }
        else
        if( navigator.userAgent.match(/Ubuntu Touch/i)) {
            mobileDevice = "P";
            MobileCapable = false;
            MobileType = "U";
            MobileDivide = false;
            DeviceCode = 'ubuntutouch1';
        }
        else
        if( navigator.userAgent.match(/Linux; Ubuntu 16.04 like Android 4.4/i)) {
            mobileDevice = "P";
            MobileCapable = false;
            MobileType = "U";
            MobileDivide = false;
            DeviceCode = 'ubuntutouch';
        }
        else
        if( navigator.userAgent.match(/iPhone/i)) {
            mobileDevice = "P";
            MobileCapable = true;
            MobileType = "I";
            MobileDivide = false;
            DeviceCode = 'iphone';
        }
        else
        if( navigator.userAgent.match(/iPad Mini/i)) {
            mobileDevice = "T";
            MobileCapable = true;
            MobileType = "I";
            MobileDivide = false;
            DeviceCode = 'ipad1';
        } else
        if( navigator.userAgent.match(/iPad/i)) {
            mobileDevice = "T";
            MobileCapable = true;
            MobileType = "I";
            MobileDivide = false;
            DeviceCode = 'ipad2';
        } else
        //Amazon Kindle HDX
        if( navigator.userAgent.match(/KFAPWI/i)) {
            mobileDevice = "T";
            MobileCapable = true;
            MobileType = "A";
            MobileDivide = false;
            MobileZoomLevel = 2;
            DeviceCode = 'kindlehdx';
        } else
        //Nexus
        if( navigator.userAgent.match(/Nexus/i) && navigator.userAgent.match(/Version/i) ) {
            mobileDevice = "T";
            DeviceCode = 'nexust'
            if( navigator.userAgent.match(/Mobile/i)){
                mobileDevice = 'P';
                DeviceCode = 'nexusp';
            }
            MobileCapable = true;
            MobileType = "A";
            MobileDivide = false;

        } else
        //Special Case SM-N910P Samsung NOTE
        if( 
                (
                navigator.userAgent.match(/SM-N9/i) 
                )
                &&  
                (
                navigator.userAgent.match(/Android 6/i) 
                )
            ){
            mobileDevice = "T";
            DeviceCode = 'samtnote';
            if( navigator.userAgent.match(/Mobile/i)){
                mobileDevice = 'P';
                DeviceCode = 'sampnote';
            }
            MobileCapable = true;
            MobileType = "A";
            MobileDivide = false;

        } else
        //Old Samsung
        if( 
                (
                navigator.userAgent.match(/Samsung/i) ||
                navigator.userAgent.match(/SCH/i) ||
                navigator.userAgent.match(/SM-/i) 
                )
                &&  
                (
                navigator.userAgent.match(/Android 4.0/i) ||
                navigator.userAgent.match(/Android 4.1/i) ||
                navigator.userAgent.match(/Android 4.2/i) ||
                navigator.userAgent.match(/Android 4.3/i) 
                )
            ){
            mobileDevice = "T";
            DeviceCode = 'samtold';
            if( navigator.userAgent.match(/Mobile/i)){
                mobileDevice = 'P';
                DeviceCode = 'sampold';
            }
            MobileCapable = true;
            MobileType = "A";
            MobileDivide = false;

        } else
        //New Samsung
        if( 
                (
                navigator.userAgent.match(/Samsung/i) ||
                navigator.userAgent.match(/SCH/i) ||
                navigator.userAgent.match(/SM-/i) 
                )
                &&  
                (
                navigator.userAgent.match(/Android 4.4/i) ||
                navigator.userAgent.match(/Android 5/i) ||
                navigator.userAgent.match(/Android 6/i) ||
                navigator.userAgent.match(/Android 7/i) ||
                navigator.userAgent.match(/Android 8/i) ||
                navigator.userAgent.match(/Android 9/i) ||
                navigator.userAgent.match(/Android 10/i) ||
                navigator.userAgent.match(/Android 11/i) 
                )
            ){
            mobileDevice = "T";
            DeviceCode = 'samt2';
            if( navigator.userAgent.match(/Mobile/i)){
                mobileDevice = 'P';
                DeviceCode = 'samp2';
            }
            MobileCapable = true;
            MobileType = "A";
            MobileDivide = false;
            //Browser not app
            //if(navigator.userAgent.match(/SamsungBrowser/i)){
            //    MobileDivide = false;
            //}
        } else
        //New Samsung
        if( 
                (
                navigator.userAgent.match(/Pixel/i)
                )
                &&  
                (
                navigator.userAgent.match(/Android 7/i) ||
                navigator.userAgent.match(/Android 8/i) || 
                navigator.userAgent.match(/Android 9/i) || 
                navigator.userAgent.match(/Android 10/i) ||
                navigator.userAgent.match(/Android 11/i) 
                )
            ){
            mobileDevice = "T";
            DeviceCode = 'pixelt2';
            if( navigator.userAgent.match(/Mobile/i)){
                mobileDevice = 'P';
                DeviceCode = 'pixel2';
            }
            MobileCapable = true;
            MobileType = "A";
            MobileDivide = false;
            //Browser not app
            //if(navigator.userAgent.match(/SamsungBrowser/i)){
            //    MobileDivide = false;
            //}
        } else
        //ASUS ASUS_I00D
        if( 
            (
            navigator.userAgent.match(/Android 10/i)   ||
            navigator.userAgent.match(/Android 11/i)  
            )
            && 
            //ASUS ASUS_I00D
            navigator.userAgent.match(/ASUS_I/i) 
            ) {
            mobileDevice = "P";
            MobileCapable = true;
            MobileType = "A";
            MobileDivide = false;
            DeviceCode = 'asus_I00D';
        } else    
        //ASUS Zenfone
        if( 
            navigator.userAgent.match(/Android/i)  && 
            //ASUS Zenfone 
            navigator.userAgent.match(/ASUS_/i) 
            ) {
            mobileDevice = "P";
            MobileCapable = true;
            MobileType = "A";
            MobileDivide = true;
            DeviceCode = 'asus';
        } else    
        //Motorala Droid 
        if( navigator.userAgent.match(/Android/i)  && 
                (
                navigator.userAgent.match(/Android 5/i) ||
                navigator.userAgent.match(/Android 6/i) ||
                navigator.userAgent.match(/Android 7/i) ||
                navigator.userAgent.match(/Android 8/i) ||
                navigator.userAgent.match(/Android 9/i) ||
                navigator.userAgent.match(/Android 10/i) ||
                navigator.userAgent.match(/Android 11/i)
                )
                &&
                (
                    navigator.userAgent.match(/ XT/i) || 
                    navigator.userAgent.match(/Motorola/i) 
                )
                /*
                        &&
                        navigator.userAgent.match(/Version/i)
                */
            ) {
            //alert('Droid');
            mobileDevice = "P";
            MobileCapable = true;
            MobileType = "A";
            //MobileDivide = false;
            MobileDivide = false;
            DeviceCode = 'droid';
        }
        else
        //Catch All for Other Android
        if( 
                (
                navigator.userAgent.match(/Android 4.0/i) ||
                navigator.userAgent.match(/Android 4.1/i) ||
                navigator.userAgent.match(/Android 4.2/i) ||
                navigator.userAgent.match(/Android 4.3/i) 
                )
            ){
            mobileDevice = "T";
            DeviceCode = 'androidt';
            if( navigator.userAgent.match(/Mobile/i)){
                mobileDevice = 'P';
                DeviceCode = 'androidp';
            }
            MobileCapable = true;
            MobileType = "A";
            MobileDivide = false;
        }
        else
        if(
                navigator.userAgent.match(/Nova2/i)  &&             
                (
                navigator.userAgent.match(/Android 4.4/i) ||
                navigator.userAgent.match(/Android 5/i) ||
                navigator.userAgent.match(/Android 6/i) ||
                navigator.userAgent.match(/Android 7/i) ||
                navigator.userAgent.match(/Android 8/i) ||
                navigator.userAgent.match(/Android 9/i) ||
                navigator.userAgent.match(/Android 10/i) ||
                navigator.userAgent.match(/Android 11/i) 
                )
             
            ){
            mobileDevice = "T";
            DeviceCode = 'androidteink';
            if( navigator.userAgent.match(/Mobile/i)){
                mobileDevice = 'P';
                DeviceCode = 'androidpeink';
            }
            MobileCapable = true;
            MobileType = "A";
            MobileDivide = false;
        }
        else
        if(
                (
                navigator.userAgent.match(/Android 4.4/i) ||
                navigator.userAgent.match(/Android 5/i) ||
                navigator.userAgent.match(/Android 6/i) ||
                navigator.userAgent.match(/Android 7/i) ||
                navigator.userAgent.match(/Android 8/i) ||
                navigator.userAgent.match(/Android 9/i) ||
                navigator.userAgent.match(/Android 10/i) ||
                navigator.userAgent.match(/Android 11/i) 
                )
             
            ){
            mobileDevice = "T";
            DeviceCode = 'androidt2';
            if( navigator.userAgent.match(/Mobile/i)){
                mobileDevice = 'P';
                DeviceCode = 'androidp2';
            }
            MobileCapable = true;
            MobileType = "A";
            MobileDivide = false;
        }

        if( MobileCapable){

            $('.poweruser').hide();
            if(window.navigator.standalone !== true) {
            }    
            $('.bannerflush').addClass('touch').removeClass('nontouch');
            $('.changeavatar').addClass('touch').removeClass('nontouch');
        }
        Browser = '';
        if(DeviceCode==='ubuntutouch') {
            Browser = 'firefox';
            DeviceCode = 'ubuntutouch';
            $('.camera').hide();
        } else
        if(navigator.userAgent.match(/Firefox/) && DeviceCode==='Unk') {
            Browser = 'firefox';
            DeviceCode = 'firefox';
            $('.camera').hide();
        } else
        if(navigator.userAgent.match(/Chrome/) && !navigator.userAgent.match(/Android/i)  && DeviceCode ==='Unk'  ) {

                Browser = 'chrome';
                DeviceCode = 'chrome';
                MobileDivide = false;
                MobileCapable = false;
                
        } else
        if( (navigator.userAgent.match(/Safari/) && !navigator.userAgent.match(/Mobile Safari/) ) && (DeviceCode==='Unk' || DeviceCode==='iphone') ) {
            Browser = 'safari';
            DeviceCode = 'safari';
        } else
        if(navigator.userAgent.match(/Chrome/) && navigator.userAgent.match(/Android/i) && DeviceCode ==='Unk' ) {

                Browser = 'chrome';
                DeviceCode = 'chromemobile';
                MobileDivide = false;
                MobileCapable = true;
                
        } else
        if(navigator.userAgent.match(/Windows NT/)  && DeviceCode ==='Unk' ) {
            Browser = 'windows';
            DeviceCode = 'windows';
        }
        if( MobileCapable === false){
            $('.browseronly').addClass('hidemobile');
        } else {
            $('.browseronly').removeClass('hidemobile');
        }
    }
    
    $.notify.addStyle('std', {
      html: "<div class='gridstdborder'><span data-notify-text/><img class='icon15' src='../img/Arrow-Right_120px.png' /></div>",
      classes: {
        base: {
          "white-space": "nowrap",
          "color": "black",
          "background-color": "white",
          "padding": "10px"
        },
        white: {
          "color": "black",
          "background-color": "white"
        },
        red: {
          "color": "white",
          "background-color": "#dd1362"
        }
      }
    });    
    function Tip1()
    {
        /*
        $('.tip1').notify("Home Button",
            {position: 'left middle', style: 'std', showDuration: 1000, 
                hideDuration: 400, className : 'white', arrowShow: true  });
                */

    }
    function GiveHeart(shape) {
            var b = Math.floor((Math.random() * 100) + 1);
            var d = ["flowOne", "flowTwo", "flowThree"];
            var a = ["colOne", "colTwo", "colThree", "colFour", "colFive", "colSix"];
            var c = (Math.random() * (1.6 - 1.2) + 1.2).toFixed(1);
            $('<div class="heart part-' + b + " " + a[Math.floor((Math.random() * 6))] + '" style="font-size:' + Math.floor(Math.random() * (50 - 22) + 22) + 'px;"><i class="fa fa-'+ shape + '"></i></div>').appendTo(".hearts").css({
                animation: "" + d[Math.floor((Math.random() * 3))] + " " + c + "s linear"
            });
            $(".part-" + b).show();
            setTimeout(function() {
                $(".part-" + b).remove()
            }, c * 900);
    }	        
    function GiveStars()
    {
        var step;
        for (step = 1; step < 10; step++) {            
            setTimeout(function() {
                GiveHeart('star');
            }, step*100);
        }
        
    }