UserAgentMatching();

$(document).ready( function() {

   
    var visitortime = new Date();
    $('#timezone').val(-visitortime.getTimezoneOffset()/60);

    if(hostedmode === false){
        window.history.pushState(null, null, rootserver1+startupphp+"?v="+mobileversion)
        window.history.pushState(null, null, rootserver1+startupphp+"?v="+mobileversion)
        window.history.replaceState(null, null, rootserver+"console.php");
        /*
        window.location.hash="nbb";
        window.location.hash="Anbb";//again because google chrome don't insert first hash into history
        window.onhashchange=function(){window.location.hash="nbb";}
       */
        $(window).on('popstate', function() {
             //alert('Back button was pressed.'+window.history.popState());
           });       
    }
    $(window).focus(function() {
        isActive = true;
    });
    
    $(window).blur(function() {
        isActive = false;
    });
    
   
    $(document).on('focus', '.autoselect', function(e){
        $(this).select();
    });
    
    $(document).on('click', '.tabenlarge', function(e){
        $('.tabwrapper').addClass('tabwrappertall');
        $('.tablist').addClass('tablisttall');
        $('.tabenlarge').hide();
        $('.tabshrink').show();
    });
    $(document).on('click', '.tabshrink', function(e){
        $('.tabwrapper').removeClass('tabwrappertall');
        $('.tablist').removeClass('tablisttall');
        $('.tabenlarge').show();
        $('.tabshrink').hide();
    });
   /*****************************************************************************
    *
    *    
    *    
    *    *  PANEL
    *  
    * 
    * 
    *****************************************************************************/
        $( window ).resize(function() {
            Sizing();
            ShowBanner();
            if( $('#chatwindow').is(":visible")){
                ScrollChat();
                setTimeout( ResetScrollSensor(), 500);
            }
        });    
        $( window ).on('orientationchange',function() {
            if( $('#chatwindow').is(":visible")){
                setTimeout( function(){
                    ResetScrollSensor();
                    ScrollChat();
                }, 500);
            }
        });    
        
        $(document).on('click', '.showtop', function(e){
            ShowBanner();
        });
        
        $('body').on('click', '.showhidden', function(e){
            $('.showhiddenarea').show();
        });
        $('body').on('click', '.showhidden2', function(e){
            $('.showhiddenarea2').show();
        });
        $('body').on('click', '.showhidden3', function(e){
            $('.showhiddenarea3').show();
        });
        $('body').on('click', '.hidehidden', function(e){
            $('.showhiddenarea').hide();
            $('.showhidden').show();
        });
        $('body').on('click', '.hidehidden2', function(e){
            $('.showhiddenarea2').hide();
            $('.showhidden2').show();
        });
        $('body').on('click', '.hidehidden3', function(e){
            $('.showhiddenarea3').hide();
            $('.showhidden3').show();
        });
        
        $('body').on('click', '.hideshow', function(e){
            $('.showhidden').hide();
        });
        $('body').on('click', '.hideshow2', function(e){
            $('.showhidden2').hide();
        });
        $('body').on('click', '.hideshow3', function(e){
            $('.showhidden3').hide();
        });
        

        //$("img").unveil(300);        
   /*****************************************************************************
    *
    *    
    *    
    *    *  MOUSE ENTER FUNCTIONS
    *  
    * 
    * 
    *****************************************************************************/

        //Workaround for Firefox/Safari
        /*
        $(document).on('click','textarea',function(){
            $(this).focus()
        });
        $(document).on('click','input',function(){
            $(this).focus()
        });
        $(document).on('click','select',function(){
            $(this).focus()
        });
        */
       
        if( MobileType === 'A' || MobileType === 'I'){
        
            $(function() {      
                $(".mainview").swipe({
                   swipeLeft:swipeleft, 
                   swipeRight:swiperight, 
                   allowPageScroll:"vertical" 
               });
                $(".tileview").swipe({
                   swipeLeft:swipeleft, 
                   swipeRight:swiperight, 
                   allowPageScroll:"vertical" 
               });
                $(".settingsview").swipe({
                   swipeLeft:swipeleft, 
                   swipeRight:swiperight, 
                   allowPageScroll:"vertical" 
               });

                function swipeleft(event, phase, direction, distance) {
                    //$('.tilebutton').trigger('click');
                    if(distance > 300){
                        //alert('Reject'+distance);
                        return;
                    }
                    if($('.sidemenuarea').is(":visible")){
                        $('.sidemenuarea').hide();
                    } 
                    /*
                    if(!$('.sidemenuarea').visible()){
                        $('#trigger_tilebutton').click();
                    } 
                    */
                 };
                function swiperight(event, phase, direction, distance) {
                    //$("#trigger_settings").trigger('click');
                    if(distance > 300){
                        //alert('Reject'+distance);
                        return;
                    }
                    if(pinlock ==='Y' && pin!==''){
                        $('.sidemenuarea').hide();
                        return;
                    }
                    $('.sidemenuarea').show(0);
                    
                 };
            });    
        };
        function SwipeFromRoom()
        {
            //alert('Entered Room');
            Rotation = -2;
        }
        function SwipeFromChat()
        {
            //alert('Entered Chat');
            Rotation = -1;
        }
        function SwipeFromSettings()
        {
            //alert('Entered Settings');
            Rotation = -3;
        }
        function SwipeRotation(rotation, direction )
        {
            if( MobileCapable === false){
                return;
            }
            if( $('textarea').is(":focus")){
                return;
            }
            
            /*
            if(rotation === 1){

                //alertify.log("4/4"); 
                rotation = 1;
                $('.tilebutton').trigger('click');
                $(".tileview").scrollTop(0);
            }
            */
            if(rotation === 4 || rotation === -4 ){


                //alertify.set({ delay: 1000 });
                //alertify.log("1/4"); 
                rotation = 4;
                $("#trigger_selectlive").trigger('click');
            }
            if(rotation === 3 || rotation === -3 ){

                //alertify.set({ delay: 1000 });
                //alertify.log("2/4"); 
                rotation = 3;
                $("#trigger_selectchat").trigger('click');
            }
            if(rotation === 2 || rotation === -2 ){

                //alertify.set({ delay: 1000 });
                //alertify.log("3/4"); 
                rotation = 2;
                $("#trigger_room").data("roomid", "0" );
                $("#trigger_room").trigger('click');
            }
            if(rotation === 1 || rotation === -1 ){


                //alertify.set({ delay: 1000 });
                //alertify.log("1/4"); 
                rotation = 1;
                $("#trigger_findpeople").trigger('click');
            }
            
            if(rotation === 5){

                //Not Used
            }
            Sizing();
            return rotation;
                
            
        }
        
        $(window).on( "orientationchange", function() {
            Sizing();
        });
        $('body').on("click", ".closesidemenu", function(){
            $('.sidemenuarea').hide();
        });
        $('body').on("click", ".opensidemenu", function(){
            if(!TermsOfUseCheck()){
                return;
            }
            if(pinlock ==='Y' && pin!==''){
                return;
            }
            $('#functioniframe').prop('src',rootserver+'blank.php');
            $('#functioniframe').hide();
            $('body').scrollLeft(0);
            $('body').click('.tilebuttonview');
            $('.sidemenuarea').show(100);
            
            if(MobileType ==='A' || MobileType==='I'){
                alertify.set({ delay: 2000 });
                alertify.log("Tip: Use Swipe to Navigate"); 
            }
            
        });
        $('body').on("touchstart", ".mainview", function(){
            if($('.sidemenuarea').is(":visible")){
                $('.sidemenuarea').hide();
            }
        });
        
                

        $('body').on("click", ".tilebuttonview", function(){
            
                ChatId = 0;
                ChannelId = 0;
            
                LastFunc = '';
                Rotation = 1;
                $('body').scrollTop(0);
                $('.mainview').scrollTop(0);
                $('.tileview').scrollTop(0);
                $('.settingsview').scrollTop(0);

                $(".tileview").hide().fadeIn(800);
                ShowBanner();
                $(".mainview").hide();
                $(".settingsview").hide();
                $(".roomsview").hide();
                $(".notificationsview").hide();
                $(".notificationspopup").hide();
               
        });
        $('body').on("click", ".tilebutton", function(){
                if(!TermsOfUseCheck()){
                    return;
                }
                if(!AppStoreCheck()){
                }
                Rotation = 1;
                ChatId = 0;
                ChannelId = 0;
                $('body').scrollTop(0);
                LastFunc = '';
                ShowBanner();
                $('.mainview').scrollTop(0);
                $('.tileview').scrollTop(0);
                $('.settingsview').scrollTop(0);
                
                $(".tileview").hide().fadeIn(800);
                $(".mainview").hide();
                $(".settingsview").hide();
                $(".roomsview").hide();
                $('.tilebutton').removeClass('blinking2');
                $(".notificationsview").hide();
                $(".notificationspopup").hide();
                ResetLastFunction();
                Sizing();
                
                //alertify.set({ delay: 500 });
                //alertify.log("Menu"); 
               
        });
        $('body').on("click", ".mainbutton", function(){
            
                ShowBanner();
                $('.mainview').scrollTop(0);
                $('.tileview').scrollTop(0);
                $('.settingsview').scrollTop(0);
                
                $(".tileview").hide();
                $(".mainview").hide().fadeIn(800);
                $(".settingsview").hide();
                $(".roomsview").hide();
                $(".notificationsview").hide();
                $(".notificationspopup").hide();
                Sizing();
        });
        $('body').on("click", ".settingsbutton", function(){
            if(!TermsOfUseCheck()){
                return;
            }
                ChatId = 0;
                ChannelId = 0;
                Rotation = 1;
                $('.mainview').scrollTop(0);
                $('.tileview').scrollTop(0);
                $('.settingsview').scrollTop(0);

            //No Toggle
                LastFunc = '';
                SwipeFromSettings();
                
                $(".tileview").hide();
                $(".settingsview").hide().fadeIn("800");
                $(".mainview").show();
                
                PanelShow(33);
                /*
                $(".settingsview").hide().fadeIn("800");
                $(".tileview").hide();
                //$(".mainview").hide();
                $(".roomsview").hide();
                $(".notificationsview").hide();
                $(".notificationspopup").hide();
                $('#functioniframe').prop('src','blank.php');
                */
                ResetLastFunction();
                Sizing();
                //alertify.set({ delay: 500 });
                //alertify.log("Settings"); 
        });
        $('body').on("click", ".settingsaction", function(){
            if($(".settingsview").is(":visible"))
            {
                LastFunc = '';
                $('.mainview').scrollTop(0);
                $('.tileview').scrollTop(0);
                $('.settingsview').scrollTop(0);
                
                $(".settingsview").hide();
                $(".tileview").hide();
                $(".mainview").hide().fadeIn(800);
                $(".roomsview").hide();
                $(".notificationsview").hide();
                $(".notificationspopup").hide();

            }
        });
        
        $('body').on('click','.colorchoice', function()
        {
            var mode = $(this).data('mode');
            var colorscheme = $(this).data('colorscheme');
            var wallpaper = $(this).data('wallpaper');
            $.ajax({
                url: rootserver+"colorchoice.php",
                context: document.body,
                type: 'POST',
                data: 
                 { 'providerid': $('#pid').val(), 
                   'colorscheme': colorscheme,
                   'wallpaper': wallpaper,
                   'mode' : mode
                 }
            }).done(function(data,status){ 
                if( mode !==''){
                    window.location = rootserver1+startupphp+"?s="+source+"&e="+enterprise+"&apn="+apn+"&gcm="+gcm+"&v="+mobileversion;
                     return;
                }
                PanelShow(8);
                $('#socialwindow').html(data);
                
            });
        });
        $('body').on('click','.languagechoice', function()
        {
            var mode = $(this).data('mode');
            var language = $(this).data('language');
            $.ajax({
                url: rootserver+"languagechoice.php",
                context: document.body,
                type: 'POST',
                data: 
                 { 'providerid': $('#pid').val(), 
                   'language': language,
                   'mode' : mode
                 }
            }).done(function(data,status){ 
                if( mode !==''){
                    window.location = rootserver1+startupphp+"?s="+source+"&e="+enterprise+"&apn="+apn+"&gcm="+gcm+"&v="+mobileversion;
                     return;
                }
                PanelShow(8);
                $('#socialwindow').html(data);
                
            });
        });
        
        
        $('body').on('click','.fillnotearea', function()
        {
                var mode = $(this).data('mode');
                
                var strWindowFeatures = "menubar=no,location=no,resizable=yes,titlebar=no,scrollbars=yes,status=no,width=800,height=600,top=40,left=40";
                //var myWindow=window.open('https://periscope.tv/robmusic','_blank',strWindowFeatures);
                myWindow.focus();
                return;
                
                //$('#noteareaiframe').prop('src','blank.php');
                $('#popupwindow').html('');
                
                //LoadingShowMessage1();
                //$('#noteareaiframe').prop('src','https://msn.com');
                $('#noteareaiframe').attr('src','https://periscope.tv/robmusic');
                //$('.newnotearea').attr("action","https://whatthezuck.net");
                if(tester === 'Y'){
                    //PanelShow(35);
                }
                //$('.newnotearea').submit();
        });
        
        
        $('body').on('click','.audiostream', function()
        {
                var mode = $(this).data('mode');
                
                $('#streamiframe').prop('src',rootserver+'blank.php');
                audiostreamactive = false;
                $('#popupwindow').html('');
                
                if( mode === 'STOP'){
                    NativeCall('sleep');
                    visibleChatPanel = false;
                    videoActive = false;
                    Sizing();
                    $('.noteareadiv').html('');
                    
                    $('.mobilenoteareadiv').hide();
                    $('.mobilenoteareadiv').html('');
                    
                    
                    ScrollChat();
                    alertify.set({ delay: 1000 });
                    alertify.log("Stream Stopped"); 
                    return;
                }
                //LoadingShowMessage1();
                $('#audiostream').find('#audiostream_streamid').val( $(this).data('streamid') );
                $('#audiostream').find('#audiostream_chatid').val( $(this).data('chatid') );
                if(tester === 'Y'){
                    //shows audio stream player
                    PanelShow(35);
                }
                $('#audiostream').submit();
                audiostreamactive = true;
                alertify.set({ delay: 15000 });
                alertify.log("Loading Audio Stream - Please Wait"); 
                NativeCall('nosleep');
        });
        $('body').on('click','.videostream', function()
        {
                var mode = $(this).data('mode');
                
                if( mode === 'STOP'){
                    visibleChatPanel = false;
                    videoActive = false;
                    Sizing();
                    $('.noteareadiv').html('');
                    
                    $('.mobilenoteareadiv').hide();
                    $('.mobilenoteareadiv').html('');
                    
                    
                    ScrollChat();
                    return;
                }
        });
        
        $('body').on('click','.audioreplay', function()
        {
            $('#streamiframe').prop('src',rootserver+'blank.php');
            audiostreamactive = false;
            var chatid = $(this).data('chatid');
            $.ajax({
                url: rootserver+'replays.php',
                context: document.body,
                type: 'POST',
                data: 
                {   'providerid': $('#pid').val(),
                    'chatid': chatid
                }

            }).done(function( data, status ) {
                PanelShow(9);
                $('#popupwindow').html(data);
            });
            
        });
        $('body').on('click','.audiokillsound', function()
        {
            $('#streamiframe').prop('src',rootserver+'blank.php');
            audiostreamactive = false;
        });
        $('body').on('click','.audioreplayitem', function()
        {
            $('#streamiframe').prop('src',rootserver+'blank.php');
            audiostreamactive = false;
            var chatid = $(this).data('chatid');
            var broadcastid = $(this).data('broadcastid');
            var filename = $(this).data('filename');
            $.ajax({
                url: rootserver+'audiostreamreplay.php',
                context: document.body,
                type: 'POST',
                data: 
                {   'providerid': $('#pid').val(),
                    'filename' : filename,
                    'chatid': chatid,
                    'broadcastid' : broadcastid
                }

            }).done(function( data, status ) {
                PanelShow(9);
                $('#popupwindow').html(data);
            });
            
        });
        $('body').on('click','.audiopanel_desktop',function(){ 
            var chatid = $(this).data('chatid');
            if( chatid === ''){
                return;
            }
            $('#trigger_audiopanel_desktop').data('chatid',chatid);
            $('#trigger_audiopanel_desktop').click();
        });
        $('body').on('click','.audiopanel_mobile',function(){ 
            var chatid = $(this).data('chatid');
            $('#trigger_audiopanel_mobile').data('chatid',chatid);
            $('#trigger_audiopanel_mobile').click();
        });
        
        $('body').on('click','.audiopanel', function()
        {
            var chatid = $(this).data('chatid');
            var mode = $(this).data('mode');
            if(chatid ===''){
                //alert('no chatid');
                return;
            }
            $.ajax({
                url: rootserver+"chatpopup.php",
                context: document.body,
                type: 'POST',
                data: 
                 { 'providerid': $('#pid').val(), 
                   'chatid' : chatid
                 }
            }).done(function(data,status){ 
                var msg = jQuery.parseJSON(data);
                if( msg.data ===''){
                    return;
                }
                if( msg.panel === 'panel'){
                    //$('.chatheading').height(70);
                    videoActive = true;
                    $('.chatheading').show();
                    if(mode === ''){
                        visibleChatPanel = true;
                        Sizing();
                        $('.noteareadiv').html(msg.data);
                        $('.noteareadiv').html(msg.data);
                        $('.noteareadiv2').html(msg.fullscreen);
                        $('.noteareadiv').show();
                        $('.mobilenoteareadiv').hide();
                        $('.mobilenoteareadiv').html('');
                    } else
                    if(mode === 'M'){
                        visibleChatPanel = false;
                        
                        $('.noteareadiv').html('');
                        $('.mobilenoteareadiv').show();
                        ResizeChatWindow();
                        $('.mobilenoteareadiv').html(msg.data);
                    } else
                    if(mode === 'M2'){
                        visibleChatPanel = false;
                        
                        $('.noteareadiv').html('');
                        $('.mobilenoteareadiv').show();
                        if($('.mobilenoteareadiv').html()===''){
                            $('.mobilenoteareadiv').html(msg.data);
                            $('.mobilenoteareadiv').html(msg.data);
                            ResizeChatWindow();
                        }
                    }
                    ScrollChat();
                    return;
                }
                if( msg.panel === 'popup'){
                    if(popupwin!==null){
                        popupwin.close();
                    }

                    popupwin = window.open(msg.data,'1494607437105', msg.param );
                    popupwin.focus();
                }
                
            });
        });
        $('body').on('click','.startaudiostream', function()
        {
            $('#trigger_audiostream').click();
        
        });
        
        $('body').on('click','.audiopanelbroadcaster', function()
        {
            var chatid = $(this).data('chatid');
            var mode = $(this).data('mode');
            if( mode!=='WEBCAM'){
                mode = 'B';
            }
            
            $.ajax({
                url: rootserver+"chatpopup.php",
                context: document.body,
                type: 'POST',
                data: 
                 { 'providerid': $('#pid').val(), 
                   'chatid' : chatid,
                   'mode' : mode
                 }
            }).done(function(data,status){ 
                var msg = jQuery.parseJSON(data);
                if( msg.data ===''){
                    return;
                }
                if( msg.panel === 'panel'){
                    //$('.chatheading').height(70);
                    videoActive = true;
                    $('.chatheading').show();
                    if(mode === 'B' || mode === 'WEBCAM'){
                        
                        visibleChatPanel = true;
                        Sizing();
                        $('.noteareadiv').html(msg.data);
                        $('.noteareadiv2').html(msg.fullscreen);
                        $('.mobilenoteareadiv').hide();
                        $('.mobilenoteareadiv').html('');
                    }
                    ScrollChat();
                    return;
                }
                
            });
        });
        
        $('body').on('click','.audioopenpopup', function()
        {
            var chatid = $(this).data('chatid');
            $.ajax({
                url: rootserver+"chatpopup.php",
                context: document.body,
                type: 'POST',
                data: 
                 { 'providerid': $('#pid').val(), 
                   'chatid' : chatid
                 }
            }).done(function(data,status){ 
                if(data == ''){
                    return;
                }
                var param = 'width=500,height=500,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=1000,top=100';
                if(popupwin!==null){
                    popupwin.close();
                }

                popupwin = window.open(data,'1494607437105', param );
                popupwin.focus();
            });
        });
        
        $('body').on('click','.notifyaudiostream', function()
        {
            var chatid = $(this).data('chatid');
            var mode = $(this).data('mode');
            var action = $(this).data('action');
            var prompt = '';
            AbortAjax();
            $('.chatextraarea').hide();
            alertify.set({ delay: 2000 });
            
            if(mode === 'BROADCASTER'){
                prompt = 'Confirm - I am broadcasting on this channel.<br><br>Click CANCEL if you are a listener.';
                var title =  $('#audiostreamtitle').val();
                var popupurl =  $('#audiopopupurl').val();
                
                if( action === 'VIDEO'){
                    //prompt = 'Start a Video Broadcast?';
                    PanelShow(9);
                    $('#popupwindow').load( rootserver+"videoconfirm.php",  {
                        'providerid': $('#pid').val(),
                        'chatid' : chatid,
                        'mode' : mode,
                    }, function(html, status){
                    });
                    return;
                }
                if( action === 'VIDEOCONFIRM'){
                    action = "VIDEO";
                    prompt = 'Start Stream';
                    //$('.chatwindow').html(LoadingGIF);
                    PanelShow(3);
                }
                
                if( action === 'TITLE'){
                    prompt = 'Confirm - Change Broadcast Info?';
                }
                if( action === 'WEBCAM'){
                    prompt = 'Start a Webcam Broadcast?';
                }

                alertify.confirm(prompt,function(ok){
                    if( ok ){
                        $('#chatmessage').load( rootserver+"chatsend.php",  {
                            'providerid': $('#pid').val(),
                            'chatid' : chatid,
                            'mode' : mode,
                            'title' : title,
                            'popupurl' : popupurl,
                            'action' : action
                        }, function(html, status){
                            if(html!==''){
                                alertify.set({ delay: 3000 });
                                 alertify.log(html); 
                            } else {
                                ActiveChat(true,'');
                            }
                        });
                        return;
                    }
                });
                return;
            } else
            if(mode == 'STREAM'){
                alertify.log("Notifying All Members - On-Air"); 
            } else 
            if(mode == 'LIKE'){
                alertify.log("Cool!"); 
            } else 
            if(mode == 'ENDBROADCAST'){
                alertify.log("Ending Broadcast"); 
                $('.noteareadiv').html('');

                $('.mobilenoteareadiv').hide();
                $('.mobilenoteareadiv').html('');
            } else {
                alertify.log("..."); 
            }
            $('#chatmessage').load( rootserver+"chatsend.php",  {
                'providerid': $('#pid').val(),
                'chatid' : chatid,
                'action' : action,
                'mode' : mode
            }, function(html, status){
                //PanelShow(3);
                ActiveChat(true,'');
            });
            
        });
        $('body').on('click','.fullscreenvideo', function()
        {
            //alertify.alert("fullscreenvideo");
            if($('.maincontentarea').is(":visible")){
                $('.maincontentarea').hide();
                $('.notearea').width(2000);
            } else {
                $('.maincontentarea').show();
                Sizing();
            }
        });
        $('body').on('click','.streamsched', function()
        {
            var mode = $(this).data('mode');
            var eventdate = $('#event_date').val();
            var eventtime = $('#event_time').val();
            var eventname = $('#event_name').val();
            var eventdesc = $('#event_desc').val();
            var eventstation = $('#event_station').val();
            var eventid = $(this).data('eventid');
            $.ajax({
                url: rootserver+"streamsched.php",
                context: document.body,
                type: 'POST',
                data: 
                 { 'providerid': $('#pid').val(), 
                   'eventdate': eventdate,
                   'eventtime': eventtime,
                   'eventname': eventname,
                   'eventdesc': eventdesc,
                   'eventstation': eventstation,
                   'eventid' : eventid,
                   'mode' : mode
                 }
            }).done(function(data,status){ 
                var msg = jQuery.parseJSON(data);
                if( msg.data ===''){
                    alertify.alert('No schedule available');
                    return;
                }
                PanelShow(8);
                $('#socialwindow').html(msg.data);
                $('#socialwindow').hide().fadeIn(800);
                
            });
        });
        
        
        $('body').on("keyup",".handletype", function(e)
        {
            var handle = $(this).val();
            handle = handle.replace(/[^a-z0-9@]/gi, "");  
            if( handle.charAt(0)!=='@' && handle!==''){
                handle = '@'+handle;
            }
            $(this).val(handle);
        });        
        $('body').on("blur",".handletype", function(e)
        {
            var handle = $(this).val();
            if( handle === '@'){
                handle = '';
            }
            $(this).val(handle);
        });        
        
        
        $('body').on("mouseenter", ".divbutton", function(){
            $(this).removeClass('divbutton_unsel').addClass('divbutton_sel');
            
        });
        $('body').on("mouseleave", ".divbutton", function(){
            $(this).removeClass('divbutton_sel').addClass('divbutton_unsel');
            
        });
        
        
        $('body').on("mouseenter", ".divbutton2", function(){
            $(this).removeClass('divbutton2_unsel').addClass('divbutton2_sel');
            
        });
        $('body').on("mouseleave", ".divbutton2", function(){
            $(this).removeClass('divbutton2_sel').addClass('divbutton2_unsel');
            
        });
        
        
        $('body').on("mouseenter", ".divbutton3", function(){
            $(this).removeClass('divbutton3_unsel').addClass('divbutton3_sel');
            
        });
        $('body').on("mouseleave", ".divbutton3", function(){
            $(this).removeClass('divbutton3_sel').addClass('divbutton3_unsel');
            
        });
        
        $('body').on("mouseenter", ".divbuttonshare", function(){
            $(this).removeClass('divbutton3_unsel').addClass('divbutton3_sel');
            
        });
        $('body').on("mouseleave", ".divbuttonshare", function(){
            $(this).removeClass('divbutton3_sel').addClass('divbutton3_unsel');
            
        });
        
        $('body').on("mouseenter", ".divbuttontext", function(){
            $(this).removeClass('divbuttontext_unsel').addClass('divbuttontext_sel');
            
        });
        $('body').on("mouseleave", ".divbuttontext", function(){
            $(this).removeClass('divbuttontext_sel').addClass('divbuttontext_unsel');
            
        });
        
        $('body').on("mouseenter", ".divbuttonlike0", function(){
            $(this).removeClass('divbuttonlike_unsel').addClass('divbuttonlike_sel');
            
        });
        $('body').on("mouseleave", ".divbuttonlike0", function(){
            $(this).removeClass('divbuttonlike_sel').addClass('divbuttonlike_unsel');
            
        });
        $('body').on("mouseenter", ".divbuttonlike1", function(){
            $(this).removeClass('divbuttonlike_unsel').addClass('divbuttonlike_sel');
            
        });
        $('body').on("mouseleave", ".divbuttonlike1", function(){
            $(this).removeClass('divbuttonlike_sel').addClass('divbuttonlike_unsel');
            
        });
        
        
        
        $('body').on("mouseenter", ".divbutton4", function(){
            $(this).removeClass('divbutton4_unsel').addClass('divbutton4_sel');
            
        });
        $('body').on("mouseleave", ".divbutton4", function(){
            $(this).removeClass('divbutton4_sel').addClass('divbutton4_unsel');
            
        });
        
        $('body').on("mouseenter", ".divbuttonsidebar", function(){
            $(this).removeClass('divbuttonsidebar_unsel').addClass('divbuttonsidebar_sel');
            
        });
        $('body').on("mouseleave", ".divbuttonsidebar", function(){
            $(this).removeClass('divbuttonsidebar_sel').addClass('divbuttonsidebar_unsel');
            
        });
        
        $('body').on("mouseenter", ".divbuttontilebar", function(){
            $(this).removeClass('divbuttontilebar_unsel').addClass('divbuttontilebar_sel');
            
        });
        $('body').on("mouseleave", ".divbuttontilebar", function(){
            $(this).removeClass('divbuttontilebar_sel').addClass('divbuttontilebar_unsel');
            
        });
        
        /*
        $('body').on("mouseenter", ".divbuttontilebar2", function(){
            $(this).removeClass('divbuttontilebar_unsel').addClass('divbuttontilebar_sel');
            
        });
        $('body').on("mouseleave", ".divbuttontilebar2", function(){
            $(this).removeClass('divbuttontilebar_sel').addClass('divbuttontilebar_unsel');
            
        });
    */
        

        $('body').on("mouseenter", ".divbutton5", function(){
            $(this).removeClass('divbutton5_unsel').addClass('divbutton5_sel');
            
        });
        $('body').on("mouseleave", ".divbutton5", function(){
            $(this).removeClass('divbutton5_sel').addClass('divbutton5_unsel');
            
        });
        
        $('body').on("mouseenter", ".divbuttoncolor1", function(){
            $(this).removeClass('divbuttoncolor1_unsel').addClass('divbuttoncolor1_sel');
        });
        $('body').on("mouseleave", ".divbuttoncolor1", function(){
            $(this).removeClass('divbuttoncolor1_sel').addClass('divbuttoncolor1_unsel');
        });

        $('body').on("mouseenter", ".divbuttoncolor2", function(){
            $(this).removeClass('divbuttoncolor2_unsel').addClass('divbuttoncolor2_sel');
        });
        $('body').on("mouseleave", ".divbuttoncolor2", function(){
            $(this).removeClass('divbuttoncolor2_sel').addClass('divbuttoncolor2_unsel');
        });

        $('body').on("mouseenter", ".divbuttoncolor3", function(){
            $(this).removeClass('divbuttoncolor3_unsel').addClass('divbuttoncolor3_sel');
        });
        $('body').on("mouseleave", ".divbuttoncolor3", function(){
            $(this).removeClass('divbuttoncolor3_sel').addClass('divbuttoncolor3_unsel');
        });

        $('body').on("mouseenter", ".divhighlight", function(){
            $(this).removeClass('divhighlight_unsel').addClass('divhighlight_sel');
        });
        $('body').on("mouseleave", ".divhighlight", function(){
            $(this).removeClass('divhighlight_sel').addClass('divhighlight_unsel');
        });
        
        
        
        $('body').on("mouseenter", ".messagesselect", function(){
            $(this).removeClass('messages_unsel').addClass('messages_sel');
            /*
            if( $('#imapmovemenu').is(":visible"))
            {
                $('#imapmovemenu').hide();
            }
            */
            
        });
        $('body').on("mouseleave", ".messagesselect", function(){
            $(this).removeClass('messages_sel').addClass('messages_unsel');
            
        });
        
        $('body').on("click", ".divtile", function(){
            $(this).addClass('.desaturate');
        });

        $('body').on("touchstart", ".tapped2", function(e){
            startX = getCoord(e, 'X');
            startY = getCoord(e, 'Y');            
            var tapped = this;
            $(tapped).css('opacity',"0.5");
        });
        $('body').on("touchstart", ".tapped", function(e){
            startX = getCoord(e, 'X');
            startY = getCoord(e, 'Y');            
            var tapped = this;
            $(tapped).css('opacity',"0.5");
            dragging = false;
            e.preventDefault();
            //e.stopPropagation();
        });
        $('body').on("touchmove", ".tapped", function(e){
            dragging = true;
            var tapped = this;
            opacity = "1.0";
            $(tapped).css('opacity',opacity);
            if ( Math.abs(getCoord(e, 'X') - startX) < 5 &&
                 Math.abs(getCoord(e, 'Y') - startY) < 5) {
                dragging = false;
                // Prevent emulated mouse events
            }            
                        //e.preventDefault();
            //e.stopPropagation();
        });
        
        //Tapped with delay allow scroll/swipe
        $('body').on("touchend", ".tapped2", function(){
            dragging = false;
            var opacity = $(this).css('opacity');
            opacity = "1.0";
            var tapped = this;
            $(tapped).css('opacity',"0.5");
            setTimeout(function(){
                $(tapped).css('opacity',opacity);
            },500);
           
        });
        
        //Tapped for No Delay and ignore scroll/swipe
        $('body').on("touchend", ".tapped", function(){
            var tapped = this;
            var opacity = $(tapped).css('opacity');
            opacity = "1.0";
            setTimeout(function(){
                $(tapped).css('opacity',opacity);
            },200);
            if( dragging){
                dragging = false;
                return;
            }
            dragging = false;
            $(tapped).click();
           
        });
        
        function getCoord(e, c) {
            return /touch/.test(e.type) ? (e.originalEvent || e).changedTouches[0]['page' + c] : e['page' + c];
        }        
   /*****************************************************************************
    *
    *    
    *    
    *    *  SETTINGS
    *  
    * 
    * 
    *****************************************************************************/
        $('body').on('click','.forpublic', function()
        {
            alertify.alert( "Open sharing is not available for private rooms." );
            
        });
        $('body').on('click','.forowner', function()
        {
            alertify.alert( "Room invite is available only to the room owner/moderator." );
            
        });

        $('body').on('click','.actionitems', function()
        {
            alertify.alert( "Please set up an Email Account to fully utilize the app features." );
            
        });
        
        $('body').on('mouseenter','.panelhost', function(){
            $('#imapmovemenu').hide();
        });
        $('body').on('click','.downloadimg', function() 
        {
            var imgIdName = $(this).data('imgid');
            if(imgIdName ==='' || typeof imgIdName === 'undefined'){
                return;
            }
            alertify.confirm('Download to Photo Library?',function(ok){
                if( ok ){
            
                    imgId = document.getElementById(imgIdName);
                    //localStorage.removeItem("base64image");
                    //var imgId = $('#photolib_photoenlarged').get(0);
                    var imgData = getBase64Image(imgId);
                    if( imgData === ''){
                        return;
                    }
                    localStorage.setItem("base64image", imgData);            
                    window.location = 'http://brax.me/command/downloadphoto/jpg';
                }
            });
        });
        function getBase64Image(img) {
            if(img.width === 0 || img.height === 0){
                return "";
            }
            
            var canvas = document.createElement("canvas");
            canvas.width = img.width;
            canvas.height = img.height;

            var ctx = canvas.getContext("2d");
            ctx.drawImage(img, 0, 0);
            var dataURL = canvas.toDataURL('image/jpeg');

            return dataURL.replace(/^data:image\/(png|jpg|jpeg);base64,/, "");
            
        }
        $('body').on('click','.certcheck', function() 
        {
            NativeCall("certcheckbrax.me");
            //window.location = 'http://brax.me/command/certcheck/brax.me';
        });
        
        $('body').on('click','.restart', function() 
        {
            $.ajax({
                url: rootserver+'accountsetting.php',
                context: document.body,
                type: 'POST',
                data: 
                 { 'providerid': $('#pid').val(), 
                   'mode' : "RESTART"
                 }
             }).done(function( data, status ) {
                 if(MobileType ==='A' || MobileType==='I'){
                     NativeCall("restart");
                     return;
                 }
                window.location = rootserver1+startupphp+"?s="+source+"&e="+enterprise+"&apn="+apn+"&gcm="+gcm+"&v="+mobileversion;
            });
        });
        $('body').on('click','#homepage', function() 
        {
            window.location = rootserver1;
        });
        $('body').on('click','.newtab', function() 
        {
            if(!TermsOfUseCheck()){
                return;
            }
            window.open( rootserver+"console.php?"+timeStamp(),"_blank");
            //$('.tileview').show();
            //$('.mainview').hide();
            return;
        });

        $('body').on('click','.logoutbutton', function() 
        {
            if(xhr && xhr.readyState !== 4){
                xhr.abort();
            }
            $('#alert').html(OKMessage);
            try {
                if(!MobileCapable) {
                    localStorage.removeItem("pid");
                }
                /* //localStorage.clear(); */

                localStorage.removeItem("swt");
                localStorage.removeItem("password");
                localStorage.removeItem("pw");
                localStorage.removeItem("lchat");
                localStorage.removeItem("chat");

            } catch(err) {}
            
            try {
                notificationunsubscribe();
            } catch(err) {}

            /*
            var mobileversion1 = mobileversion;
            if( typeof localStorage.mobileversion !== 'undefined'){
                mobileversion1 = localStorage.mobileversion;
            }
            if(mobileversion1 === 'undefined'){
                mobileversion1 = '';
            }
            */
            var test = 'test';
            if(mobileversion!=='000' && mobileversion!==''  ){
                NativeCall("restart");
                return;
            }
            window.location = rootserver1+startupphp+"?s="+source+"&e="+enterprise+"&apn="+apn+"&gcm="+gcm+"&v="+mobileversion;
                
        });
        

        $('body').on('click','.helpinfo', function()
        {
            var help = $(this).data('help');
            alertify.alert(help);
        });
        $('body').on('click','.initmsgqueue1', function()
        {
            alertify.confirm('Reinitialize Mail Queue? This will regenerate the mail cache.',function(ok){
                if( ok ){
                
                    alertify.set({ delay: 2000 });
                    alertify.log("Initializing Messages"); 
                    //alertify.alert("Initializing Messages");
                    xhr = $.ajax({
                        url: rootserver+"messagesimap.php",
                        context: document.body,
                        type: 'POST',
                        data: 
                         { 'providerid': $('#pid').val(), 
                           'mode' : "N"
                         }
                    });
                }
            });
        });
        $('body').on('click','.help_enterprise_room', function()
        {
            PanelShow(9);
            $('.popupwindow').html(LoadingGIF);
            $('.popupwindow').load( rootserver+"help_enterprise_room.php?"+timeStamp());
        });
        $('body').on('click','.help_enterprise_invite', function()
        {
            PanelShow(9);
            $('.popupwindow').html(LoadingGIF);
            $('.popupwindow').load( rootserver+"help_enterprise_invite.php?"+timeStamp());
        });
        $('body').on('click','.help_enterprise_csv', function()
        {
            PanelShow(9);
            $('.popupwindow').html(LoadingGIF);
            $('.popupwindow').load( rootserver+"help_enterprise_csv.php?"+timeStamp());
        });
        $('body').on('click','.help_enterprise_groupsend', function()
        {
            PanelShow(9);
            $('.popupwindow').html(LoadingGIF);
            $('.popupwindow').load( rootserver+"help_enterprise_groupsend.php?"+timeStamp());
        });
        $('body').on('click','.help_enterprise_inforequest', function()
        {
            PanelShow(9);
            $('.popupwindow').html(LoadingGIF);
            $('.popupwindow').load( rootserver+"help_enterprise_inforequest.php?"+timeStamp());
        });

    
        $('body').on('click','.help_room', function()
        {
            PanelShow(9);
            $('.popupwindow').html(LoadingGIF);
            $('.popupwindow').load( rootserver+"help_room.php?"+timeStamp());
        });
        $('body').on('click','.help_chat', function()
        {
            PanelShow(9);
            $('.popupwindow').html(LoadingGIF);
            $('.popupwindow').load( rootserver+"help_chat.php?"+timeStamp());
        });
        $('body').on('click','.help_file', function()
        {
            PanelShow(9);
            $('.popupwindow').html(LoadingGIF);
            $('.popupwindow').load( rootserver+"help_file.php?"+timeStamp());
        });
        $('body').on('click','.help_photo', function()
        {
            PanelShow(9);
            $('.popupwindow').html(LoadingGIF);
            $('.popupwindow').load( rootserver+"help_photo.php?"+timeStamp());
        });
        
        $('body').on('click','.info_room', function()
        {
            PanelShow(9);
            $('.popupwindow').html(LoadingGIF);
            $('.popupwindow').hide().load( rootserver+"about_room.php?"+timeStamp()).fadeIn(800);
        });
        $('body').on('click','.info_chat', function()
        {
            PanelShow(9);
            $('.popupwindow').html(LoadingGIF);
            $('.popupwindow').hide().load( rootserver+"about_chat.php?"+timeStamp()).fadeIn(800);
        });
        $('body').on('click','.info_radio', function()
        {
            PanelShow(9);
            $('.popupwindow').html(LoadingGIF);
            $('.popupwindow').hide().load( rootserver+"about_radio.php?"+timeStamp()).fadeIn(800);
        });
        $('body').on('click','.info_photo', function()
        {
            PanelShow(9);
            $('.popupwindow').html(LoadingGIF);
            $('.popupwindow').hide().load( rootserver+"about_photo.php?"+timeStamp()).fadeIn(800);
        });
        $('body').on('click','.info_email', function()
        {
            PanelShow(9);
            $('.popupwindow').html(LoadingGIF);
            $('.popupwindow').hide().load( rootserver+"about_email.php?"+timeStamp()).fadeIn(800);
        });
        $('body').on('click','.info_file', function()
        {
            PanelShow(9);
            $('.popupwindow').html(LoadingGIF);
            $('.popupwindow').hide().load( rootserver+"about_file.php?"+timeStamp()).fadeIn(800);
        });
        $('body').on('click','.info_final', function()
        {
            PanelShow(9);
            $('.popupwindow').html(LoadingGIF);
            $('.popupwindow').hide().load( rootserver+"about_final.php?"+timeStamp()).fadeIn(800);
        });
        $('body').on('click','.info_final2', function()
        {
            PanelShow(9);
            $('.popupwindow').html(LoadingGIF);
            $('.popupwindow').hide().load( rootserver+"about_final2.php?"+timeStamp()).fadeIn(800);
        });
        
        $('body').on('click','.notifysubscribe', function()
        {
            //if( DeviceCode==='chrome'){
                PanelShow(9);
                ('.popupwindow').html(LoadingGIF);
                $('.popupwindow').load( rootserver+"notifysubscribe.php?"+timeStamp());
            //}
        });
        $('body').on('click','.notifysubscribe1', function()
        {
            notificationsubscribe(true);
        });
        
        
        $('body').on('click','.about', function()
        {
            PanelShow(9);
            $('.popupwindow').html(LoadingGIF);
            $('.popupwindow').hide().load( rootserver+"about.php?"+timeStamp()).fadeIn(800);
        });
        $('body').on('click','.about0', function()
        {
            PanelShow(9);
            $('.popupwindow').html(LoadingGIF);
            $('.popupwindow').hide().load( rootserver+"about0.php?"+timeStamp()).fadeIn(800);
        });
        $('body').on('click','.about2', function()
        {
            PanelShow(9);
            $('.popupwindow').html(LoadingGIF);
            $('.popupwindow').hide().load( rootserver+"about2.php?"+timeStamp()).fadeIn(800);
        });

    
        $('body').on('click','.aboutenterprise', function()
        {
            PanelShow(9);
            $('.popupwindow').html(LoadingGIF);
            $('.popupwindow').load( rootserver+"aboutenterprise.php?"+timeStamp());
        });
    
        
        
        $('body').on('click','#helpbutton', function()
        {
            PanelShow(6);
            $('.firsttime').load( rootserver+"firsttime.php");
            $('.securityguide').load( rootserver+"securityguide.php");
        });
        $('body').on('click','.termsofusedisplay', function()
        {
            PanelShow(34);
            $('#prestart').load( rootserver+"license-v1-body.php?i=N",  {});
        });
        $('body').on('click','.appstoredisplay', function()
        {
            PanelShow(34);
            $('#prestart').load( rootserver+"appstore.php",  {});
        });
        $('body').on('click','.termsofuseagree', function()
        {
            $('#prestart').hide();
            $('#prestart').html("");
            $.ajax({
                url: rootserver+'accountsetting.php',
                context: document.body,
                type: 'POST',
                data: 
                 { 'providerid': $('#pid').val(), 
                   'mode' : "AGREE"
                 }
             }).done(function( data, status ) {
                $('.tilebutton').trigger('click');
                termsofuse = 'Y';
                RunAtStartup();
                $('#prestart').html("");
                $('#prestart').hide();
            });
            
        });
        $('body').on('click','.termsofusedisagree', function()
        {
            $.ajax({
                url: rootserver+'accountsetting.php',
                context: document.body,
                type: 'POST',
                data: 
                 { 'providerid': $('#pid').val(), 
                   'mode' : "DISAGREE"
                 }
             }).done(function( data, status ) {
                $('#prestart').hide();
                if(mobileversion!=='000' && mobileversion!==''  ){
                    NativeCall("restart");
                    return;
                }
                
                window.location = rootserver1+startupphp+"?s="+source+"&e="+enterprise+"&apn="+apn+"&gcm="+gcm+"&v="+mobileversion;
            });
        });
        $('body').on('click','.socialmediaenable', function()
        {
            $.ajax({
                url: rootserver+'accountsetting.php',
                context: document.body,
                type: 'POST',
                data: 
                 { 'providerid': $('#pid').val(), 
                   'mode' : "SOCIALMEDIAPROMPT"
                 }
             }).done(function( data, status ) {
                var prompt = data;
                
                alertify.confirm( prompt ,function(ok){
                    if( ok ){
                        $.ajax({
                            url: rootserver+'accountsetting.php',
                            context: document.body,
                            type: 'POST',
                            data: 
                             { 'providerid': $('#pid').val(), 
                               'mode' : "SOCIALMEDIAOK"
                             }
                         }).done(function( data, status ) {
                            $('#prestart').hide();
                            if(mobileversion!=='000' && mobileversion!==''  ){
                                NativeCall("restart");
                                return;
                            }
                            
                            window.location = rootserver1+startupphp+"?s="+source+"&e="+enterprise+"&apn="+apn+"&gcm="+gcm+"&v="+mobileversion;
                        });
                    }
                });
                
                
            });
            
                
        });
        
        $('body').on('click','.privacydisplay', function()
        {
            AbortAjax();
            PanelShow(30);
            
            $('#functioniframe').prop('src',rootserver+'blank.php');
            $('#privacyform').submit();
        });
        $('body').on('click','.privacytip', function()
        {
            AbortAjax();
            PanelShow(30);
            
            $('#functioniframe').prop('src',rootserver+'blank.php');
            $('#privacytipform').submit();
        });
        $('body').on('click','.changeavatar', function()
        {
                if(xhr && xhr.readyState !== 4){
                    xhr.abort();
                }
            $(".mainview").scrollTop(0);
            PanelShow(20);
            $('#functioniframe').prop('src',rootserver+'blank.php');
            
            
            $('form#avatarform').prop("target","functioniframe");
            $('form#avatarform').submit();
            
        });
        $('body').on('click','.techsupport', function()
        {
                if(xhr && xhr.readyState !== 4){
                    xhr.abort();
                }
            $(".mainview").scrollTop(0);
            PanelShow(20);
            $('#functioniframe').prop('src',rootserver+'blank.php');
            
            
            $('form#techsupportform').prop("target","functioniframe");
            $('form#techsupportform').submit();
            
        });
        $('body').on('click','.upgrade', function()
        {
                if(xhr && xhr.readyState !== 4){
                    xhr.abort();
                }
            $(".mainview").scrollTop(0);
            PanelShow(9);
            
            $('.popupwindow').load( rootserver+"accountcheck.php",  {
                'providerid': $('#pid').val()
            }, function(html, status){
            });
            
            
        });
        
        

        $('body').on('click','.notifyclear', function()
        {
            alertify.confirm('Clear Notifications',function(ok){
                if( ok ){
                
                    xhr = $.ajax({
                        url: rootserver+"notifyclear.php",
                        context: document.body,
                        type: 'POST',
                        data: 
                         { 
                         }
                    }).done(function(){
                        SideBarList(true);
                    });
                }
            });
        });

            
        
        $('body').on('click','#suspendsms', function()
        {
            if( $('#suspendsms').prop('checked') )
                $.post(rootserver+"suspend.php", { suspendsms: 'Y', providerid : $('#pid').val() });
            else
                $.post(rootserver+"suspend.php", { suspendsms: 'N', providerid : $('#pid').val() });
        });
        $('body').on('click','#suspendmail', function()
        {
            if( $('#suspendemail').prop('checked') )
                $.post(rootserver+"suspend.php", { suspendemail: 'Y', providerid : $('#pid').val() });
            else
                $.post(rootserver+"suspend.php", { suspendemail: 'N', providerid : $('#pid').val() });
        });
        
        $('body').on('click','.externaliframe', function()
        {
                $(".mainview").scrollTop(0);
                AbortAjax();
                LoadingShowMessage1();
                PanelShow(38);
                var src = $(this).data('src');
                
                $('form#externaliframe').prop('target','functioniframe');
                $('#functioniframe').prop('src',rootserver+'blank.php');
                $('form#externaliframe').prop('action',src);
                $('#externaliframe').submit();
        });
        
        
        $('body').on('click','.profilebutton', function()
        {
                $(".mainview").scrollTop(0);
                AbortAjax();
                LoadingShowMessage1();
                PanelShow(30);
                
                $('form#profile').prop('target','functioniframe');
                $('#functioniframe').prop('src',rootserver+'blank.php');
                $('#profile').submit();
        });
        $('body').on('click','.savebio', function()
        {
                $(".mainview").scrollTop(0);
                AbortAjax();
                var bio = $('.publicbio').val();
                var publish = 'N';
                if($('.publish').is(':checked')){
                    publish = 'Y';
                }
                PanelShow(8);
                $('#socialwindow').load( rootserver+"avatarchg.php",  {
                    'providerid': $('#pid').val(),
                    'mode' : 'S',
                    'bio' : bio,
                    'publish' : publish
                }, function(html, status){
                    $('#trigger_tilebutton').click();
                });
        });
        
        $('body').on('click','#imapsetupbuttonnew', function()
        {
                $('#imapsetupnew').submit();
        });
        $('body').on('click','#imapsetupbutton', function()
        {
                $('#functioniframe').prop('src',rootserver+'blank.php');
                $('#imapsetup').submit();
        });
        $('body').on('click','#imapsetupbutton1', function()
        {
                PanelShow(9);
                $('.popupwindow').load( rootserver+"about_email.php?"+timeStamp());
                //$('#showmessage1').prop('src','blank.php');
                //$('#imapsetup1').submit();
        });
        $('body').on('click','#imapsetuplaunch1', function()
        {
                PanelShow(8);
                $('#socialwindow').load( rootserver+"emailintro.php",  {
                    'providerid': $('#pid').val(),
                    'mode' : invitesource
                }, function(html, status){
                });
        });
        $('body').on('click','#imapsetuplaunch2', function()
        {
                PanelShow(9);
                $('#functioniframe').prop('src',rootserver+'blank.php');
                $('#imapsetup1').submit();
        });
        $('body').on('click','#imapsetupcancel', function()
        {
                PanelShow(8);
                $('#socialwindow').load( rootserver+"emailintro.php",  {
                    'providerid': $('#pid').val(),
                    'mode' : 'C'
                }, function(html, status){
                });
        });
        $('body').on('click','#staffbutton', function()
        {
                $('#functioniframe').prop('src',rootserver+'blank.php');
                //LoadingShowMessage1();
                PanelShow(20);
                $('#staff').submit();
        });
        $('body').on('click','.signup', function()
        {
                $('#functioniframe').prop('src',rootserver+'blank.php');
                //LoadingShowMessage1();
                PanelShow(20);
                $('#signupform').submit();
        });
        $('body').on('click','#chgpasswordbuttonnew', function()
        {
                $('#chgpasswordnew').submit();
        });
        $('body').on('click','.stafflist', function()
        {
                //$('#functioniframe').prop('src','blank.php');
                //$('form#stafflist').prop('target','functioniframe');
                PanelShow(20);

                $('#stafflist').submit();
        });
        /*
        $('body').on('click','#chgpasswordbutton', function()
        {
                //LoadingShowMessage1();
                $('#functioniframe').prop('src','blank.php');
                PanelShow(20);
               
                $('form#chgpassword').prop('target','functioniframe');
                //$('form.newemail').prop('action','newemail-frame.php');
                

                $('#chgpassword').submit();
        });
        */
        $('body').on('click','.chgtotp', function()
        {
                PanelShow(14);
                $('#socialwindow').load( rootserver+"authenticator.php");
        });
        $('body').on('click','.chgtotpvalidate', function()
        {
                var code = $('#chgtotpcode').val();
                var secret = $('#chgtotpsecret').text();
                
                $.ajax({
                    url: rootserver+"authenticate.php",
                    context: document.body,
                    type: 'POST',
                    data: 
                     { 
                         'code' : code,
                         'secret' : secret
                     }
                }).done(function( data, status ) {
                        var msg = jQuery.parseJSON(data);
                        if(msg.msg!==''){
                            alertify.alert(msg.msg);
                        }
                        if(msg.error=='N'){
                            $("#trigger_settings").trigger('click');
                        }
                });
                
        });
        $('body').on('click','.chgtotpdelete', function()
        {
            alertify.confirm('Delete Authenticator?',function(ok){
                if( ok ){
                
                    $.ajax({
                        url: rootserver+"authenticate.php",
                        context: document.body,
                        type: 'POST',
                        data: 
                         { 
                             'code' : '',
                             'secret' : 'delete'
                         }
                    }).done(function( data, status ) {
                            var msg = jQuery.parseJSON(data);
                            if(msg.msg!==''){
                                setTimeout(function(){
                                    alertify.alert(msg.msg);
                                },500);
                            }
                            
                            if(msg.error==='N'){
                                $("#trigger_settings").trigger('click');
                            }
                    });
                }   
            });
                
        });
        
        $('body').on('click','.chgpasswordbutton', function()
        {
            //LoadingShowMessage1();
            $('#functioniframe').prop('src',rootserver+'blank.php');
            PanelShow(36);

            $('form#chgpassword').prop('target','functioniframe');
            //$('form.newemail').prop('action','newemail-frame.php');


            $('#chgpassword').submit();
        });
        
        $('body').on('click','.restreambutton', function()
        {
            //LoadingShowMessage1();
            $('#functioniframe').prop('src',rootserver+'blank.php');
            PanelShow(30);

            $('form#restream').prop('target','functioniframe');
            //$('form.newemail').prop('action','newemail-frame.php');


            $('#restream').submit();
        });
        
        $('body').on('click','#addressbookbutton', function()
        {
                //LoadingShowMessage1();
                $('#functioniframe').prop('src',rootserver+'blank.php');
                PanelShow(20);
                $('#addressbookedit').submit();
                
        });
        $('body').on('click','.contactadd', function()
        {
            var providerid = $(this).data('providerid');
            alertify.confirm('Add to Contacts?',function(ok){
                if( ok ){
                
                    xhr = $.ajax({
                        url: rootserver+"contactadd.php",
                        context: document.body,
                        type: 'POST',
                        data: 
                         { 
                             'contactid' : providerid
                         }
                    }).done(function(){
                        //alertify.alert('Successfully Added');
                    });
                }
            });
        });
        
        
        $('body').on('click','#contactbookbutton', function()
        {
            //LoadingShowMessage1(true);
            $('#functioniframe').prop('src',rootserver+'blank.php');
            PanelShow(20);
            $(".mainview").scrollTop(0);
            $('#contactbook').submit();
        });
        $('body').on('click','#roommembersbutton', function()
        {
            //LoadingShowMessage1(true);
            $('#functioniframe').prop('src',rootserver+'blank.php');
            PanelShow(20);
            $(".mainview").scrollTop(0);
            $('#contactenterprise').submit();
        });
        $('body').on('click','#roomsmsmembersbutton', function()
        {
            //LoadingShowMessage1(true);
            $('#functioniframe').prop('src',rootserver+'blank.php');
            PanelShow(20);
            $(".mainview").scrollTop(0);
            $('#contactsms').submit();
        });
            
                
        
        
        $('body').on('click','#downloadhist', function()
        {
                $('.downloadhist').submit();
        });
        $('body').on('click','#phpinfo', function()
        {
                $('#phpinfoform').submit();
        });
        
        $('body').on('click','.securityguidebutton', function()
        {
                PanelShow(14);
                $('#socialwindow').load( rootserver+"securityguide.php");
        });
        $('body').on('click','.stats', function()
        {
            AbortAjax();
            $('.mainview').scrollTop(0);
            PanelShow(30);
            
            $('#functioniframe').prop('src',rootserver+'blank.php');
            $('#statsform').submit();
        });
        $('body').on('click','.statsplus', function()
        {
            AbortAjax();
            $('.mainview').scrollTop(0);
            $('.settingsview').scrollTop(0);
            PanelShow(30);
            
            $('#functioniframe').prop('src',rootserver+'blank.php');
            $('#superstatsform').submit();
        });
        $('body').on('click','.statsuser', function()
        {
            AbortAjax();
            PanelShow(20);
            ShowBanner();
            $('.settingsview').scrollTop(0);
            $('.mainview').scrollTop(0);
            
            $('#functioniframe').prop('src',rootserver+'blank.php');
            $('#userstatsform').submit();
        });
        $('body').on('click','.testzone', function()
        {
            
            AbortAjax();
            PanelShow(20);
            $('.settingsview').scrollTop(0);
            
            $('#functioniframe').prop('src',rootserver+'blank.php');
            $('#testzoneform').submit();
        });
        $('body').on('click','.testzone2', function()
        {
                PanelShow(14);
                $('#socialwindow').load( rootserver+"testzone.php");
        });
        
        $('body').on('click','.report1', function()
        {
            
            AbortAjax();
            PanelShow(30);
            $('.settingsview').scrollTop(0);
            
            $('#functioniframe').prop('src',rootserver+'blank.php');
            $('#report1form').submit();
        });
        $('body').on('click','.tokenreport', function()
        {
            
            AbortAjax();
            PanelShow(30);
            $('.settingsview').scrollTop(0);
            
            $('#functioniframe').prop('src',rootserver+'blank.php');
            $('#tokenreportform').submit();
        });
        $('body').on('click','.tokenstore', function()
        {
            
            AbortAjax();
            PanelShow(30);
            $('.settingsview').scrollTop(0);
            
            $('#functioniframe').prop('src',rootserver+'blank.php');
            $('#tokenstoreform').submit();
        });
        
        
                
        $('body').on('click','.leave', function()
        {
            PanelShow(-1); //Restore Last Panel
        });
        
        $('body').on('click','.ageconfirm', function()
        {
            AbortAjax();
            PanelShow(29);
            
            $('#functioniframe').prop('src',rootserver+'blank.php');
            $('#ageform').submit();
        });
        
        
        $('body').on('click','.credentialget', function()
        {
            //AbortAjax();
            var mode = $(this).data('mode');
            var formid = $(this).data('formid');
            
            
            $.ajax({
                url: rootserver+'credentialget.php',
                context: document.body,
                type: 'POST',
                data: 
                 { 'providerid': $('#pid').val(), 
                   'formid' : formid,
                   'mode' : mode
                 }
             }).done(function( data, status ) {
                PanelShow(9);
                $('#popupwindow').html(data);

            });
            
            
        });
        $('body').on('click','.credentialformlist', function()
        {
            //AbortAjax();
            var mode = $(this).data('mode');
            
            
            $.ajax({
                url: rootserver+'credentialformlist.php',
                context: document.body,
                type: 'POST',
                data: 
                 { 'providerid': $('#pid').val(), 
                   'mode' : mode
                 }
             }).done(function( data, status ) {
                PanelShow(9);
                $('#popupwindow').html(data);
                $('.mainview').scrollTop(0);

            });
            
            
        });
        
        $('body').on('click','#savecredentialbutton', function() 
        {
            var formid = $(this).data('formid');
            
            SubmitCredential(formid);
            //alert('Save Request');
        });
        $('body').on('change','#editformname', function() 
        {
            var formid = $(this).data('formid');
            var formname = $('#editformname').val();
            
                $('#roominnerwindow').load( rootserver+"credentialformsetup.php?"+timeStamp(),  {
                    'providerid': $('#pid').val(),
                    'mode': 'EF',
                    'formid': formid,
                    'formname' : formname
                }, function(html, status){
                });
            
        });
        $('body').on('change','#editformenterprise', function() 
        {
            var formid = $(this).data('formid');
            var enterprise = $('#editformenterprise').val();
            
                $('#roominnerwindow').load( rootserver+"credentialformsetup.php?"+timeStamp(),  {
                    'providerid': $('#pid').val(),
                    'mode': 'ENTERPRISE',
                    'formid': formid,
                    'enterprise' : enterprise
                }, function(html, status){
                });
            
        });
        
        $('body').on('change','.credentialtype', function() 
        {
            var value = $('.credentialtype').val();
            var length = $('.credentiallength').val();
            $('.credentselecthtml').prop("disabled",true);
            $('.credentiallength').prop("disabled",false);
            $('.eventname').prop("disabled",false);
            $('.eventname').val("");
            
            if(length ==='N/A'){
                $('.credentiallength').val('45');
            }
            
            if(value ==="Select"){
                $('.credentselecthtml').prop("disabled",false);
                $('.credentiallength').prop("disabled",true);
                $('.credentiallength').val("N/A");
            } 
            if(value ==="Comment" || value ==='Title'){
                $('.credentiallength').prop("disabled",true);
                $('.credentiallength').val("N/A");
                $('.eventname').prop("disabled",true);
                $('.eventname').val(value);
            }
        });
        
        
        $('body').on('change','.formdataentry', function() 
        {
            var value = $(this).val();
            var credentname = $(this).data("credentname");
            var credentid = $(this).data("credentid");
            var formid = $(this).data("formid");
            SaveCredential(formid, credentid, credentname, value)
            //alert( credentname+"-"+data);
        });
        $('body').on('input','.formsmsentry', function() 
        {
            var value = $(this).val();
            SaveSMS( value)
            //alert( credentname+"-"+data);
        });
        
        function SaveCredential(formid, credentid, credentname, value )
        {
            $('#status').load(rootserver+"credentialsave.php", { 
                'pid': $('#pid').val(),
                'formid': formid,
                'credentname':credentname,
                'credentid':credentid,
                'value' : value,
                'mode'  : 'S'
            }, function(data,status){
                if(data!==''){
                    alert(data);
                }
            });
        }
        function SubmitCredential(formid )
        {
            $('#status').load(rootserver+"credentialsave.php", { 
                'pid': $('#pid').val(),
                'formid' : formid,
                'mode'  : 'L'
            }, function(data){
                $('.mainview').scrollTop(0);
                alertify.alert(data);
            });
       }
        function SaveSMS( value )
        {
            //alertify.alert(value+" "+credentname+" "+roomid);
            $('#status').load(rootserver+"credentialsave.php", { 
                'pid': $('#pid').val(),
                'sms': value
            }, function(){
            });
       }
       
        $('body').on('click','.testlink', function()
        {
       
            $.ajax({
                url: rootserver+'cryptget.php',
                context: document.body,
                type: 'POST',
                data: 
                 {
                     encoding: 'SPA10'
                 }
            }).success(function(data){
                alertify.alert(data);
            });
            
            return;
        });

       
        
        
   /*****************************************************************************
    *
    *    
    *    
    *    *  NOTIFICATION
    *  
    * 
    * 
    *****************************************************************************/
        $('body').on('click','.notification', function()
        {
                if(!TermsOfUseCheck()){
                    return;
                }
                AbortAjax();
                var mode = $(this).data('mode');
                $(".notificationsview").show();
                $(".notificationspopup").hide();
                $(".tileview").hide();
                $(".mainview").hide();
                $(".settingsview").hide();
                $(".roomsview").hide();
                //PanelShow(26);
                $('.notificationsview').html(LoadingGIF);
                $('.notificationsview').load( rootserver+"notification.php", {
                    providerid : $('#pid').val(),
                    mode : mode
                });
        });
        $('body').on('click','.notificationdismiss', function()
        {
            $(".notificationspopup").toggle("scale");
        });
        $('body').on('click','.notificationpopup', function()
        {
            return;
                    if( $('.chatarea').visible(true)){
                    
                        return;
                    }
                    $.ajax({
                        url: rootserver+'lastfunc.php',
                        context: document.body,
                        type: 'POST',
                        data: 
                         { 'providerid': $('#pid').val(), 
                           'mode' : 'N'
                         }
                     }).done(function( data, status ) {
                        var msg = jQuery.parseJSON(data);
                        
                        if( msg.notification !==''){
                        
                            $('.notificationspopup').html(LoadingGIF);
                            $(".notificationspopup").show();
                            $(".notificationsview").hide();
                            //$(".tileview").hide();
                            //$(".mainview").hide();
                            //$(".settingsview").hide();
                            //$(".roomsview").hide();
                            $('.notificationspopup').html(msg.notification );
                            if(msg.soundalert==='1'){
                            
                                setTimeout(function(){
                                    $(".notificationspopup").hide();
                                }, 30000);
                                PlaySound(true);
                            } else {
                            
                                setTimeout(function(){
                                    $(".notificationspopup").hide();
                                }, 10000);
                            }
                            
                        } else {
                        
                            localStorage.mobileNotified = '';
                        }
                    });
                
                
        });
        

        $('body').on('click','.showdialog', function(){
            var title = $(this).data('title'); 
            var text = $(this).data('text'); 
            $('#dialog').html(text);
            $('#dialog').attr('title',title);
            $('#dialog').dialog();
        });

   /*****************************************************************************
    *
    *    
    *    
    *    *  SHARES
    *  
    * 
    * 
    *****************************************************************************/

        $('body').on('click','.openshare', function()
        {
            PanelShow(7);
        });
        $('body').on('click','.backtoshares', function()
        {
            PanelShow(8);
        });
        $('body').on('click','.manageshares', function()
        {
            if( xhr && xhr.readyState !== 4){
                xhr.abort();
            }
            var page = $(this).data('page');
            var datestart = $(this).data('datestart');
            var dateend = $(this).data('dateend');
            var mode = $(this).data('mode');
            var sort = $(this).data('sort');
            var filename = $(this).data('deletefilename');
            active = '';
            if( $('#activeshares').prop("checked") )
                active = 'Y';
            
            if( mode === 'D'){
            
                alertify.confirm('Kill access to share?',function(ok){
                    if( ok ){
                    
                        PanelShow(8);
                        $('#socialwindow').load( rootserver+"sharemanage.php",  {
                            'providerid': $('#pid').val(),
                            'page' : page,
                            'datestart' : datestart,
                            'dateend' : dateend,
                            'mode' : mode,
                            'sort' : sort,
                            'active' : active,
                            'filename' : filename
                        }, function(html, status){
                        });
                    }
                });
            }
            else {
            
                PanelShow(8);
                $('#socialwindow').load( rootserver+"sharemanage.php?"+timeStamp(),  {
                    'providerid': $('#pid').val(),
                    'page' : page,
                    'datestart' : datestart,
                    'dateend' : dateend,
                    'mode' : mode,
                    'sort' : sort,
                    'active' : active,
                    'filename' : filename,
                   'timestamp' : timeStamp()
                }, function(html, status){
                });
            }
        });
        
        
   /*****************************************************************************
    *
    *    
    *    
    *    *  STATUS POST
    *  
    * 
    * 
    *****************************************************************************/
        
        
        $(document).on( 'mouseenter',".commentline", function() 
        { 
            $(this).children('.action').show();
        });
        $(document).on( 'mouseleave',".commentline", function() 
        { 
            $(this).children('.action').hide();
        });
        
        
        
   /*****************************************************************************
    *
    *    
    *    
    *    *  CONVERSATIONS /ROOMS
    *  
    * 
    * 
    *****************************************************************************/
        
        $('body').on('click','.showmore', function()
        {
            $('.commentlong').hide();
            $('.commentshort').show();
            $('.showmore').show();
            
            if($(this).parent().children('.commentshort').is(":visible")){
            
                $(this).parent().children('.commentlong').show();
                $(this).parent().children('.commentshort').hide();
                $(this).hide();
            }
        });
        
        
        $('body').on('click','.scrolltotop',function()
        {
            $('.mainview').scrollTop(0);
        });
        $('body').on('click','.scrolltoanchor',function()
        {
            scrollToAnchor( $(this).data('anchor'));
        });
        $(document).on( 'click',".openstatuslink", function(e) 
        { 
            $('.statuslink').show();
            $('.statusvideo').hide();
            $('.statusphotolink').hide();
            $('.statusphoto').hide();
            $('.statusfile').hide();
        });
        $(document).on( 'click',".openstatusvideo", function(e) 
        { 
            $('.statuslink').hide();
            $('.statusvideo').show();
            $('.statusphotolink').hide();
            $('.statusphoto').hide();
            $('.statusfile').hide();
        });
        $(document).on( 'click',".openstatusphoto", function(e) 
        { 
            $('.statuslink').hide();
            $('.statusvideo').hide();
            $('.statusphotolink').hide();
            $('.statusphoto').show();
            $('.statusfile').hide();
        });
        $(document).on( 'click',".openstatusfile", function(e) 
        { 
            $('.statuslink').hide();
            $('.statusvideo').hide();
            $('.statusphotolink').hide();
            $('.statusphoto').hide();
            $('.statusfile').show();
        });
        $(document).on( 'click',".openstatusphotolink", function(e) 
        { 
            $('.statuslink').hide();
            $('.statusvideo').hide();
            $('.statusphoto').hide();
            $('.statusphotolink').show();
            $('.statusfile').hide();
        });
        $(document).on( 'click',".openreplylink", function(e) 
        { 
            $('.replylinkspan').show();
            $('.replyvideospan').hide();
            $('.replyphotospan').hide();
            $('.replyphotolinkspan').hide();
            $('.replyfilespan').hide();
        });
        $(document).on( 'click',".openreplyvideo", function(e) 
        { 
            $('.replylinkspan').hide();
            $('.replyvideospan').show();
            $('.replyphotospan').hide();
            $('.replyphotolinkspan').hide();
            $('.replyfilespan').hide();
        });
        $(document).on( 'click',".openreplyfile", function(e) 
        { 
            $('.replylinkspan').hide();
            $('.replyvideospan').hide();
            $('.replyphotospan').hide();
            $('.replyphotolinkspan').hide();
            $('.replyfilespan').show();
        });
        $(document).on( 'click',".openreplyphoto", function(e) 
        { 
            $('.replylinkspan').hide();
            $('.replyvideospan').hide();
            $('.replyphotospan').show();
            $('.replyphotolinkspan').hide();
            $('.replyfilespan').hide();
        });
        $(document).on( 'click',".openreplyphotolink", function(e) 
        { 
            $('.replylinkspan').hide();
            $('.replyvideospan').hide();
            $('.replyphotospan').hide();
            $('.replyphotolinkspan').show();
            $('.replyfilespan').hide();
        });
        $(document).on( 'click',".openchatphoto", function(e) 
        { 
            if($('.chatphoto').is(":visible")){
            
                $('.chatphoto').hide();
            }  else {
            
                $('.chatphoto').show();
            }
   
                
        });
        $('body').on('click','.friendsearch', function()
        {
                AbortAjax();
                var filter = $('.friendsearchfilter').val();
                var roomid = $(this).data('roomid');
                var groupid = $(this).data('groupid');
                var sponsor = $(this).data('sponsor');
                var caller = $(this).data('caller');
                
                $.ajax({
                    url: rootserver+'friendsearch.php?'+timeStamp(),
                    context: document.body,
                    type: 'POST',
                    data: 
                     { 
                    'providerid': $('#pid').val(),
                    'roomid': roomid,
                    'filter': filter,
                    'caller': caller,
                    'groupid' :groupid,
                    'sponsor' :sponsor,
                    'timestamp' : timeStamp()
                     }

                }).done(function( data, status ) {
                    //if( data !=='')
                    //{
                        $('.friendsource').html(data);
                    //}
                });
        });
        
        $('body').on('click','.roommanage', function()
        {
                AbortAjax();
                PanelShow(4);
                $(".mainview").scrollTop(0);
                $('#roominnerwindow').html($('.roommanagediv').html());
        });
        
        
        $('body').on('click','.friends', function()
        {
                AbortAjax();
                PanelShow(4);
                var room = $('#createroom').val();
                var filter = $('#roommanagefilter').val();
                var friendproviderid = $(this).data('providerid');
                var roomid = $(this).data('roomid');
                var mode = $(this).data('mode');
                var caller = $(this).data('caller');
                if( mode === 'A' || mode === 'P' || mode ==='D'){
                     room = $(this).data('room');
                 }
                $('#roominnerwindow').html(LoadingGIF);
                $('#roominnerwindow').hide().load( rootserver+"friends.php",  {
                    'providerid': $('#pid').val(),
                    'friendproviderid': friendproviderid,
                    'room': room,
                    'roomid': roomid,
                    'mode': mode,
                    'caller': caller,
                    'filter': filter
                }, function(html, status){
                        $(".mainview").scrollTop(0);
                }).fadeIn(800);
            
        });
        $(document).on( 'click',".roomcontrolbutton", function() 
        { 
            $(this).parent().find('.roomcontrols').show();
            $(this).hide();
        });
        
        
        $(document).on( 'click',".hidecomment", function() 
        { 
            $('.makeaction').hide();
            $('.noaction').show();
            $('.replycomment').val("");
            blur();
            
        });
        $(document).on( 'click',".makecommenttop", function() 
        { 
            MakeCommentTop( $(this) );
        });
        
        $(document).on( 'click',".makecommenttop", function() 
        { 
            MakeCommentTop( $(this) );

        });
        function MakeCommentTop(this1)
        {
            $('#roomstatusmode').val( "P");
            $('#roompostreply').hide();
            $('#roompostcomment').show();
            $('#roomstatusheading').html( "<b>Start New Topic</b>");
            $('#roomstatustitle').show();
            lastcommentid = null;
            lastcommentheaderid = null;

            if(MobileCapable){
                $('#makeaction').show();
                $('.roomcontent').hide();
            }
            //original
            if(MobileCapable && $('#roomstatuscomment').is(":visible")){
            
               $('#roomstatuscomment').focus();
                setTimeout(function(){
                    $('#roomstatuscomment').focus();
                },
                500);
            }
            else {
            
                this1.parents('table.makecommentowner').find('.noaction').hide();
                this1.parents('table.makecommentowner').find('.makeaction').show();
                this1.parents('table.makecommentowner').find('.noaction').hide();
                $('#statuscomment').focus();
            }
            return;
            
        }
        $(document).on( 'click',".makecomment", function() 
        { 
            $('#roomstatusmode').val( "R");
            $('#roompostreply').show();
            $('#roompostcomment').hide();
            $('#roomstatustitle').hide();
            
            
            $('#roomstatusshareid').val( $(this).data('shareid'));
            $('#roomstatusreference').val( $(this).data('reference'));
            $('#roomstatusheading').html( "<b>Topic Reply</b>");
            lastcommentid = $(this).closest('.roomcommentsarea').find('.roomcomment');
            lastcommentheaderid = $(this).closest('.roomcommentsarea').find('.roomcommentheader');

            if(MobileCapable){
                $('#makeaction').show();
                $('.roomcontent').hide();
            }
            //original
            if(MobileCapable && $('#roomstatuscomment').is(":visible"))
            {
               $('#roomstatuscomment').focus();
                setTimeout(function(){
                    $('#roomstatuscomment').focus();
                },
                500);
            }
            else
            {
                $(this).parent().find('.makeaction').show();
                $(this).parent().find('.makecomment').hide();
                $(this).parent().find('.makeaction').find('.replycomment').first().focus();
                
            }
            return;


        });
        $('body').on('click','.roomshareoptions', function()
        {
            if($('.shareoptions').is(":visible")){
                $('.shareoptions').hide();
                
            }
            else {
                $('.shareoptions').show();
            }
            //scrollToAnchor( 'shareoptions');
            
        });
        $('body').on('click','.roomevents', function()
        {
                var mode = $(this).data('mode');
                var roomid = $(this).data('roomid');
                if( roomid === 'All'){
                    return;
                }
                var eventid = $(this).data('eventid');
                var eventdate = $('.eventdate').val();
                var eventtime = $('.eventtime').val();
                var eventdesc = $('.eventdesc').val();
                var eventname = $('.eventname').val();
                
                //eventdate = moment(eventdate,"YYYY-MM-DD");
                
                if( mode == 'S' || mode == 'E'){
                    //var regexp = /([01][0-9]|[02][0-3]):[0-5][0-9][ ][A|P][M]/;
                    //if( !eventtime.match(regexp))
                    //{
                    //    alertify.alert('Please correct the Time Format to hh:mm AM')
                    //    return;
                    //}
                }

                AbortAjax();
                PanelShow(4);
                $('#roominnerwindow').html(LoadingGIF);
                $('#roominnerwindow').load( rootserver+"roomevents.php?"+timeStamp(),  {
                    'providerid': $('#pid').val(),
                    'mode': mode,
                    'roomid': roomid,
                    'eventid' :eventid,
                    'eventname' :eventname,
                    'eventdesc' :eventdesc,
                    'eventtime' :eventtime,
                    'eventdate' :eventdate
                }, function(html, status){
                    $(".mainview").scrollTop(0);
                });
            
            
        });
        
        $('body').on('click','.roomtasks', function()
        {
                var mode = $(this).data('mode');
                var roomid = $(this).data('roomid');
                if( roomid === 'All'){
                    return;
                }
                var eventid = $(this).data('eventid');
                var eventdate = $('.eventdate').val();
                var eventtime = $('.eventtime').val();
                var eventdesc = $('.eventdesc').val();
                var eventname = $('.eventname').val();
                var eventassign = $('.eventassign').val();
                var eventpriority = $('.eventpriority').val();
                var sort = $(this).data('sort');
                var page = $(this).data('page');
                
                AbortAjax();
                PanelShow(4);
                $('#roominnerwindow').html(LoadingGIF);
                $('#roominnerwindow').load( rootserver+"roomtasks.php?"+timeStamp(),  {
                    'providerid': $('#pid').val(),
                    'mode': mode,
                    'roomid': roomid,
                    'eventid' :eventid,
                    'eventname' :eventname,
                    'eventdesc' :eventdesc,
                    'eventtime' :eventtime,
                    'eventassign' :eventassign,
                    'priority' :eventpriority,
                    'eventdate' :eventdate,
                    'sort' : sort,
                    'page' : page,
                    'browser' : Browser
                }, function(html, status){
                    $(".mainview").scrollTop(0);
                    if(Browser==='firefox')
                    {
                        $( ".eventdate" ).appendDtpicker(
                                {"dateOnly": true, "inline": true});
                        //$( ".eventdate" ).datepicker();
                        //$( ".eventdate" ).datepicker("option", "dateFormat", "mm/dd/yy");
                    }
                    
                });
            
            
        });
        $('body').on('click','.credentialformsetup', function()
        {
                var mode = $(this).data('mode');
                var formid = $(this).data('formid');
                if( formid === ''){
                    return;
                }
                var roomid = $(this).data('roomid');
                
                if(mode==='PRINTFORM'){
                    $('#printout').print();
                    return;
                }
                
                if(mode==='DF'){
                    
                    alertify.confirm('Delete Form?',function(ok){
                        if( ok ) {
                            AbortAjax();
                            PanelShow(4);

                            $('#roominnerwindow').html(LoadingGIF);
                            $('#roominnerwindow').load( rootserver+"credentialformsetup.php?"+timeStamp(),  {
                                'providerid': $('#pid').val(),
                                'mode': mode,
                                'formid': formid
                            }, function(html, status){
                                $(".mainview").scrollTop(0);

                            });
                        }
                    });
                    return;
                }
                if(mode==='PUBLIC'){
                    
                    AbortAjax();
                    PanelShow(4);

                    $('#roominnerwindow').html(LoadingGIF);
                    $('#roominnerwindow').load( rootserver+"credentialformsetup.php?"+timeStamp(),  {
                        'providerid': $('#pid').val(),
                        'mode': mode,
                        'formid': formid
                    }, function(html, status){
                        $(".mainview").scrollTop(0);

                    });
                    return;
                }
                
                var formname = '';
                if(mode == 'N'){
                    formname = $('#newformname').val();
                    
                }
                var eventid = $(this).data('eventid');
                var clientid = $(this).data('clientid');
                var eventdesc = $('.eventdesc').val();
                var eventname = $('.eventname').val();
                var selecthtml = $('.credentselecthtml').val();
                var validation = $('.credentvalidation').val();
                //alertify.alert(selecthtml);
                var seq = $('.eventpriority').val();
                var credentiallength = $('.credentiallength').val();
                var credentialtype = $('.credentialtype').val();
                var page = $(this).data('page');

                if(mode === 'X'){
                    var href = rootserver+"credentialformsetup.php?mode=X&roomid="+roomid;
                    window.location.replace( href );
                    return;
                }
                if(mode === 'EXPORTFORM'){
                    alertify.confirm('Export Form Data?',function(ok){
                        if( ok ) {
                    
                            var href = rootserver+"credentialformsetup.php?mode=EXPORTFORM";
                            window.location.replace( href );
                            return;
                        }
                    });    
                    return;
                }
                
                AbortAjax();
                PanelShow(4);
                
                $('#roominnerwindow').html(LoadingGIF);
                
                $.ajax({
                    url: rootserver+'credentialformsetup.php',
                    context: document.body,
                    type: 'POST',
                    data: 
                    { 
                        'providerid': $('#pid').val(),
                        'mode': mode,
                        'formid': formid,
                        'roomid': roomid,
                        'formname' : formname,
                        'eventid' :eventid,
                        'eventname' :eventname,
                        'eventdesc' :eventdesc,
                        'priority' :seq,
                        'credentiallength' : credentiallength,
                        'credentialtype' : credentialtype,
                        'selecthtml' : selecthtml,
                        'validation' : validation,
                        'clientid' : clientid,
                        'page' : page
                    }
                }).done(function( data, status ) {
                    if(data!==''){
                        $('#roominnerwindow').html(data);
                        $(".mainview").scrollTop(0);
                    }
                });
            
            
        });        
        
        $('body').on('click','.roomweb', function()
        {
                var mode = $(this).data('mode');
                var roomid = $(this).data('roomid');
                if( roomid === 'All'){
                    return;
                }
                var backgroundcolor = $('.webstyle_backgroundcolor').val();
                var color = $('.webstyle_color').val();
                var trimcolor = $('.webstyle_trimcolor').val();
                var title = $('.webstyle_title').val();
                var subtitle = $('.webstyle_subtitle').val();
                var subtitle2 = $('.webstyle_subtitle2').val();
                var footer = $('.webstyle_footer').val();
                var analytics = $('.webstyle_analytics').val();
                AbortAjax();
                PanelShow(4);
                $('#roominnerwindow').html(LoadingGIF);
                $('#roominnerwindow').load( rootserver+"roomweb.php?"+timeStamp(),  {
                    'providerid': $('#pid').val(),
                    'mode': mode,
                    'roomid': roomid,
                    'backgroundcolor':backgroundcolor,
                    'color':color,
                    'trimcolor':trimcolor,
                    'title': title,
                    'subtitle': subtitle,
                    'subtitle2': subtitle2,
                    'footer': footer,
                    'analytics': analytics
                }, function(html, status){
                    $(".mainview").scrollTop(0);
                });
            
            
        });        
        
        $('body').on('click','.roomfeedflag', function() 
        {
            var mode = 'ROOMFEEDON';
            if($('.roomfeedflag').prop("checked")){
                mode = 'ROOMFEEDOFF';
            }
            $.ajax({
                url: rootserver+'accountsetting.php',
                context: document.body,
                type: 'POST',
                data: 
                 { 'providerid': $('#pid').val(), 
                   'mode' : mode
                 }
             }).done(function( data, status ) {
                    $('#trigger_roomselect').trigger('click');
            });
        });
        
        
        $('body').on('click','.feedload', function()
        {
                if(!TermsOfUseCheck()){
                    return;
                }
                roomid = $(this).data('roomid');
                var caller = $(this).data('caller');
                defaultRoomid = roomid;
                selectedroomid = "";
                
                LastFunc = 'R';
                AbortAjax();
                ShowBanner();
                PanelShow(4);
                

                RoomProc( 
                    "", "", "", "", "", "", "", 
                    defaultRoomid, selectedroomid, "", "", "", "", 'Y', 
                    null, null, "", "", "", "", caller,'' );
            
                
            
        });        
        
        $('body').on('click','.feed', function()
        {
                if(!TermsOfUseCheck()){
                    return;
                }
                AbortAjax();
                ShowBanner();
                PanelShow(4);
                
                var video = "";
                var roomfiltername = $('.roomfiltername').val();
                var roomfilterdate = $('.roomfilterdate').val();

                $('#roomstatuspostid').val( $(this).data('postid'));
                var readonly = $(this).data('readonly');
                
                var anonymous = 'N';
                if( $('#roomstatusanonymous').is(":checked")) {
                    anonymous = $('#roomstatusanonymous').val();
                };
                if( $('#roomstatusalias').is(":checked")) {
                    anonymous = $('#roomstatusalias').val();
                };
                
                if(typeof $(this).data('mobile')==='undefined' ||
                        $(this).data('mobile')!=='Y'){
                    $('#roomstatusmode').val( $(this).data('mode'));
                    
                    anonymous = 'N';
                    if( $('#statusanonymous').is(":checked")) {
                        anonymous = $('#statusanonymous').val();
                    };
                    if( $('#statusalias').is(":checked")) {
                        anonymous = $('#statusalias').val();
                    };
                }
                var mode;
                mode = $(this).data('mode');
                if(mode==='' || typeof mode === "undefined" ){
                    mode = $('#roomstatusmode').val();
                }
                
                var profile;
                profile = $(this).data('profile');
                if(profile==='' || typeof mode === "undefined" ){
                    LastFunc = 'R';
                } else {
                    LastFunc = 'U';
                }
                 
                 //$('#roomstatusshareid').val( $(this).data('shareid'));
                 
                
                var shareid = $('#roomstatusshareid').val();
                var postid = $('#roomstatuspostid').val();
                var reference = $(this).data('reference');
                
                if( mode!=='P')
                {
                    shareid = $(this).data('shareid');
                    postid = $(this).data('postid');
                    
                }
                
                var page = $('#roomstatuspage').val();
                if($(this).data('mode')==='+' || $(this).data('mode')==='-'){
                    page = $(this).data('page');
                    mode = '';
                }
                var roomid = $('#roomstatusroomid').val();
                var selectedroomid = $('#roomstatusselectedroomid').val();
                
                roomid = $(this).data('roomid');
                var selectedroomid = $(this).data('selectedroomid');
                if( typeof roomid === "undefined" ){
                
                    defaultRoomid = "All";
                    roomid = "All";
                }
                if( typeof selectedroomid === "undefined" ){
                
                    selectedroomid = "All";
                }
                
                if( roomid!=='' ){
                
                    defaultRoomid = roomid;
                }
                if( defaultRoomid === 0 ){
                    $('#trigger_roomselect').trigger('click');
                    return;
                }
                
                if($('#roomstatustitle').val()===''){
                    $('#roomstatustitle').val( $('#statustitle').val());
                }
                if($('#roomstatuscomment').val()===''){
                    $('#roomstatuscomment').val( $('#statuscomment').val());
                }
                if($('#roomstatusphoto').val()===''){
                    $('#roomstatusphoto').val( $('#statusphoto').val());
                }
                if($('#roomstatusfile').val()===''){
                    $('#roomstatusfile').val( $('#statusfile').val());
                }
                var find = $('#roomsearch').val();
                if( typeof find === "undefined"){
                    find = '';
                }
                
                var title = $('#roomstatustitle').val();
                var comment= $('#roomstatuscomment').val();
                if( mode === 'P' && title === '' && comment!==''){
                    alertify.alert('Please add a title to your post');
                    return;
                    
                }
                
                var filelink = $('#roomstatusfile').val();
                var photo = $('#roomstatusphoto').val();
                var room = $('#roomstatusroom').val();
                var caller = $(this).data('caller');
                $('#roomstatustitle').val( "");
                $('#roomstatuscomment').val( "");
                $('#roomstatusphoto').val( "");
                $('#roomstatusfile').val( "");
                $('#roomstatusanonymous').val( "");
                $('#roomstatusalias').val( "");
                $('#roomstatusroom').val( "");
                $('#roomstatusalias').prop("checked",false);
                $('#roomstatusanonymous').prop("checked",false);
                
                $('#statustitle').val( "");
                $('#statuscomment').val( "");
                $('#statusphoto').val( "");
                $('#statusfile').val( "");
                $('#statusroom').val( "");
                $('#statusalias').prop("checked",false);
                $('#statusanonymous').prop("checked",false);
                
                
                
                if( mode === 'D') {
                    
                    alertify.confirm('Delete post?',function(ok){
                        if( ok ) {
                            RoomProc( 
                                title, comment, filelink, shareid, photo, video, room, 
                                defaultRoomid, selectedroomid, postid, page, mode, anonymous, 'Y', null, null, "","", reference, null,caller,'' );
                        }
                    });
                    return;
                }
                if( mode === 'B') {
                    
                    alertify.confirm('Bump post up?',function(ok){
                        if( ok ) {
                            RoomProc( 
                                title, comment, filelink, shareid, photo, video, room, 
                                defaultRoomid, selectedroomid, postid, page, mode, anonymous, 'Y', null, null, '','',reference, readonly, null,caller,'' );
                        }
                    });
                    return;
                }
                if( mode === 'FLAG') {
                    
                    alertify.confirm('Report content as objectionable?',function(ok){
                        if( ok ) {
                            RoomProc( 
                                title, comment, filelink, shareid, photo, video, room, 
                                defaultRoomid, selectedroomid, postid, page, mode, anonymous, 'Y', null, null,'','',reference, null,caller,'' );
                        }
                    });
                    return;
                }
                if( mode === 'PIN') {
                    
                    alertify.confirm('Pin post to top?',function(ok){
                        if( ok ) {
                            RoomProc( 
                                title, comment, filelink, shareid, photo, video, room, 
                                defaultRoomid, selectedroomid, postid, page, mode, anonymous, 'Y', null, null,'','',reference, null,caller,'' );
                        }
                    });
                    return;
                }
                

                RoomProc( 
                    title, comment, filelink, shareid, photo, video, room, 
                    defaultRoomid, selectedroomid, postid, page, mode, anonymous, 'Y', 
                    null, null, roomfiltername, roomfilterdate, reference, readonly, caller, find );
                
            
        });

        $('body').on('click','.feedreply', function()
        {
            
                $('#roomstatuspostid').val( $(this).data('postid'));
                var anonymous = 'N';
                if( $('#roomstatusanonymous').is(":checked")) {
                    anonymous = $('#roomstatusanonymous').val();
                };
                if( $('#roomstatusalias').is(":checked")) {
                    anonymous = $('#roomstatusalias').val();
                };
                
                
                if(typeof $(this).data('mobile')==='undefined' ||
                        $(this).data('mobile')!=='Y'){
                    $('#roomstatusmode').val( $(this).data('mode'));
                    anonymous = 'N';
                    if( $(this).parent(".makeaction").find(".replyanonymous").is(":checked")) {
                        anonymous = $(this).parent(".makeaction").find(".replyanonymous").val();
                    };
                    if( $(this).parent(".makeaction").find(".replyalias").is(":checked")) {
                        anonymous = $(this).parent(".makeaction").find(".replyalias").val();
                    };
                }
                var mode = $('#roomstatusmode').val();
                //$('#roomstatusshareid').val( $(this).data('shareid'));
                
                var shareid = $('#roomstatusshareid').val();
                var postid = $('#roomstatuspostid').val();
                var reference = $(this).data('reference');
                if( typeof reference === 'undefined'){
                    reference = $('#roomstatusreference').val();
                }
                if( mode!=='R'){
                
                    shareid = $(this).data('shareid');
                    postid = $(this).data('postid');
                    lastcommentid = null;
                    lastcommentheaderid = null;
                    
                }
                
                var page = $('#roomstatuspage').val();
                
                var roomid = $('#roomstatusroomid').val();
                var selectedroomid = $('#roomstatusselectedroomid').val();
                
                roomid = $(this).data('roomid');
                
                $('#roomstatustitle').val('');
                if($('#roomstatuscomment').val()===''){
                    $('#roomstatuscomment').val( $(this).parent(".makeaction").find(".replycomment").val() );
                }
                if($('#roomstatusphoto').val()===''){
                    $('#roomstatusphoto').val( $(this).parent(".makeaction").find(".replyphoto").val() );
                }
                if($('#roomstatusfile').val()===''){
                    $('#roomstatusfile').val( $(this).parent(".makeaction").find(".replyfile").val());
                }
                
            
                var title = $('#roomstatustitle').val();
                var comment= $('#roomstatuscomment').val();
                var filelink = $('#roomstatusfile').val();
                var photo = $('#roomstatusphoto').val();
                var room = $('#roomstatusroom').val();
                $('#roomstatustitle').val( "");
                $('#roomstatuscomment').val( "");
                $('#roomstatusphoto').val( "");
                $('#roomstatusfile').val( "");
                $('#roomstatusanonymous').val( "");
                $('#roomstatusalias').val( "");
                $('#roomstatusroom').val( "");
                $('#roomstatusalias').prop("checked",false);
                $('#roomstatusanonymous').prop("checked",false);
                
                $(this).parent(".makeaction").find(".replycomment").val("");
                $(this).parent(".makeaction").find(".replyfile").val("");
                $(this).parent(".makeaction").find(".replyphoto").val("");
                            
                $(this).parent(".makeaction").find(".replyanonymous").prop("checked", false);
                $(this).parent(".makeaction").find(".replyalias").prop("checked", false);
                var commentid;
                var commentheaderid;
            
            
                if(typeof $(this).data('mobile')==='undefined' || $(this).data('mobile')===''){
                    
                    if( lastcommentid === null){
                        commentid = $(this).closest('.roomcommentsarea').find('.roomcomment');
                        commentheaderid = $(this).closest('.roomcommentsarea').find('.roomcommentheader');
                        $(this).closest('.roomcommentsarea').find('.roomcommenthideheader').hide();
                    } else {
                        commentid = lastcommentid;
                        commentheaderid = lastcommentheaderid;
                        
                    }
                }
                else {
                
                    if( commentid !== null){
                        commentid = lastcommentid;
                        commentheaderid = lastcommentheaderid;
                    }
                    $('#makeaction').hide();
                    $('.roomcontent').show();
                    
                }
                $('.roomcommenthideheader').hide();
                if( commentid !== null){
                    commentheaderid.hide();
                    commentid.show();
                }

                PanelShow(4);
                var page = '';
                
                if( mode === 'D') {

                    alertify.confirm('Delete post?',function(ok){
                        if( ok ) {
                            RoomProc( 
                                title, comment, filelink, shareid, photo, video, room, 
                                roomid, roomid, postid, page, mode, anonymous, 'N', commentid, commentheaderid, '',"",reference, null, '', '' );
                        }
                    });
                    return;
                }

                RoomProc( 
                    title, comment, filelink, shareid, photo, video, room, 
                    roomid, roomid, postid, page, mode, anonymous, 'N', commentid, commentheaderid,"","",reference, null, '', '');

        });
        function RoomProc( 
                title, comment, filelink, shareid, photo, video, room, 
                roomid, selectedroomid, postid, page, mode, anonymous, parent, commentid, commentheaderid, 
                roomfiltername, roomfilterdate, reference, readonly, caller, find )
        {
                if(parent ==='Y'){
                    $('#roominnerwindow').html(LoadingGIF);
                }
                
                lastcommentheaderid  = null;
                lastcommentid = null;
                
            
                if( typeof roomid === 'undefined' || roomid === "0" || roomid === "All" || roomid === ''){
                    AbortAjax();
                    PanelShow(4);
                
                    $('#roominnerwindow').html(LoadingGIF);
                    $.ajax({
                        url: rootserver+'roomselect.php',
                        context: document.body,
                        type: 'POST',
                        data: 
                         { 'providerid': $('#pid').val(), 
                            'room': room,
                            'roomid' :roomid
                         }

                    }).done(function( data, status ) {

                        $('#roominnerwindow').hide().html(data).fadeIn(800);
                        $("#mainview").scrollTop(0);
                        SideBarList(true);

                    });
                    return;
                }
            
            
                AbortAjax();
                $.ajax({
                    url: rootserver+'room.php',
                    context: document.body,
                    type: 'POST',
                    data: 
                     { 
                    'providerid': $('#pid').val(),
                    'comment': comment,
                    'link' : filelink,
                    'title': title,
                    'shareid': shareid,
                    'photo': photo,
                    'video': video,
                    'room': room,
                    'roomid': roomid,
                    'selectedroomid': selectedroomid,
                    'postid': postid,
                    'mode': mode,
                    'parent': parent,
                    'page': page,
                    'anonymous': anonymous,
                    'readonly' : readonly,
                    'filtername' : roomfiltername,
                    'filterdate' : roomfilterdate,
                    'iscore' : localStorage.iscore,
                    'caller' : caller,
                    'find' : find
                     }

                }).done(function( data, status ) {
                    if(status!=='success'){
                        alertify.alert('Room connection failed. Check your network connection and retry.');
                        //$('.mainview').scrollTop(0);
                        return;
                    }
                   
                    if( parent === 'Y'){
                        SwipeFromRoom();
                    
                        $('#roominnerwindow').html(LoadingGIF);
                        if( data !==''){
                            if(mode ==='P' || mode ==='L' || mode ==='R'){
                                GiveStars();
                            }
                        
                            $('#roominnerwindow').hide().html(data).fadeIn(800);
                            if( typeof reference !=='undefined' && reference!==''){
                            
                                scrollToAnchor( reference.replace('.',''));
                            }
                            else
                            if( typeof anchor !=='undefined' && anchor!==''){
                            
                                scrollToAnchor( anchor);
                            }
                        }
                    } else {
                        var limit = '';
                        if( mode === 'R'){
                            limit = 5;
                        }
                        
                        $('.makeaction').hide();
                        $('.makecomment').show();
                        $(commentid).html(LoadingGIF);
                        AbortAjax();
                        $.ajax({
                            url: rootserver+'room_sub.php',
                            context: document.body,
                            type: 'POST',
                            data: 
                             { 'providerid': $('#pid').val(), 
                               'shareid' : shareid,
                               'scrollreference' : reference.replace('.',''),
                               'limit' : limit
                             }

                        }).done(function( data, status ) {
                            //Perform a Comment Count
                            $(commentid).html(data);
                            AbortAjax();
                            $.ajax({
                                url: rootserver+'room.php',
                                context: document.body,
                                type: 'POST',
                                data: 
                                 { 'providerid': $('#pid').val(), 
                                   'shareid' : shareid,
                                   'mode': 'C'
                                 }

                            }).done(function( data, status ) {
                                $(commentheaderid).show();
                                $(commentheaderid).hide().html(data).fadeIn(800);
                                ShowBanner();
                                if(mode ==='P' || mode ==='L' || mode ==='R'){
                                    GiveStars();
                                }
                                //scrollToAnchor( reference.replace('.',''));
                            });

                        });
                        

                    }
                    
                    
                });
        }               
        $('body').on('click','.feededitpost', function()
        {
            var roomid = $(this).data('roomid');
            var postid = $(this).data('postid');
            var page = $(this).data('page');
            var cleanpostid = $(this).data('postidclean');
            //alertify.alert(postid);
            var comment = $('#roomedit-'+cleanpostid).val();
            $('#roomedit-'+cleanpostid).first().remove();
            
            //alertify.alert(roomid+" "+postid+" "+cleanpostid+" "+page);
            
                var title = "";
                var link = "";
                var video = "";
                var room = "";
                $('.commentlong').hide();
                $('.commentshort').show();
                
                $.ajax({
                    url: rootserver+'room.php',
                    context: document.body,
                    type: 'POST',
                    data: 
                     { 
                    'providerid': $('#pid').val(),
                    'comment': comment,
                    'title': title,
                    'room': room,
                    'roomid': roomid,
                    'postid': postid,
                    'page': page,
                    'mode': 'EDIT'
                     }

                }).done(function( data, status ) {
                    alertify.set({ delay: 2000 });
                    alertify.log("Post changed"); 
                    //alertify.alert('Photo has been shared to selected Room');
                    if( data !=='')
                    {
                    }
                    $('#roominnerwindow').html(LoadingGIF);
                    if( data !==''){

                        $('#roominnerwindow').hide().html(data).fadeIn(800);
                        if( typeof reference !=='undefined' && reference!==''){

                            scrollToAnchor( reference.replace('.',''));
                        }
                        else
                        if( typeof anchor !=='undefined' && anchor!==''){

                            scrollToAnchor( anchor);
                        }
                    }
                });
            
        });
        $('body').on('keyup','.feedenter',function(e) {
            
            //if ((e.keyCode === 10 || e.keyCode === 13) && !e.shiftKey){
            //    $('#roompostenter').click();
            //    return;
            //}
        });
        $('body').on('keyup','.feedreplyenter',function(e) {
            
            /*
            if ((e.keyCode == 10 || e.keyCode == 13) && !e.shiftKey){
                var shareid = $(this).data('shareid');
                var reference = $(this).data('reference');
                lastcommentid = $(this).closest('.roomcommentsarea').find('.roomcomment');
                lastcommentheaderid = $(this).closest('.roomcommentsarea').find('.roomcommentheader');
                $(this).closest('.roomcommentsarea').find('.roomcommenthideheader').hide();
                $('#roomstatuscomment').val($(this).val());
                $(this).val("");
                $('#roompostreplyenter').data('shareid', shareid);
                $('#roompostreplyenter').data('reference', reference);
                $('#roompostreplyenter').click();
                return;
            }
            */
        });
        
        $('body').on('click','.feedphotoshare', function()
        {
            
                var selectedroom = $(this).data('selectedroom');
                if( selectedroom === 'Y'){
                
                    defaultRoomid = $(this).data('roomid');
                    PanelShow(13);
                    return;
                }
            
                var roomid = defaultRoomid;
                var mode = $(this).data('mode');
                var shareid = $(this).data('shareid');
                var postid = $(this).data('postid');
                var page = $(this).data('page');
                var photo = $(this).data('photo');
                
                if( roomid === 0){
                
                    PanelShow(9);
                    $('#popupwindow').html(LoadingGIF);
                    $('#popupwindow').load( rootserver+"roomselect.php",  {
                        'providerid': $('#pid').val(),
                        'room': room,
                        'roomid' :roomid,
                        'mode' : 'P'
                    }, function(html, status){
                            $("#mainview").scrollTop(0);
                    });
                    return;
                }
                
                
                var title = "";
                var comment = "";
                var link = "";
                var video = "";
                var room = "";
                
                $.ajax({
                    url: rootserver+'room.php',
                    context: document.body,
                    type: 'POST',
                    data: 
                     { 
                    'providerid': $('#pid').val(),
                    'comment': comment,
                    'title': title,
                    'shareid': shareid,
                    'photo': photo,
                    'video': video,
                    'link': link,
                    'room': room,
                    'roomid': roomid,
                    'postid': postid,
                    'page': page,
                    'mode': mode,
                    'parent': 'Y'
                     }

                }).done(function( data, status ) {
                    alertify.set({ delay: 2000 });
                    alertify.log("Photo has been shared to current room"); 
                    //alertify.alert('Photo has been shared to selected Room');
                    if( data !=='')
                    {
                    }
                });
                
            
        });
        
        $('body').on('click','.roomsharepost', function()
        {
                var mode = $(this).data('mode');
                var roomid = $(this).data('roomid');
                var articleid = $(this).data('articleid');
                
                PanelShow(9);
                $('#popupwindow').html(LoadingGIF);
                $('#popupwindow').load( rootserver+"roomsharepost.php",  {
                    'mode' : mode,
                    'articleid' : articleid,
                    'roomid' : roomid
                }, function(html, status){
                    if( mode === 'P' ){
                        $("#mainview").scrollTop(0);
                        defaultRoomid = roomid;
                        $('#trigger_room').data("roomid",roomid);
                        setTimeout(function() {
                            $('#trigger_room').trigger('click');    
                        }, 500);            
                    }
                    
                });
                return;
        });
        
        
        $(document).on( 'click',".hideroomcomment", function(e) 
        { 
            
            $('.roomcomment').hide();
            $('.roomcommentheader').show();
            $('.roomcommenthideheader').hide();
            
        });
        $(document).on( 'click',".showroomcomment", function(e) 
        { 

            var shareid = $(this).data('shareid');
            var mode = $(this).data('mode');
            
            $(this).parent().find('.roomcomment').show();
            //$(this).closest('.roomcommentsarea').find('.roomcomment').show();
            $(this).parent().find('.roomcommentheader').hide();
            $(this).parent().find('.roomcommenthideheader').show();
            //var commentid = $(this).closest('.roomcommentsarea').find('.roomcomment');
            var commentid = $(this).parent().find('.roomcomment');
            var commentheaderid = $(this).parent().find('.roomcommentheader');
            
            $(commentid).html(LoadingGIF);
            $(commentheaderid).html(LoadingGIF);
            AbortAjax();
            $.ajax({
                url: rootserver+'room_sub.php',
                context: document.body,
                type: 'POST',
                data: 
                 { 'providerid': $('#pid').val(), 
                   'shareid' : shareid,
                   'mode' : mode
                 }

            }).done(function( data, status ) {
                $(commentid).html(data);
                if( mode === 'X'){
                
                    //alertify.alert(data);
                    //$(commentid).html(data);
                    //return;
                }
                AbortAjax();
                $.ajax({
                    url: rootserver+'room.php',
                    context: document.body,
                    type: 'POST',
                    data: 
                     { 'providerid': $('#pid').val(), 
                       'shareid' : shareid,
                       'mode': 'C'
                     }

                }).done(function( data, status ) {
                    $(commentheaderid).html(data);
                });
                
                
            });
            
            
        });
        $('body').on('click','.roomselect1', function()
        {
                //var room = $('#statusroom').val();
                AbortAjax();
                PanelShow(4);
                LastFunc = '';
                
                $('#roominnerwindow').html(LoadingGIF);
                $.ajax({
                    url: rootserver+'roomselect.php',
                    context: document.body,
                    type: 'POST',
                    cache: true,
                    data: 
                     { 'providerid': $('#pid').val(), 
                        'room': '',
                        'roomid' :''
                     }

                }).done(function( data, status ) {

                    $('#roominnerwindow').hide().html(data).fadeIn(800);
                    $("#mainview").scrollTop(0);

                });
            
        });
        $('body').on('click','.postfind', function()
        {
                //var room = $('#statusroom').val();
                AbortAjax();
                var roomid = $(this).data('roomid');
                var room = $(this).data('room');
                PanelShow(4);
                
                $('#roominnerwindow').html(LoadingGIF);
                $.ajax({
                    url: rootserver+'roomfind.php',
                    context: document.body,
                    type: 'POST',
                    data: 
                     { 'providerid': $('#pid').val(), 
                        'room': room,
                        'roomid' :roomid
                     }

                }).done(function( data, status ) {

                    $('#roominnerwindow').html(data);
                    $("#mainview").scrollTop(0);

                });
            
        });
        
        $('body').on('click','.roomselect', function()
        {
            
                ChatId = 0;
                ChannelId = 0;
            
                //var room = $('#statusroom').val();
                AbortAjax();
                var roomid = $(this).data('roomid');
                var room = $(this).data('room');
                var mode = $(this).data('mode');
                var page = $(this).data('page');
                
                var roomfind = '';
                if(mode === 'F'){
                    roomfind = $('#findroom').val();
                    mode = '';
                }
                
                PanelShow(4);
                defaultRoomid = 0;
                
                $('#roominnerwindow').html(LoadingGIF);
                $.ajax({
                    url: rootserver+'roomselect.php',
                    context: document.body,
                    type: 'POST',
                    data: 
                     { 'providerid': $('#pid').val(), 
                        'room': room,
                        'roomid' :roomid,
                        'mode': mode,
                        'page' : page,
                        'find' : roomfind
                     }

                }).done(function( data, status ) {

                    $('#roominnerwindow').hide().html(data).fadeIn(800);
                    $("#mainview").scrollTop(0);

                }).fail(function( data, status ) {

                    $('#roominnerwindow').html(ConnectError);

                });
            
        });
        $('body').on('click','.roomdiscover', function()
        {
                //var room = $('#statusroom').val();
                AbortAjax();
                PanelShow(4);
                
                var roomfind = $('#findroom').val();
                var caller = $(this).data('caller');
                var category = $(this).data('category');
                
                $('#roominnerwindow').html(LoadingGIF);
                $.ajax({
                    url: rootserver+'roomdiscover.php',
                    context: document.body,
                    type: 'POST',
                    cache: true,
                    data: 
                     { 'providerid': $('#pid').val(), 
                       'category': category,
                       'caller': caller,
                       'find' : roomfind
                     }

                }).done(function( data, status ) {
                    if(data!==''){

                        $('#roominnerwindow').hide().html(data).fadeIn(800);
                        $("#mainview").scrollTop(0);
                    }

                });
            
        });        
        $('body').on('click','.userstore', function()
        {
            
                ChatId = 0;
                ChannelId = 0;
            
                //var room = $('#statusroom').val();
                AbortAjax();
                var roomid = $(this).data('roomid');
                var owner = $(this).data('owner');
                var mode = $(this).data('mode');
                var page = $(this).data('page');
                var category = $(this).data('category');
                
                var roomfind = '';
                if(mode === 'F'){
                    roomfind = $('#findproduct').val();
                    mode = '';
                }
                
                PanelShow(4);
                defaultRoomid = 0;
                
                $('#roominnerwindow').html(LoadingGIF);
                $.ajax({
                    url: rootserver+'userstore.php',
                    context: document.body,
                    type: 'POST',
                    data: 
                     { 'providerid': $('#pid').val(), 
                        'roomid' :roomid,
                        'owner' :owner,
                        'mode': mode,
                        'page' : page,
                        'category' : category,
                        'find' : roomfind
                     }

                }).done(function( data, status ) {

                    $('#roominnerwindow').hide().html(data).fadeIn(800);
                    $("#mainview").scrollTop(0);

                }).fail(function( data, status ) {

                    $('#roominnerwindow').html(ConnectError);

                });
            
        });        
        $('body').on('click','.groupinvitecreate', function()
        {
                var roomid = $(this).data("roomid");
                var mode = $(this).data("mode");
                $.ajax({
                    url: rootserver+'roomcreateinvite.php',
                    context: document.body,
                    type: 'POST',
                    cache: true,
                    data: 
                     { 'providerid': $('#pid').val(), 
                       'roomid': roomid,
                       'mode' : mode
                     }

                }).done(function( data, status ) {
                   $('.groupinvitelink').val(data);
                   $('.groupinvitelink').show();    
                   $('.groupinvitelinkgroup').show();    

                });
        });
        $('body').on('click','.groupinvitegotolink', function()
        {
                var link = $('.groupinvitelink').val();
                window.open( link );
        });
        
        $('body').on('click','.invitebutton', function()
        {
            alertify.set({ delay: 2000 });
            if( $('.inviteemail').val()=='' && $('.invitesms').val()=='' ){
            
                alertify.log("Missing Email Address or Text Phone"); 
                return;
            }
            if( $('.invitename').val()==''){
            
                alertify.log('Missing Friend Name');
                return;
            }
            var roomid = $(this).data('roomid');
            AbortAjax();
            
            $.ajax({
                url: rootserver+'friendinvite.php',
                context: document.body,
                type: 'POST',
                data: 
                 { 'providerid': $('#pid').val(), 
                   'inviteemail': $('.inviteemail').val(),
                   'invitesms': $('.invitesms').val(),
                   'invitename': $('.invitename').val(),
                   'roomid': roomid,
                   'invitemsg': $('.invitemsg').val(),
                   'mode': 'S'
                 }

            }).done(function( data, status ) {
                $('.inviteemail').val("");
                $('.invitename').val("");
                $('.invitesms').val("");
                alertify.set({ delay: 2000 });
                alertify.log('Invitation has been sent!');
                
            });

            
        });
        $('body').on('click','.uploadfile2', function()
        {
            if( MobileType === 'I'){
                var pid = $('#pid').val();
                var chatid = $(this).data('chatid');
                NativeCall('filelibrary'+"/"+pid+"-"+chatid);
                return;
            }
            if( MobileType === 'A'){
            
                var chatid = $(this).data('chatid');
                NativeCall('filelibrary'+"/"+pid+"-"+chatid);
                return;
            }
            
            AbortAjax();
            PanelShow(23);
            $('#functioniframe').prop('src',rootserver+"fileupload/upload-file.php");
            
        });
        $('body').on('click','.uploadfilecase', function()
        {
            if( MobileType === 'I'){
            
                NativeCall("filelibrary");
                return;
            }
            if( MobileType === 'A'){
            
                NativeCall("filelibrary");
                return;
            }
            //Global
            CaseId = $(this).data('caseid');
            
            AbortAjax();
            PanelShow(37);
            $('#functioniframe').prop('src',rootserver+"fileupload/upload-file.php");
            
        });

    
        $('body').on('click','.uploadphoto2', function()
        {
            if(!TermsOfUseCheck()){
                return;
            }
            if( MobileType === 'I'){
                var pid = $('#pid').val();
                var chatid = $(this).data('chatid');
                NativeCall('photofilepicker'+"/"+pid+"-"+chatid);
                return;
            }
            if( MobileType === 'A'){
                NativeCall('photofilepicker');
            
                return;
            }
            AbortAjax();
            PanelShow(10);
            $('#functioniframe').prop('src',rootserver+'upload/upload-photo.php');
            
        });
        $('body').on('click','.uploadphoto2chat', function()
        {
            if(!TermsOfUseCheck()){
                return;
            }
            if( MobileType === 'I'){
                var pid = $('#pid').val();
                NativeCall('photofilepicker'+"/"+pid+"-"+ChatId);
                return;
            }
            if( MobileType === 'A'){
                NativeCall('photofilepicker');
            
                return;
            }
            AbortAjax();
            PanelShow(10);
            $('#functioniframe').prop('src',rootserver+'upload/upload-photo.php');
            
        });
        
        $('body').on('click','.uploadcamera', function()
        {
            AbortAjax();
            PanelShow(10);
            $('#functioniframe').prop('src',rootserver+'upload/camera.php');
            
        });
        
        $('body').on('click','.camera', function()
        {
            if(!TermsOfUseCheck()){
                return;
            }
            var status = AppStoreCheck()
            if(status === 1 || status === 2){
                if(status ===2){
                    alert('This feature requires the mobile app');
                }
                return;
            }
            
            AbortAjax();
            if( LastFunc === 'C' && ChatId > 0){
                var pid = $('#pid').val();
                var chatid = $(this).data('chatid');
                NativeCall("camera"+"/"+pid+"-"+chatid);
                /*
                alertify.confirm('Photo will be added to Chat.',function(ok){
                    if( ok ) {
                        NativeCall("camera");
                    }
                });
                */
                

            }
            else
            if( LastFunc === 'R' && parseInt(defaultRoomid) > 0 ){
            
                var pid = $('#pid').val();
                NativeCall("camera"+"/"+pid);
                /*
                alertify.confirm('Photo will be added to Room.',function(ok){
                    if( ok ) {
                    }
                });
                */
                

            }
            else
            if( LastFunc === 'U' && parseInt(defaultRoomid) > 0 ){
                var pid = $('#pid').val();
            
                alertify.confirm('Photo will become your profile picture.',function(ok){
                    if( ok ) {
                        NativeCall("camera"+"/"+pid);
                    }
                });
                

            }
            else{
                var pid = $('#pid').val();
                NativeCall("camera"+"/"+pid);
            }
            return;
            
        });        
        $('body').on('click','.uploadavatarcamera', function()
        {
            var status = AppStoreCheck()
            if(status === 1 || status === 2){
                if(status ===2){
                    alert('This feature requires the mobile app');
                }
                return;
            }
            var pid = $('#pid').val();
            NativeCall("avatar"+"/"+pid);
            
        });
        $('body').on('click','.uploadavatar', function()
        {
            if(!TermsOfUseCheck()){
                return;
            }
            //if(MobileType === 'A' || MobileType === 'I'){

                PanelShow(9);
                $('#popupwindow').load( rootserver+"avatarchg.php",  {
                   'timestamp' : timeStamp()
                }, function(html, status){
                    $(".mainview").scrollTop(0);
                });
                return;
            //}
            AbortAjax();
            PanelShow(30);
            $('#functioniframe').prop('src',rootserver+'blank.php');
            $('#avatarform').submit();
            
        });
        
        $('body').on('click','.uploadphoto', function()
        {
            AbortAjax();
            PanelShow(27);
            $('#functioniframe').prop('src',rootserver+'blank.php');
            $('#uploadphotoform').submit();
            
        });
        $('body').on('click','.artistupload', function()
        {
                PanelShow(9);
                $('#popupwindow').load( rootserver+"camera.php?"+timeStamp(),  {
                   'timestamp' : timeStamp()
                }, function(html, status){
                    $(".mainview").scrollTop(0);
                });
            
        });
        $('body').on('click','.artistupload', function()
        {
            AbortAjax();
            PanelShow(10);
            $('#functioniframe').prop('src',rootserver+'blank.php');
            $('#artistuploadform').submit();
        });
        $('body').on('click','.iotdeviceview', function()
        {
            AbortAjax();
            $(".mainview").scrollTop(0);
            
            var url = $(this).data('url')+"&"+timeStamp();
            var baseurl = $(this).data('baseurl');
            window.open( url, "_blank");
            return;

            AbortAjax();
            $.ajax({
                 url: rootserver+"/test.json?"+timeStamp(),
                 timeout : 500,
                 dataType : jsonp,
                 jsonp: "iotcallback"
             }).success(function(){
                alert("success"); 
             });
         });
         function iotcallback()
         {
             alert('called');
            var url = $('.iotdeviceview').data('url')+"&"+timeStamp();
            window.open( url, "_blank");
         };
         /*
                alert('Fail');
                
                PanelShow(13);
                $.ajax({
                     url: rootserver+"iotfail.php",
                     context: document.body,
                     type: 'GET',
                     data: 
                      { 
                      }

                 }).done(function(data, status){
                    $('#socialwindow').html(data);
                 });
             });
            

        });
        */
        $('body').on('click','.photoview', function()
        {
            AbortAjax();
            PanelShow(28);
            $(".mainview").scrollTop(0);
            $('#photoviewform').find('#photoview_filename').val( $(this).data('filename') );
            
            $('#functioniframe').prop('src',rootserver+'blank.php');
            $('#photoviewform').submit();
        });
        $('body').on('click','.feedphotochat', function()
        {
            AbortAjax();
            PanelShow(28);
            $('#photoviewform').find('#photoview_filename').val( $(this).attr('src') );
            
            $('#functioniframe').prop('src',rootserver+'blank.php');
            $('#photoviewform').submit();
        });
        $('body').on('click','.expandphoto', function()
        {
            if(hostedmode === true){
                return;
            }
            AbortAjax();
            PanelShow(28);
            $(".mainview").scrollTop(0);
            $('#photoviewform').find('#photoview_filename').val( $(this).attr('src') );
            
            $('#functioniframe').prop('src',rootserver+'blank.php');
            $('#photoviewform').submit();
        });
        $('body').on('click','.videoview', function()
        {
            AbortAjax();
            PanelShow(28);
            $('#videoviewform').find('#videoview_url').val( $(this).data('url') );
            
            $('#functioniframe').prop('src',rootserver+'blank.php');
            $('#videoviewform').submit();
        });
        
        $('body').on('click','.slideshow', function()
        {
            AbortAjax();
            $(".mainview").scrollTop(0);
            PanelShow(28);
            $('#slideshowform').find('#slideshow_album').val( $(this).data('album') );
            $('#slideshowform').find('#slideshow_pid').val( $(this).data('providerid') );
            
            $('#functioniframe').prop('src',rootserver+'blank.php');
            $('#slideshowform').submit();
        });
                
        $('body').on('click','.leaveslideshow', function()
        {
            PanelShow(-1); //Restore Last Panel
        });
        $('body').on('click','.wrap', function()
        {
            AbortAjax();
            PanelShow(31);
            $('#wrapform').find('#wrap_url').val( $(this).data('url') );
            
            $('#functioniframe').prop('src',rootserver+'blank.php');
            $('#wrapform').submit();
        });
        

        $('body').on('click','.uploadcsv', function()
        {
                if(xhr && xhr.readyState !== 4){
                    xhr.abort();
                }
            PanelShow(30);
            $('#functioniframe').prop('src',rootserver+'blank.php');
            $('#uploadcsvform').submit();
            
        });
        $('body').on('click','.uploadsignupcsv', function()
        {
                if(xhr && xhr.readyState !== 4){
                    xhr.abort();
                }
            PanelShow(30);
            $('#functioniframe').prop('src',rootserver+'blank.php');
            $('#uploadsignupcsvform').submit();
            
        });
        $('body').on('click','.uploadtextcsv', function()
        {
                if(xhr && xhr.readyState !== 4){
                    xhr.abort();
                }
            PanelShow(30);
            $('#functioniframe').prop('src',rootserver+'blank.php');
            $('#uploadtextcsvform').submit();
            
        });
        
        $('body').on('click','.uploadfile', function()
        {
            if(xhr && xhr.readyState !== 4){
                xhr.abort();
            }
            if( MobileType === 'I'){
            
                var pid = $('#pid').val();
                NativeCall("filelibrary"+"/"+pid);
                return;
            }
            if( MobileType === 'A'){
            
                NativeCall("filepicker");
                return;
            }
            $('#uploadfileform').find('#uploadfile_chatid').val('');
            PanelShow(23);
            $('#functioniframe').prop('src',rootserver+'blank.php');
            $('#uploadfileform').submit();
            
        });
        $('body').on('click','.uploadfilefromchat', function()
        {
            if(xhr && xhr.readyState !== 4){
                xhr.abort();
            }
            if( MobileType === 'I'){
            
                var pid = $('#pid').val();
                NativeCall("filelibrary"+"/"+pid);
                return;
            }
            if( MobileType === 'A'){
            
                NativeCall("filelibrary");
                return;
            }
            $('#uploadfileform').find('#uploadfile_otherid').val($(this).data("otherid"));
            $('#uploadfileform').find('#uploadfile_chatid').val($(this).data("chatid"));
            $('#uploadfileform').find('#uploadfile_passkey64').val($(this).data("passkey64"));
            PanelShow(36);
            $('#functioniframe').prop('src',rootserver+'blank.php');
            $('#uploadfileform').submit();
            
        });
        $('body').on('click','.uploadfilefromselect', function()
        {
            if(xhr && xhr.readyState !== 4){
                xhr.abort();
            }
            if( MobileType === 'I'){
            
                var pid = $('#pid').val();
                NativeCall("filelibrary"+"/"+pid);
                return;
            }
            if( MobileType === 'A'){
            
                NativeCall("filelibrary");
                return;
            }
            PanelShow(24);
            $('#functioniframe').prop('src',rootserver+'blank.php');
            $('#uploadfileform').submit();
            
        });
        
        
        $('body').on('click','.uploadphotofromselect', function()
        {
            if(xhr && xhr.readyState !== 4){
                xhr.abort();
            }
            PanelShow(25);
            $('#functioniframe').prop('src',rootserver+'blank.php');
            $('#uploadphotoform').submit();
            
        });
        
        
        
        $('body').on('click','.roomlist', function()
        {
                AbortAjax();
                PanelShow(4);
                var room = $(this).data('room');
                $('#roominnerwindow').html(LoadingGIF);
                $('#roominnerwindow').hide().load( rootserver+"friends.php?"+timeStamp(),  {
                    'providerid': $('#pid').val(),
                    'room': room,
                    'mode': ''
                }, function(html, status){
                        $("#roominnerwindow").scrollTop(0);
                }).fadeIn(800);
            
        });
        $('body').on('click','.roomjoin', function()
        {
                AbortAjax();
                var mode = $(this).data('mode');
                var action = $(this).data('action');
                var caller = $(this).data('caller');
                var handle = ""+$('#roomhandle').val();
                var handle2 = $(this).data('handle');
                var roomid = $(this).data('roomid');
                if( typeof handle2!=='undefined'){
                    handle = handle2;
                }
                if( typeof action === "undefined"){
                    action = "";
                }
                
                if( ( handle === '' || handle ==='#') && (mode === 'J' || mode === 'R') ){
                    $('#trigger_roomselect').trigger('click');
                    return;
                }

                //$('#roominnerwindow').html(LoadingGIF);
                $.ajax({
                    url: rootserver+'roomjoin.php?'+timeStamp(),
                    context: document.body,
                    type: 'POST',
                    data: 
                     {
                        'providerid': $('#pid').val(),
                        'mode': mode,
                        'caller': caller,
                        'roomid' : roomid,
                        'handle' :handle,
                        'action' : action
                     }
                }).done(function(data){
                    if(action === 'RADIO'){
                        //alertify.alert("Accepted to this live streaming channel. Go to LIVE to access.");
                        $('#trigger_roomselectlive').trigger('click');
                        return;
                    }
                    if(action === 'RADIO2'){
                        //alertify.alert("Accepted to this live streaming channel. Go to LIVE to access.");
                        $('#trigger_selectlive').trigger('click');
                        return;
                    }
                    if( mode === 'J' || mode === 'JENTERPRISE' || mode==='JCOMMUNITY'){
                        
                    
                        var msg = jQuery.parseJSON(data);
                        defaultRoomid = msg.roomid;
                        inforequest = msg.inforequest;
                        if( msg.msg!==''){
                            alertify.alert( msg.msg);
                            return;
                        }
                        PanelShow(4);
                        if(inforequest==='Y'){
                            $('#trigger_credentialget').trigger('click');
                        } else
                        if(mode === 'JCOMMUNITY'){
                            $('#trigger_selectchat').trigger('click');
                        } else
                        if(handle!==''){
                            $('#trigger_room').data("roomid",msg.roomid);
                            $('#trigger_room').data("caller",caller);
                            $('#trigger_room').trigger('click');
                        }
                        else {
                            $('#trigger_discoverroom').trigger('click');
                        }
                    } else {
                        PanelShow(4);
                    }
                    
                    if( action !== ''){
                        var msg = jQuery.parseJSON(data);
                        if( msg.msg!==''){
                            alertify.alert( msg.msg);
                        }
                        
                    }
                    $(".mainview").scrollTop(0);
                    
                });
            
        });
        $('body').on('click','.roomedit', function()
        {
                var mode = $(this).data('mode');
                var room = $(this).data('room');
                var roomid = $(this).data('roomid');
                var discover = 'N';                
                var friendproviderid = $(this).data('providerid');


                if($('#discover1').is(":checked")) {
                    discover = $('#discover1').val();
                }
                if( room === '' && mode === 'E'){
                    return;
                }
                if( mode === 'DR'){
                    alertify.confirm('Delete Room, members, and content?',function(ok){
                        if( ok )
                        {
                            $('#roominnerwindow').load( rootserver+"friends.php?"+timeStamp(),  {
                                'providerid': $('#pid').val(),
                                'room': room,
                                'mode': mode,
                                'roomid': roomid
                            }, function(html, status){
                                    $(".mainview").scrollTop(0);
                            });
                            
                        }
                    });
                    return;
                    
                }
                if( mode === 'D'){
                    alertify.confirm('Remove yourself from this Room?<br><br>Caution: This will also remove all your prior posts and activity from this room.',function(ok){
                        if( ok )
                        {
                            $('#roominnerwindow').load( rootserver+"friends.php?"+timeStamp(),  {
                                'providerid': $('#pid').val(),
                                'room': room,
                                'mode': mode,
                                'roomid': roomid,
                                'friendproviderid': friendproviderid,
                            }, function(html, status){
                                    defaultRoomid = 0;
                                    $(".mainview").scrollTop(0);
                            });
                            
                        }
                    });
                    return;
                    
                }
                
                PanelShow(4);
                
                var roomanonymous = 'A';
                if($('#roomanonymous1').is(":checked")) {
                    roomanonymous = 'Y';
                }
                if($('#roomanonymous2').is(":checked")) {
                    roomanonymous = 'N';
                }
                var roomexternal = 'N';
                if($('#roomexternal1').is(":checked")) {
                    roomexternal = 'Y';
                }
                var privateflag = 'N';
                if($('#private1').is(":checked")) {
                    privateflag = 'Y';
                }
                var contactexchange = 'N';
                if($('#contactexchange1').is(":checked")) {
                    contactexchange = 'Y';
                }
                var adminonly = 'N';
                if($('#adminonly2').is(":checked")) {
                    adminonly = 'Y';
                }
                var notifications = 'Y';
                if($('#notifications2').is(":checked")) {
                    notifications = 'N';
                }
                var showmembers = 'Y';
                if($('#showmembers2').is(":checked")) {
                    showmembers = 'N';
                }
                
                var soundalert = '0';
                if($('#soundalert2').is(":checked")) {
                    soundalert = '1';
                }
                
                var sharephotoflag = '0';
                if($('#sharephotoflag2').is(":checked")) {
                    sharephotoflag = '1';
                }
               
                var webpublishprofile = 'N';
                if($('#webpublishprofile1').is(":checked")) {
                    webpublishprofile = 'Y';
                }
                
                var searchengine = 'N';
                if($('#searchengine1').is(":checked")) {
                    searchengine = 'Y';
                }
                var store = 'N';
                if($('#roomstore1').is(":checked")) {
                    store = 'Y';
                }
                
                
                
                var newroom = $('#newroomname').val();
                var roomdesc = $('#roomdesc').val();
                var tags = $('#roomtags').val();
                var minage = $('#roomage').val();
                var handle = $('#roomhandle').val();
                var organization = $('#roomorganization').val();
                var photourl = $('#photourl').val();
                var photourl2 = $('#photourl2').val();
                var category = $('#roomcategory').val();
                var rsscategory = $('#rsscategory').val();
                var rsssource = $('#rsssource').val();
                var radiostation = $('#radiostation').val();
                var groupid = $('#roomgroupid').val();
                var sponsor = $('#roomsponsor').val();
                var parent = $('#roomparent').val();
                var childsort = $('#roomchildsort').val();
                var profileflag = $('#profileflag').val();
                var roominvitehandle = $('#roominvitehandle').val();
                var webcolorscheme = $('#webcolorscheme').val();
                var webtextcolor = $('#webtextcolor').val();
                var webflags = $('#webflags').val();
                var copymembers = $('#copymembers').val();
                var analytics = $('#analytics').val();
                var subscriptiondays = $('#subscriptiondays').val();
                var subscription = $('#subscription').val();
                var subscriptionusd = $('#subscriptionusd').val();
                var wallpaper = $('#wallpaper').val();
                var autochatuser = $('#autochatuser').val();
                var autochatmsg = $('#autochatmsg').val();
                var community = $('#community').val();
                var communitylink = $('#communitylink').val();
                var roomstyle = $('#roomstyle').val();
                
                
                
                $('#roominnerwindow').html(LoadingGIF);
                $('#roominnerwindow').hide().load( rootserver+"friends.php?"+timeStamp(),  {
                    'providerid': $('#pid').val(),
                    'room': room,
                    'roomid': roomid,
                    'newroom': newroom,
                    'roomdescription' : roomdesc,
                    'tags' : tags,
                    'category' : category,
                    'rsscategory' : rsscategory,
                    'rsssource' : rsssource,
                    'minage' : minage,
                    'mode': mode,
                    'discover': discover,
                    'roomanonymous': roomanonymous,
                    'roomexternal': roomexternal,
                    'organization': organization,
                    'handle' :handle,
                    'private' :privateflag,
                    'contactexchange': contactexchange,
                    'adminonly': adminonly,
                    'notifications': notifications,
                    'showmembers' : showmembers,
                    'soundalert': soundalert,
                    'sharephotoflag': sharephotoflag,
                    'photourl' :photourl,
                    'photourl2' :photourl2,
                    'groupid' : groupid,
                    'radiostation' : radiostation,
                    'sponsor' : sponsor,
                    'parent' : parent,
                    'childsort' : childsort,
                    'copymembers' : copymembers,
                    'profileflag' : profileflag,
                    'roominvitehandle' : roominvitehandle,
                    'webcolorscheme' : webcolorscheme,
                    'webtextcolor' : webtextcolor,
                    'webpublishprofile' : webpublishprofile,
                    'webflags' : webflags,
                    'searchengine' : searchengine,
                    'analytics' : analytics,
                    'subscription' : subscription,
                    'subscriptionusd' : subscriptionusd,
                    'subscriptiondays' : subscriptiondays,
                    'wallpaper' : wallpaper,
                    'autochatuser' : autochatuser,
                    'autochatmsg' : autochatmsg,
                    'community': community,
                    'communitylink': communitylink,
                    'store': store,
                    'roomstyle': roomstyle,
                }, function(html, status){
                        $('#roominnerwindow').html(html);
                        $(".mainview").scrollTop(0);
                }).fadeIn(800);
            
        });
        $('body').on('click','.roommembers', function()
        {
                AbortAjax();
                PanelShow(4);
                var friendproviderid = $(this).data('providerid');
                var room = $(this).data('room');
                var roomid = $(this).data('roomid');
                var caller = $(this).data('caller');
                var mode = $(this).data('mode');
                var filter = $('#roommemberfilter').val();
                $('#roominnerwindow').html(LoadingGIF);
                $('#roominnerwindow').hide().load( rootserver+"roommembers.php",  {
                    'providerid': $('#pid').val(),
                    'friendproviderid': friendproviderid,
                    'room': room,
                    'roomid': roomid,
                    'caller': caller,
                    'mode': mode,
                    'filter': filter,
                }, function(html, status){
                        $("#roominnerwindow").scrollTop(0);
                }).fadeIn(800);
            
        });
        $('body').on('click','.roomfavorite', function()
        {
                AbortAjax();
                var roomid = $(this).data('roomid');
                var caller = $(this).data('caller');
                var mode = $(this).data('mode');
                
                $.ajax({
                    url: rootserver+'roomfavorite.php',
                    context: document.body,
                    type: 'POST',
                    data: 
                     {
                        'providerid': $('#pid').val(),
                        'roomid': roomid,
                        'caller': caller,
                        'mode': mode,
                     }
                }).done(function(){
                    if(mode === 'A'){
                        alertify.alert('Room added to favorites');  
                    }
                    if(mode === 'D'){
                        alertify.alert('Room removed from favorites');  
                    }
                    
                });
            
        });
        
        $('body').on('click','.followers', function()
        {
            
                //UpdateTimeStamp();
                AbortAjax();

                var caller = $(this).data('caller');
                var providerid = $(this).data('userid');
                PanelShow(9);
                $('#popupwindow').load( rootserver+"followers.php?"+timeStamp(),  {
                    'providerid': providerid,
                    'caller': caller,
                }, function(html, status){
                        $('#popupwindow').html(html);
                        $(".mainview").scrollTop(0);
                });
                //Sizing();
            
        });
        
        $('body').on('click','.managefriends', function()
        {
                AbortAjax();
                var friendid = $(this).data('friendid');
                var caller = $(this).data('caller');
                var mode = $(this).data('mode');
                
                if(mode==='AF'){
                    alertify.set({ labels: {
                        ok     : "Regular Follow",
                        cancel : "Incognito Follow"
                    } });            
                    alertify.confirm("Follow?", function(ok){
                        if(ok){
                            AddFriend( friendid, caller, mode, '');
                        } else {
                            AddFriend( friendid, caller, mode, 'INCOGNITO');
                        }
                        alertify.set({ labels: {
                            ok     : "OK",
                            cancel : "Cancel"
                        } });            
                        return;
                    });
                    return;
                }
                if(mode==='UF'){
                    alertify.set({ labels: {
                        ok     : "OK",
                        cancel : "Cancel"
                    } });            
                    alertify.confirm("Unfollow?", function(ok){
                        AddFriend( friendid, caller, mode, '');
                        return;
                    });
                    return;
                }
                if(mode==='BAN'){
                    alertify.set({ labels: {
                        ok     : "OK",
                        cancel : "Cancel"
                    } });            
                    alertify.confirm("Ban/Unban?", function(ok){
                        AddFriend( friendid, caller, mode, '');
                        return;
                    });
                    return;
                }
                if(mode==='XBAN'){
                    alertify.set({ labels: {
                        ok     : "OK",
                        cancel : "Cancel"
                    } });            
                    alertify.confirm("Remove BanID?", function(ok){
                        AddFriend( friendid, caller, mode, '');
                        return;
                    });
                    return;
                }
                
                
                if(mode!=='A'){
                    AddFriend( friendid, caller, mode, '');
                    return;
                }

                alertify.set({ labels: {
                    ok     : "Family",
                    cancel : "Friend"
                } });            
                alertify.confirm('Choose a Friend Level',function(ok){
                    if( ok ){
                        AddFriend( friendid, caller, mode, 'FAMILY');
                    } else {
                        AddFriend( friendid, caller, mode, 'FRIEND');
                    }
                    alertify.set({ labels: {
                        ok     : "OK",
                        cancel : "Cancel"
                    } });            
                });
            
            
        });
        function AddFriend( friendid, caller, mode, friendlevel )
        {
                $.ajax({
                    url: rootserver+'managefriends.php',
                    context: document.body,
                    type: 'POST',
                    data: 
                     {
                        'providerid': $('#pid').val(),
                        'friendid': friendid,
                        'caller': caller,
                        'mode': mode,
                        'friendlevel' : friendlevel,
                        'chatid' : ChatId
                     }
                }).done(function(){
                    if(mode === 'A'){
                        //alertify.alert('Friend added');  
                    }
                    if(mode === 'D'){
                        $('#trigger_findpeople').data('mode','P7').click().data('mode','');
                    }
                    
                });
            
        }
        
        $('body').on('click','.friendlist', function()
        {
                AbortAjax();
                PanelShow(4);
                var friendproviderid = $(this).data('providerid');
                var room = $(this).data('room');
                var roomid = $(this).data('roomid');
                var caller = $(this).data('caller');
                var mode = $(this).data('mode');
                var filter = $('#roommemberfilter').val()
                $('#roominnerwindow').html(LoadingGIF);
                $('#roominnerwindow').load( rootserver+"roommembers.php",  {
                    'providerid': $('#pid').val(),
                    'friendproviderid': friendproviderid,
                    'room': room,
                    'roomid': roomid,
                    'caller': caller,
                    'mode': mode,
                    'filter': filter,
                }, function(html, status){
                        $("#roominnerwindow").scrollTop(0);
                });
            
        });
        $('body').on('click','.friendinvite', function()
        {
                AbortAjax();
                PanelShow(4);
                var room = $(this).data('room');
                var roomid = $(this).data('roomid');
                var caller = $(this).data('caller');
                $('#roominnerwindow').html(LoadingGIF);
                $('#roominnerwindow').load( rootserver+"friendinvite.php",  {
                    'providerid': $('#pid').val(),
                    'room': room,
                    'roomid': roomid,
                    'caller': caller
                }, function(html, status){
                        $("#roominnerwindow").scrollTop(0);
                });
            
        });
        $('body').on('click','.socialshare', function()
        {
            var share = $(this).data('share');
            window.open( share,"_blank");
        });
        $('body').on('click','.sharetrack', function()
        {
            var roomid = $(this).data('roomid');
            $.ajax({
                url: rootserver+'sharetrack.php?'+timeStamp(),
                context: document.body,
                type: 'POST',
                data: 
                 {
                    'providerid': $('#pid').val(),
                    'roomid': roomid
                 }
            });
        });
        $('body').on('click','.rssview', function()
        {
            AbortAjax();
            PanelShow(32);
            $(".mainview").scrollTop(0);
            var articleid = $(this).data('articleid');
            $('#functioniframe').prop('src',rootserver+'blank.php');
            $('#rssform_articleid').val( articleid );
                
            $('form#rssform').prop('target','functioniframe');
            $('form#rssform').submit();
        });
        $('body').on('click','.roomothertext', function()
        {
                //PanelShow(3);
                var reply = $(this).data('reply');
                var icon = ">";
                $('#replycomment').focus().val("").val(icon+reply+" - ");
                if(reply!==''){
                    $('.replycomment').val(icon+reply+" - ");
                }

        });
   /*****************************************************************************
    *
    *    
    *    
    *    *  GROUP MANAGE
    *  
    * 
    * 
    *****************************************************************************/
        $('body').on('click','.groupmanage', function()
        {
                AbortAjax();
                PanelShow(4);
                var groupid = $(this).data('groupid');
                var mode = $(this).data('mode');
                var providerid = $(this).data('providerid');
                var filter = $('#groupmanagefilter').val();
                
                var groupname = $('#groupname').val();
                var groupdesc = $('#groupdesc').val();
                var photourl = $('#groupphotourl').val();
                var organization = $('#grouporganization').val();
                var roomid = $('#grouproomid').val();
                
                if( mode === 'D'){
                    alertify.confirm('Delete member from community?',function(ok){
                        if( ok ){
                        
                            $('#roominnerwindow').hide().load( rootserver+"groupsetup.php",  {
                                'friendproviderid': providerid,
                                'groupid': groupid,
                                'mode': mode,
                                'groupname':groupname,
                                'groupdesc': groupdesc,
                                'photourl': photourl,
                                'organization': organization,
                                'roomid' : roomid,
                                'filter' : filter

                            }, function(html, status){
                                    $("#roominnerwindow").scrollTop(0);
                            }).fadeIn(800);
                        }
                    });
                    return;
                    
                }
                
                
                if( mode === 'DR'){
                    alertify.confirm('Delete this entire community?<br><br>Warning: This is not reversible. All members will have to be reentered.',function(ok){
                        if( ok ){
                        
                            $('#roominnerwindow').hide().load( rootserver+"groupsetup.php",  {
                                'friendproviderid': providerid,
                                'groupid': groupid,
                                'mode': mode,
                                'groupname':groupname,
                                'groupdesc': groupdesc,
                                'photourl': photourl,
                                'organization': organization,
                                'roomid' : roomid,
                                'filter' : filter

                            }, function(html, status){
                                    $("#roominnerwindow").scrollTop(0);
                            }).fadeIn(800);
                        }
                    });
                    return;
                    
                }
                
                $('#roominnerwindow').html(LoadingGIF);
                $('#roominnerwindow').hide().load( rootserver+"groupsetup.php",  {
                    'friendproviderid': providerid,
                    'groupid': groupid,
                    'mode': mode,
                    'groupname':groupname,
                    'groupdesc': groupdesc,
                    'photourl': photourl,
                    'organization': organization,
                    'roomid' : roomid,
                    'filter' : filter
                
                }, function(html, status){
                        $("#roominnerwindow").scrollTop(0);
                }).fadeIn(800);
        });

        $('body').on('click','.sponsormanage', function()
        {
                AbortAjax();
                PanelShow(4);
                var sponsor = $(this).data('sponsor');
                if( sponsor ==='' || typeof sponsor === "undefined"){
                    sponsor = $('#sponsor').val();
                    
                }
                var mode = $(this).data('mode');
                var providerid = $(this).data('providerid');
                var filter = $('#sponsormanagefilter').val();
                
                var wizardtype = $('#sponsorwizardtype').val();
                
                
                var sponsorname = $('#sponsorname').val();
                var sponsornew = $('#sponsornew').val();
                var welcome = $('#welcome').val();
                var logo = $('#sponsorlogo').val();
                var roomhashtag = $('#sponsorroomhashtag').val();
                var partitioned = $('#sponsorpartitioned').val();
                var live = $('#sponsorlive').val();
                var needemail = $('#sponsorneedemail').val();
                var colorscheme = $('#sponsorcolorscheme').val();
                var colorschemeinvite = $('#sponsorcolorschemeinvite').val();
                var priority = $('#sponsorpriority').val();
                var boxcolor = $('#sponsorboxcolor').val();
                var enterpriselist = $('#sponsorenterpriselist').val();
                var communitylist = $('#sponsorcommunitylist').val();
                var industry = $('#sponsorindustry').val();
                var autochatuser = $('#sponsorautochatuser').val();
                
                
                if( mode === 'DR'){
                    alertify.confirm('Delete this Domain?',function(ok){
                        if( ok ){
                        
                            $('#roominnerwindow').hide().load( rootserver+"sponsorsetup.php",  {
                                'friendproviderid': providerid,
                                'sponsor': sponsor,
                                'mode': mode,
                                'sponsorname':sponsorname,
                                'welcome': welcome,
                                'logo': logo,
                                'roomhashtag' : roomhashtag,
                                'partitioned' : partitioned,
                                'live' : live,
                                'needemail' : needemail,
                                'colorscheme' : colorscheme,
                                'colorschemeinvite' : colorschemeinvite,
                                'priority' : priority,
                                'boxcolor' : boxcolor,
                                'enterpriselist' : enterpriselist,
                                'communitylist' : communitylist,
                                'industry' : industry,
                                'wizardtype' : wizardtype,
                                'autochatuser' : autochatuser,
                                'filter' : filter

                            }, function(html, status){
                                    $("#roominnerwindow").scrollTop(0);
                            }).fadeIn(800);
                        }
                    });
                    return;
                    
                }
                
                
                $('#roominnerwindow').html(LoadingGIF);
                $('#roominnerwindow').hide().load( rootserver+"sponsorsetup.php",  {
                    'friendproviderid': providerid,
                    'sponsor': sponsor,
                    'mode': mode,
                    'sponsorname':sponsorname,
                    'welcome': welcome,
                    'logo': logo,
                    'roomhashtag' : roomhashtag,
                    'partitioned' : partitioned,
                    'live' : live,
                    'needemail' : needemail,
                    'colorscheme' : colorscheme,
                    'colorschemeinvite' : colorschemeinvite,
                    'priority' : priority,
                    'boxcolor' : boxcolor,
                    'enterpriselist' : enterpriselist,
                    'communitylist' : communitylist,
                    'industry' : industry,
                    'wizardtype' : wizardtype,
                    'autochatuser' : autochatuser,
                    'sponsornew' : sponsornew,
                    'filter' : filter
                
                }, function(html, status){
                        $("#roominnerwindow").scrollTop(0);
                }).fadeIn(800);
        });
    
        $('body').on('click','.sponsorlist', function()
        {
                AbortAjax();
                PanelShow(4);
                var sponsor = $(this).data('sponsor');
                var mode = $(this).data('mode');
                var providerid = $(this).data('providerid');
                
                
                $('#roominnerwindow').html(LoadingGIF);
                $('#roominnerwindow').hide().load( rootserver+"sponsorlist.php",  {
                    'sponsor': sponsor,
                    'providerid': providerid,
                    'mode': mode
                
                }, function(html, status){
                        $("#roominnerwindow").scrollTop(0);
                }).fadeIn(800);
        });
        
        
        
        
        $('body').on('click','.productmanage', function()
        {
                AbortAjax();
                PanelShow(4);
                var product = $(this).data('product');
                if( product ==='' || typeof product === "undefined"){
                    product = $('#product').val();
                    
                }
                var mode = $(this).data('mode');
                var providerid = $(this).data('providerid');
                var filter = $('#productmanagefilter').val();
                
                var productname = $('#productname').val();
                var productdesc = $('#productdesc').val();
                var productcategory = $('#productcategory').val();
                var productphoto = $('#productphoto').val();
                var productprice = $('#productprice').val();
                var productshipping = $('#productshipping').val();
                var productshipping2 = $('#productshipping2').val();
                var producttax = $('#producttax').val();
                var productshippingflag = $('#productshippingflag').val();
                var productseq = $('#productseq').val();
                var productcurrency = $('#productcurrency').val();
                var productstock = $('#productstock').val();
                var productweight = $('#productweight').val();
                var productsubscription = $('#productsubscription').val();
                var productsubscriptionperiod = $('#productsubscriptionperiod').val();
                var productoption1 = $('#productoption1').val();
                var productoption2 = $('#productoption2').val();
                var productstatus = $('#productstatus').val();
                
                
                if( mode === 'D'){
                    alertify.confirm('Delete this Product?',function(ok){
                        if( ok ){
                        
                            $('#roominnerwindow').hide().load( rootserver+"productsetup.php",  {
                                'providerid' : providerid,
                                'mode' : mode,
                                'product' : product,
                                'productname' : productname,
                                'productdesc' : productdesc,
                                'productphoto' : productphoto,
                                'productprice' : productprice,
                                'productshipping' : productshipping,
                                'productshipping2' : productshipping2,
                                'producttax' : producttax,
                                'productshippingflag' : productshippingflag,
                                'productseq' : productseq,
                                'productcurrency' : productcurrency,
                                'productstock' : productstock,
                                'productweight' : productweight,
                                'productsubscription' : productsubscription,
                                'productsubscriptionperiod' : productsubscriptionperiod,
                                'productcategory' : productcategory,
                                'filter' : filter

                            }, function(html, status){
                                    $("#roominnerwindow").scrollTop(0);
                            }).fadeIn(800);
                        }
                    });
                    return;
                    
                }
                
                
                $('#roominnerwindow').html(LoadingGIF);
                $('#roominnerwindow').hide().load( rootserver+"productsetup.php",  {
                        'providerid' : providerid,
                        'mode' : mode,
                        'product' : product,
                        'productname' : productname,
                        'productdesc' : productdesc,
                        'productphoto' : productphoto,
                        'productprice' : productprice,
                        'productshipping' : productshipping,
                        'productshipping2' : productshipping2,
                        'producttax' : producttax,
                        'productshippingflag' : productshippingflag,
                        'productseq' : productseq,
                        'productcurrency' : productcurrency,
                        'productstock' : productstock,
                        'productweight' : productweight,
                        'productsubscription' : productsubscription,
                        'productsubscriptionperiod' : productsubscriptionperiod,
                        'productoption1' : productoption1,
                        'productoption2' : productoption2,
                        'productstatus' : productstatus,
                        'productcategory' : productcategory,
                        'filter' : filter
                
                }, function(html, status){
                        $("#roominnerwindow").scrollTop(0);
                }).fadeIn(800);
        });
    
        $('body').on('click','.productlist', function()
        {
                AbortAjax();
                PanelShow(4);
                var product = $(this).data('product');
                var mode = $(this).data('mode');
                var providerid = $(this).data('providerid');
                var paypalemail = $('#paypalemail').val();
                var sandbox = $('#sandbox').val();
                var find = $('#findproduct').val();
                
                
                $('#roominnerwindow').html(LoadingGIF);
                $('#roominnerwindow').hide().load( rootserver+"productlist.php",  {
                    'product': product,
                    'providerid': providerid,
                    'paypalemail' : paypalemail,
                    'sandbox' : sandbox,
                    'mode': mode,
                    'find' : find
                
                }, function(html, status){
                        $("#roominnerwindow").scrollTop(0);
                }).fadeIn(800);
        });        
        
        
   /*****************************************************************************
    *
    *    
    *    
    *    *  PHOTO LIBRARY
    *  
    * 
    * 
    *****************************************************************************/
        $('body').on('click','.sharelink', function()
        {
            $(this).select();
        });
        $('body').on('click','.sharealbum', function()
        {
            $('.sharealbumarea').show();
            $('.sharealbum').hide();
        });
        $('body').on('click','.sharephoto', function()
        {
            $('.sharephotoarea').show();
            $('.sharephoto').hide();
        });
        
        $('body').on('click','.photoselectback', function()
        {
            PanelShow(9);
            
        });
        $('body').on('click','.photoselect', function()
        {
                AbortAjax();
                var target = $(this).data('target');
                var src = $(this).data('src');
                var mode = $(this).data('mode');
                var album = $(this).data('album');
                var filename = $(this).data('filename');
                var alias = $(this).data('alias');
                var caller = $(this).data('caller');
                var page = $(this).data('page');
                var passkey64 = $(this).data('passkey64');
                //album = defaultAlbum;
                if( filename !== "")
                {
                    if(target!==''){
                    
                        $(target).val(alias);
                    }
                    if( src!==''){
                        $(src).attr('src',filename);
                    }
                    if( caller === 'chat' && filename!== ' '){
                        
                        alertify.confirm('Share photo to chat?',function(ok){
                            if( ok ){
                                $(target).val(filename);
                                PanelShow(3);
                                SendChat(passkey64,'',false);
                            } else {
                                $('.chatextraarea').hide();
                                ActiveChat(true,chatinputpasskey);
                                
                            }
                        });
                        return;
                        
                    }
                    if( caller === 'chat'){
                        $('.chatextraarea').hide();
                        ActiveChat(true,chatinputpasskey);
                        
                        PanelShow(3);
                    }
                    if( caller === 'feed'){
                        PanelShow(4);
                    }
                    if( caller === 'share'){
                        PanelShow(16);
                    }
                    if( caller === 'web'){
                        PanelShow(8);
                    }
                    if( caller === 'roomsetup' ){
                        PanelShow(4);
                    }
                    if( caller === 'productsetup' ){
                        PanelShow(4);
                    }
                    if( caller === 'grouptext'){
                        PanelShow(15);
                    }
                    //PanelShow(8);
                    return;
                } else {
                    PanelShow(9);
                }
                if(album === ''){
                    album = defaultAlbum;
                }
                $('#popupwindow').html(LoadingGIF);
                $('#popupwindow').load( rootserver+"photoselect.php",  {
                    'providerid': $('#pid').val(),
                    'target' : target,
                    'src' : src,
                    'mode' : mode,
                    'album' : album,
                    'caller' : caller,
                    'page' : page,
                    'passkey64' : passkey64
                }, function(html, status){
                        $(".mainview").scrollTop(0);
                });
            
        });
        $('body').on('click','.emoji', function()
        {
            AbortAjax();
            var target = $(this).data('target');
            var caller = $(this).data('caller');
            //$(target).emojiPicker({button:true});
            //$(target).emojiPicker('toggle');
                return;
            
        });
                
        
        
        $('body').on('change','.photolibalbumselect', function()
        {
            
                //UpdateTimeStamp();
                AbortAjax();

                var album = $('.photolibalbumselect').val();
                var target = $(this).data('target');
                var mode = $(this).data('mode');
                var src = $(this).data('src');
                var caller = $(this).data('caller');
                defaultAlbum = album;
                PanelShow(9);
                $('#popupwindow').load( rootserver+"photoselect.php?"+timeStamp(),  {
                    'providerid': $('#pid').val(),
                    'album': album,
                    'page': 1,
                    'target': target,
                    'filename': '',
                    'src': src,
                    'caller': caller,
                    'mode': mode,
                    'timestamp' : timeStamp()
                }, function(html, status){
                        $(".mainview").scrollTop(0);
                });
                //Sizing();
            
        });
        /*
        $('body').on('blur','.photolibalbum', function()
        {
                //Sizing();
        });
        */
        $('body').on('click','.photolibalbum', function()
        {
                if( typeof $('#photomenu').menu('instance')==='undefined'){
                

                    photomenu = $('#photomenu').menu().position({
                      my: "left top",
                      at: "left top+20",
                      of: this
                    });
                }
            
                photomenu = $('#photomenu').menu("refresh").position({
                  my: "left top",
                  at: "left top+20",
                  of: this
                });
                return;
        });
        $('body').on('click','#photoalbumitem', function()
        {
                AbortAjax();
                $('#photomenu').hide();
                var album = $(this).data('album');
                defaultAlbum = album;
                PanelShow(13);
                $('#socialwindow').load( rootserver+"photolib.php?"+timeStamp(),  {
                    'providerid': $('#pid').val(),
                    'album': album,
                    'origalbum': album,
                    'page': 1,
                    'rotate': '',
                    'filename': ''
                }, function(html, status){
                        $(".mainview").scrollTop(0);
                });
      
        });
        $('body').on('click','.photolibalbumselect', function()
        {
                if( typeof $('#photomenuselect').menu('instance')==='undefined')
                {

                    $('#photomenuselect').menu().position({
                      my: "left top",
                      at: "left top+20",
                      of: this
                    }).show();
                }
       
                $('#photomenuselect').menu("refresh").position({
                  my: "left top",
                  at: "left top+20",
                  of: this
                }).show();
                return;
        });
        $('body').on('click','#photoalbumitemselect', function()
        {
                //UpdateTimeStamp();
                AbortAjax();

                var album = $(this).data('album');
                var target = $(this).data('target');
                var mode = $(this).data('mode');
                var src = $(this).data('src');
                var caller = $(this).data('caller');
                defaultAlbum = album;
                PanelShow(9);
                $('#popupwindow').load( rootserver+"photoselect.php?"+timeStamp(),  {
                    'providerid': $('#pid').val(),
                    'album': album,
                    'page': 1,
                    'target': target,
                    'filename': '',
                    'src': src,
                    'caller': caller,
                    'mode': mode
                }, function(html, status){
                        $(".mainview").scrollTop(0);
                });
      
        });
        
        $('body').on('click','.textpics', function()
        {
            
            
            AbortAjax();
            
            var album = "TextPics";
            PanelShow(13);
             $('#socialwindow').load( rootserver+"photolib.php",  {
                 'providerid': $('#pid').val(),
                 'album': album,
                 'origalbum': album
             }, function(html, status){
                    $(".mainview").scrollTop(0);
             });
        });
        $('body').on('click','.textphoto', function()
        {
            
            
            AbortAjax();
            PanelShow(12);
            $('#textphotoform').submit();
            
        });
        $('body').on('click','.profileartist', function()
        {
            AbortAjax();
            PanelShow(10);
            $('#functioniframe').prop('src',rootserver+'blank.php');
                
            $('form.profileartistform').prop('target','functioniframe');
            $('form.profileartistform').submit();
        });
        
        $('body').on('click','.photounselect', function()
        {
            $('.photoselectarea').hide();
            $('.photoalbumarea').show();
        });
        
        $('body').on('click','.photolibshare', function()
        {
            AbortAjax();
            
            var filename = $(this).data('filename');
            var page = $(this).data('page');
            var album = $(this).data('album');
            var userid = $(this).data('userid');
            var mode = $(this).data('mode');
                    
            $('#socialwindow').html(LoadingGIF);
            PanelShow(13);
            $('#socialwindow').load( rootserver+"photolibshare.php",  {
                'providerid': $('#pid').val(), 
                'page': page,
                'album': album,
                'filename': filename,
                'userid' : userid,
                'mode' : mode
            }, function(html, status){
            });
        });        
        
        $('body').on('click','.photolibrary', function()
        {
            LastFunc = '';
            AbortAjax();
            
            var filename = $(this).data('filename');
            var page = $(this).data('page');
            var rotate = $(this).data('rotate');
            var save = $(this).data('save');
            var album = $(this).data('album');
            //var album = $('.photolibalbum').val();
            var newalbum = $('.photolib_albumrename').val();
            var roomid = defaultRoomid;
            if(album === ''){
                album = defaultAlbum;
            }
            $('.showrename').hide();
            $('.hiderename').show();
            if($('.photolib_albumrenamenew').val()!==''){
                newalbum = $('.photolib_albumrenamenew').val();
            }
            if( save==='RENAME' || typeof newalbum!=='undefined' && newalbum==='(New)' && save ==='CA' ){
                $('.photolib_albumrename').val("");
                //alert(album);
                $('.showrename').show();
                $('.hiderename').hide();
                $('.photolib_albumrenamenew').val("");
                return;
            }
            var deletefilename = $(this).data('deletefilename');
            if( save ==="D"){
                if(album === '(New)'){
                    album = '';
                }
                
                
                alertify.confirm('Delete Photo?',function(ok){
                    if( ok ){
                    
                        $('#socialwindow').html(LoadingGIF);
                        PanelShow(13);
                        $('#socialwindow').load( rootserver+"photolib.php?"+timeStamp(),  {
                            'providerid': $('#pid').val(), 
                            'page': page,
                            'album': album,
                            'origalbum': album,
                            'filename': filename,
                            'deletefilename': deletefilename,
                            'timestamp' : timeStamp()
                        }, function(html, status){
                        });
                    }
                });
            }
            if( save ==="DA"){
            
                if(album === '(New)'){
                    album = '';
                }
                alertify.confirm('Delete Album '+album+'?',function(ok){
                    if( ok ){
                    
                        $('#socialwindow').html(LoadingGIF);
                        PanelShow(13);
                        $('#socialwindow').load( rootserver+"photolib.php?"+timeStamp(),  {
                            'providerid': $('#pid').val(), 
                            'page': page,
                            'album': album,
                            'origalbum': album,
                            'filename': '',
                            'save': save,
                            'deletefilename': '',
                            'timestamp' : timeStamp()
                        }, function(html, status){
                        });
                    }
                });
            } else {
            
                    if( save!=='A'){
                        $('#socialwindow').html(LoadingGIF);
                    }
                    PanelShow(13);
                    
                    $.ajax({
                        url: rootserver+'photolib.php?'+timeStamp(),
                        context: document.body,
                        type: 'POST',
                        data: 
                         {
                            'providerid': $('#pid').val(),
                            'album': album,
                            'newalbum': newalbum,
                            'origalbum': album,
                            'page': page,
                            'rotate': rotate,
                            'save': save,
                            'filename': filename,
                            'roomid': roomid,
                            'timestamp' : timeStamp()
                         }
                    }).done(function( data, status ) {
                        //alertify.alert(save);
                        $(".mainview").scrollTop(0);
                        
                        if( save!=='A'){
                            $('#socialwindow').hide().html(data).fadeIn(800);
                        } else { 
                            if(mobileversion!=='000' && mobileversion!==''  ){
                                NativeCall("restart");
                                return;
                            }
                            
                            window.location = rootserver1+startupphp+"?s="+source+"&e="+enterprise+"&apn="+apn+"&gcm="+gcm+"&v="+mobileversion;
                            
                            alertify.set({ delay: 2000 });
                            alertify.log("Profile Photo Changed - Restart to View"); 
                            
                        }
                        if(typeof filename!=="undefined" && filename!=='' &&
                           (typeof rotate === "undefined" || rotate==='')){
                            $('.photoalbumarea').hide();
                        }
                        
   
                    });
                
                    
            
            }
            
            
        });
        
   /*****************************************************************************
    *
    *    
    *    
    *    *  SECURITY LIBRARY
    *  
    * 
    * 
    *****************************************************************************/
        $('body').on('click','.homeiot', function()
        {
            var page = $(this).data('page');
            var mode = $(this).data('mode');
            var filter = $(this).data('filter');
            
            $('#socialwindow').html(LoadingGIF);
            PanelShow(8);
            $('#socialwindow').load( rootserver+"iotview.php",  {
                'providerid': $('#pid').val(),
                'page' : page,
                'mode' : mode,
                'filter' : filter,
            }, function(html, status){
                $(".mainview").scrollTop(0);
            });
            
        });
        $('body').on('click','.homeiotdevices', function()
        {
            $('#popupwindow').html(LoadingGIF);
            PanelShow(9);
            $('#popupwindow').load( rootserver+'iotaccess.php',  {
                'providerid': $('#pid').val(),

            }, function(html, status){
                $('#popupwindow').html(html);
            });

            
        });
        $('body').on('click','.homeiotcheck', function()
        {
            var url = $(this).data('url');
            var baseurljson = $(this).data('baseurl')+'/test.json?';
            var baseurl = $(this).data('baseurl');
            
            PanelShow(9);
            $('#popupwindow').load( rootserver+'iotaccess.php',  {
                'providerid': $('#pid').val(),
                'url': baseurl,

            }, function(html, status){
                $('#popupwindow').html(html);
            });
            return;
            
            
            //url = 'https://brax.me';  
            PanelShow(9);
            $('#popupwindow').html(LoadingGIF);
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: baseurljson,
                timeout: 1000,
                success: function(data, textStatus ){
                    
                    PanelShow(9);
                    $('#popupwindow').load( rootserver+'iotaccess.php',  {
                        'providerid': $('#pid').val(),
                        'url': baseurl,

                    }, function(html, status){
                        $('#popupwindow').html(html);
                    });
                    
                },
                fail: function(xhr, textStatus, errorThrown){
                    alertify.alert(textStatus+' Device not accessible. You must be on the same network to access the device.');
                }
            });            
            
            
        });
        $('body').on('click','.homeiotplayer', function()
        {
            AbortAjax();
            PanelShow(28);
            $('#videoiotform').find('#videoiot_url').val( $(this).data('url') );
            
            $('#functioniframe').prop('src',rootserver+'blank.php');
            $('#videoiotform').submit();
        });
        
   /*****************************************************************************
    *
    *    
    *    
    *    *  CASE FILES LIBRARY
    *  
    * 
    * 
    *****************************************************************************/
        $('body').on('click','.caseselect', function()
        {
            var page = $(this).data('page');
            var mode = $(this).data('mode');
            var filtername = $('#casefiltername').val();
            var casename = $('#casename').val();
            var externalid = $('#caseexternalid').val();
            var casenotes = $('#casenotes').val();
            var caseid = $(this).data('caseid');
            var roomid = $(this).data('roomid');
            var userid = $(this).data('providerid');
            
            $('#popupwindow').html(LoadingGIF);
            PanelShow(9);
            $('#popupwindow').load( rootserver+"caseselect.php?"+timeStamp(),  {
                'providerid': $('#pid').val(),
                'page' : page,
                'mode' : mode,
                'caseid' : caseid,
                'userid' : userid,
                'roomid' : roomid,
                'filtername': filtername,
                'casename' : casename,
                'externalid' : externalid,
                'casenotes' : casenotes,
                'timestamp' : timeStamp()
            }, function(html, status){
                    $(".mainview").scrollTop(0);
            });
            
        });
        
        $('body').on('click','.casefiles', function()
        {
            $('#functioniframe').prop('src',rootserver+'blank.php');
            var caseid = $(this).data("caseid");
            if( caseid === ''){
                return;
            }
            
            var page = $(this).data("page");
            var mode = $(this).data("mode");
            var sort = $(this).data("sort");
            var filename = $(this).data("filename");
            var target = $(this).data("target");
            var caller = $(this).data("caller");
            var folder = $(this).data("folder");
            var folderid = $(this).data("folderid");
            var origfilename = $('.filename').val();
            var title = $('.filetitle').val();
            var filtername = $('.filefiltername').val();
            var filterterm = $('.filefilterterm').val();
            
            if( typeof folder === 'undefined' || folder === '')
                folder = '';
            if( mode === 'F')
            {
                folder = $('.case_newfolder').val();
            }
            AbortAjax();
            
            
            $('#popupwindow').html(LoadingGIF);
            if( typeof mode !== 'undefined' && mode === 'D')
            {
                PanelShow(9);
                $('#popupwindow').load( rootserver+"casefiles.php?"+timeStamp(),  {
                    'providerid': $('#pid').val(),
                    'page' : page,
                    'mode' : mode,
                    'sort' : sort,
                    'filename' : filename,
                    'target' : target,
                    'caller' : caller,
                    'folder' : folder,
                    'folderid' : folderid,
                    'filtername': filtername,
                    'filterterm': filterterm,
                    'caseid' : caseid,
                    'timestamp' : timeStamp()
                }, function(html, status){
                        $(".mainview").scrollTop(0);
                });
            }
            else
            {
                PanelShow(9);
                $('#popupwindow').load( rootserver+"casefiles.php?"+timeStamp(),  {
                    'providerid': $('#pid').val(),
                    'page' : page,
                    'mode' : mode,
                    'sort' : sort,
                    'filename' : filename,
                    'target' : target,
                    'caller' : caller,
                    'folder' : folder,
                    'folderid' : folderid,
                    'origfilename' : origfilename,
                    'title' : title,
                    'filtername': filtername,
                    'caseid' : caseid,
                    'timestamp' : timeStamp()
                }, function(html, status){
                        $(".mainview").scrollTop(0);
                });
                
            }
            
            
            return;
        });
        
        $('body').on('click','.casefileselect', function()
        {
            AbortAjax();
            $('#functioniframe').prop('src',rootserver+'blank.php');
            var page = $(this).data("page");
            var sort = $(this).data("sort");
            var folder = $(this).data("folder");
            var folderid = $(this).data("folderid");
            var casefolderid = $(this).data("casefolderid");
            var filename = $(this).data("filename");
            var altfilename = $(this).data("altfilename");
            var target = $(this).data("target");
            var caller = $(this).data("caller");
            var caseid = $(this).data("caseid");
            var mode = $(this).data("mode");
            
            PanelShow(9);
            $('#popupwindow').load( rootserver+"casefiles.php?"+timeStamp(),  {
                'providerid': $('#pid').val(),
                'page' : 1,
                'mode' : 'S',
                'sort' : "",
                'filename' : filename,
                'caseid' : caseid,
                'casefolderid' : casefolderid,
                'timestamp' : timeStamp()
            }, function(html, status){
                    $('#popupwindow').html(html);
                    $(".mainview").scrollTop(0);
            });
                
        });
        $('body').on('click','.casefileselectgroup', function()
        {
            AbortAjax();
            PanelShow(9);
            $('#functioniframe').prop('src',rootserver+'blank.php');
            var flist = new Array();
            var roomfolderid;
            var roomid;
            $('.rowmultiselected').each(function(){
                //var filename = $(this).data("filename");
                casefolderid = $(this).data("roomfolderid");
                caseid = $(this).data("roomid");
                flist.push($(this).data("filename"));
            });

            //convert array to object and then stringify so it works with json_decode
            var filenamelist = JSON.stringify($.extend({},flist));
            $('#popupwindow').load( rootserver+"casefiles.php?"+timeStamp(),  {
                'providerid': $('#pid').val(),
                'page' : 1,
                'mode' : 'S',
                'sort' : "",
                'filename' : '',
                'filenamelist' : filenamelist,
                'caseid' : caseid,
                'casefolderid' : casefolderid,
                'timestamp' : timeStamp()
                }, function(html, status){
                    $('#popupwindow').html(html);
                    //alertify.alert(html);
            });
        });        
   /*****************************************************************************
    *
    *    
    *    
    *    *  FILE LIBRARY
    *  
    * 
    * 
    *****************************************************************************/
        $('body').on('click','.roomfiles', function()
        {
            $('#functioniframe').prop('src',rootserver+'blank.php');
            var roomid = $(this).data("roomid");
            if( roomid == 'All'){
                return;
            }
            
            var page = $(this).data("page");
            var mode = $(this).data("mode");
            var sort = $(this).data("sort");
            var filename = $(this).data("filename");
            var target = $(this).data("target");
            var caller = $(this).data("caller");
            var folder = $(this).data("folder");
            var folderid = $(this).data("folderid");
            var origfilename = $('.filename').val();
            var title = $('.filetitle').val();
            var filtername = $('.filefiltername').val();
            var filterterm = $('.filefilterterm').val();
            
            if( typeof folder === 'undefined' || folder === '')
                folder = '';
            if( mode === 'F')
            {
                folder = $('.room_newfolder').val();
            }
            AbortAjax();
            
            
            $('#popupwindow').html(LoadingGIF);
            if( typeof mode !== 'undefined' && mode === 'D')
            {
                PanelShow(9);
                $('#popupwindow').load( rootserver+"roomfiles.php?"+timeStamp(),  {
                    'providerid': $('#pid').val(),
                    'page' : page,
                    'mode' : mode,
                    'sort' : sort,
                    'filename' : filename,
                    'target' : target,
                    'caller' : caller,
                    'folder' : folder,
                    'folderid' : folderid,
                    'filtername': filtername,
                    'filterterm': filterterm,
                    'roomid' : roomid,
                    'timestamp' : timeStamp()
                }, function(html, status){
                        $(".mainview").scrollTop(0);
                });
            }
            else
            {
                PanelShow(9);
                $('#popupwindow').load( rootserver+"roomfiles.php?"+timeStamp(),  {
                    'providerid': $('#pid').val(),
                    'page' : page,
                    'mode' : mode,
                    'sort' : sort,
                    'filename' : filename,
                    'target' : target,
                    'caller' : caller,
                    'folder' : folder,
                    'folderid' : folderid,
                    'origfilename' : origfilename,
                    'title' : title,
                    'filtername': filtername,
                    'roomid' : roomid,
                    'timestamp' : timeStamp()
                }, function(html, status){
                        $('#popupwindow').html(html);
                        $(".mainview").scrollTop(0);
                });
                
            }
            
            
            return;
        });
        
        $('body').on('click','.roomfileselect', function()
        {
            AbortAjax();
            $('#functioniframe').prop('src',rootserver+'blank.php');
            var page = $(this).data("page");
            var sort = $(this).data("sort");
            var folder = $(this).data("folder");
            var folderid = $(this).data("folderid");
            var roomfolderid = $(this).data("roomfolderid");
            var filename = $(this).data("filename");
            var altfilename = $(this).data("altfilename");
            var target = $(this).data("target");
            var caller = $(this).data("caller");
            var roomid = $(this).data("roomid");
            var mode = $(this).data("mode");
            
            PanelShow(9);
            $('#popupwindow').load( rootserver+"roomfiles.php?"+timeStamp(),  {
                'providerid': $('#pid').val(),
                'page' : 1,
                'mode' : 'S',
                'sort' : "",
                'filename' : filename,
                'roomid' : roomid,
                'roomfolderid' : roomfolderid,
                'timestamp' : timeStamp()
            }, function(html, status){
                    $('#popupwindow').html(html);
                    $(".mainview").scrollTop(0);
            });
                
        });
        $('body').on('click','.roomfileselectgroup', function()
        {
            AbortAjax();
            PanelShow(9);
            $('#functioniframe').prop('src',rootserver+'blank.php');
            var flist = new Array();
            var roomfolderid;
            var roomid;
            $('.rowmultiselected').each(function(){
                //var filename = $(this).data("filename");
                roomfolderid = $(this).data("roomfolderid");
                roomid = $(this).data("roomid");
                flist.push($(this).data("filename"));
            });

            //convert array to object and then stringify so it works with json_decode
            var filenamelist = JSON.stringify($.extend({},flist));
            $('#popupwindow').load( rootserver+"roomfiles.php?"+timeStamp(),  {
                'providerid': $('#pid').val(),
                'page' : 1,
                'mode' : 'S',
                'sort' : "",
                'filename' : '',
                'filenamelist' : filenamelist,
                'roomid' : roomid,
                'roomfolderid' : roomfolderid,
                'timestamp' : timeStamp()
                }, function(html, status){
                    $('#popupwindow').html(html);
                    //alertify.alert(html);
            });
        });
        $('body').on('click','.doclibselectrow', function()
        {
            $(this).parent().parent().addClass('multiselected');
            $(this).addClass('rowmultiselected doclibselectcancel');
            $('.filebatchaction').show();
            
        });
        $('body').on('click','.doclibselectcancel', function()
        {
            $(this).parent().parent().removeClass('multiselected');
            $(this).removeClass('rowmultiselected doclibselectcancel');
            
        });
        $('body').on('click','.doclibselectrowcancel', function()
        {
            $('.doclibfilerow').removeClass('multiselected');
            $('.doclibselectrow').removeClass('rowmultiselected');
            $('.filebatchaction').hide();
            
        });
        
        
        $('body').on('click','.doclibsearch', function()
        {
            if($('.doclibsearcharea').is(':visible'))
            {
                $('.doclibsearcharea').hide();
                
            }
            else
            {
                $('.doclibsearcharea').show();
                $('.filefiltername').focus();
                
            }
        });
        
        
        $('body').on('click','.doclib', function()
        {
            LastFunc = '';
            AbortAjax();
            var mode = $(this).data("mode");
            if( mode === 'BACK'){
                PanelShow(8);
                $('.mainview').scrollTop(filesScrollPos);            
                return;
            }
            
            $('#functioniframe').prop('src',rootserver+'blank.php');
            var page = $(this).data("page");
            var sort = $(this).data("sort");
            var filename = $(this).data("filename");
            var target = $(this).data("target");
            var caller = $(this).data("caller");
            var folder = $(this).data("folder");
            var folderid = $(this).data("folderid");
            var origfilename = $('.filename').val();
            var title = $('.filetitle').val();
            var content = $('#texteditcontent').val();
            var textfilename = $('#textfilename').val();
            var filtername = $('.filefiltername').val();
            var roomid = $(this).data("roomid");
            var roomfolderid = $(this).data("roomfolderid");
            var passkey64 = $(this).data("passkey64");
            var targetemail = '';
            var parentfolder = '';
            if( typeof folder === 'undefined' || folder === ''){
                folder = LastFolder;
            }
            if( typeof filtername === 'undefined' ){
                filtername = '';
            }
            if( mode === 'S'){
            
                folder = $('.filefolder').val();
            }
            if( mode === 'F'){
            
                parentfolder = folder;
                folder = $('.file_newfolder').val();
            }
            if( mode === 'E'){
            
                targetemail = $('.filesendemail').val();
            }
            
            if( mode === 'D'){
            
                origfilename = $(this).data("origfilename");
                alertify.confirm('Delete File '+origfilename+'?',function(ok){
                    if( ok )
                    {
                        PanelShow(8);
                        $('#socialwindow').html(LoadingGIF);
                        $('#socialwindow').load( rootserver+"doclib.php?"+timeStamp(),  {
                            'providerid': $('#pid').val(),
                            'page' : page,
                            'mode' : mode,
                            'sort' : sort,
                            'filename' : filename,
                            'target' : target,
                            'caller' : caller,
                            'folder' : folder,
                            'folderid' : folderid,
                            'filtername': filtername,
                            'passkey64' : passkey64,
                            'timestamp' : timeStamp()
                        }, function(html, status){
                            $('#socialwindow').html(html);
                            $(".mainview").scrollTop(0);
                                
                        });
                    }
                });
                return;
            }
            if( mode === 'L'){
            
                alertify.confirm('Change/Regenerate Shareable Download Link?<br><br>Warning: Old link will no longer be valid.',function(ok){
                    if( ok )
                    {
                        PanelShow(8);
                        $('#socialwindow').html(LoadingGIF);
                        $('#socialwindow').load( rootserver+"doclib.php?"+timeStamp(),  {
                            'providerid': $('#pid').val(),
                            'page' : page,
                            'mode' : mode,
                            'sort' : sort,
                            'filename' : filename,
                            'target' : target,
                            'caller' : caller,
                            'folder' : folder,
                            'folderid' : folderid,
                            'filtername': filtername,
                            'passkey64' : passkey64,
                            'timestamp' : timeStamp()
                        }, function(html, status){
                                $(".mainview").scrollTop(0);
                        });
                    }
                });
            } else
            if( mode === 'DF'){
            
                alertify.confirm('Delete entire folder: '+folder+'?',function(ok){
                    if( ok )
                    {
                        PanelShow(8);
                        $('#socialwindow').html(LoadingGIF);
                        $('#socialwindow').load( rootserver+"doclib.php?"+timeStamp(),  {
                            'providerid': $('#pid').val(),
                            'page' : page,
                            'mode' : mode,
                            'sort' : sort,
                            'filename' : filename,
                            'target' : target,
                            'caller' : caller,
                            'folder' : folder,
                            'folderid' : folderid,
                            'filtername': filtername,
                            'passkey64' : passkey64,
                            'timestamp' : timeStamp()
                        }, function(html, status){
                                $(".mainview").scrollTop(0);
                            if(filtername!==''){
                                $('.doclibsearcharea').show();
                            }
                                
                        });
                    }
                });
                return;
            } else {
                if(mode !== 'TEXTEDIT'){
                    PanelShow(8);
                } else {
                    alertify.set({ delay: 2000 });
                    alertify.log("Saved"); 
                }
                $('#socialwindow').html(LoadingGIF);
                $('#socialwindow').hide().load( rootserver+"doclib.php",  {
                    'providerid': $('#pid').val(),
                    'page' : page,
                    'mode' : mode,
                    'sort' : sort,
                    'filename' : filename,
                    'target' : target,
                    'caller' : caller,
                    'folder' : folder,
                    'folderid' : folderid,
                    'parentfolder' : parentfolder,
                    'origfilename' : origfilename,
                    'title' : title,
                    'filtername': filtername,
                    'roomid': roomid,
                    'roomfolderid': roomfolderid,
                    'targetemail': targetemail,
                    'passkey64' : passkey64,
                    'content' : content,
                    'textfilename' : textfilename,
                    'timestamp' : timeStamp()
                }, function(html, status){
                        $(".mainview").scrollTop(0);
                        if(filtername!==''){
                            $('.doclibsearcharea').show();
                        }
                }).fadeIn(800);
                
            }
            
            
            return;
        });
        
        $('body').on('click','.doclib1', function()
        {
                
            filesScrollPos = $('.mainview').scrollTop();            
            AbortAjax();
            $('#functioniframe').prop('src',rootserver+'blank.php');
            var page = $(this).data("page");
            var sort = $(this).data("sort");
            var folder = $(this).data("folder");
            var folderid = $(this).data("folderid");
            var roomfolderid = $(this).data("roomfolderid");
            var filename = $(this).data("filename");
            var altfilename = $(this).data("altfilename");
            var target = $(this).data("target");
            var caller = $(this).data("caller");
            var roomid = $(this).data("roomid");
            var mode = $(this).data("mode");
            var passkey64 = $(this).data("passkey64");
            
            $('#popupwindow').html(LoadingGIF);
            PanelShow(9);
            $('#popupwindow').load( rootserver+"doclib.php?"+timeStamp(),  {
                'providerid': $('#pid').val(),
                'page' : page,
                'mode' : mode,
                'sort' : sort,
                'filename' : filename,
                'altfilename' : altfilename,
                'target' : target,
                'caller' : caller,
                'folder' : folder,
                'roomid' : roomid,
                'roomfolderid' : roomfolderid,
                'folderid' : folderid,
                'passkey64' : passkey64,
                'timestamp' : timeStamp()
            }, function(html, status){
                $('#popupwindow').html(html);
                $(".mainview").scrollTop(0);
            });
            
            return;
        });
        
        $('body').on('change','.doclibfolder', function()
        {
            AbortAjax();
            $('#functioniframe').prop('src',rootserver+'blank.php');
            var page = $(this).data("page");
            var sort = $(this).data("sort");
            var target = $(this).data("target");
            var caller = $(this).data("caller");
            var folder = $(this).val();
            var folderid = $(this).data("folderid");
            var passkey64 = $(this).data('passkey64');
            LastFolder = folder;
            $('#popupwindow').html(LoadingGIF);
            
            PanelShow(9);
            $('#popupwindow').load( rootserver+"doclib.php?"+timeStamp(),  {
                'providerid': $('#pid').val(),
                'folder': folder,
                'folderid': folderid,
                'page' : page,
                'mode' : '',
                'sort' : sort,
                'filename' : '',
                'target' : target,
                'caller' : caller,
                'passkey64' : passkey64,
                'timestamp' : timeStamp()
            }, function(html, status){
                    $('#popupwindow').html(html);
                    $(".mainview").scrollTop(0);
            });

            return;
        });
        $('body').on('click','.doclibchangefolder', function()
        {
            AbortAjax();
            $('#functioniframe').prop('src',rootserver+'blank.php');
            var page = $(this).data("page");
            var sort = $(this).data("sort");
            var folder = $(this).data("folder");
            var folderid = $(this).data("folderid");
            var filename = $(this).data("filename");
            var altfilename = $(this).data("altfilename");
            var target = $(this).data("target");
            var caller = $(this).data("caller");
            var roomid = $(this).data("roomid");
            var passkey64 = $(this).data('passkey64');
            
            
            $('#popupwindow').html(LoadingGIF);
            PanelShow(9);
            $('#popupwindow').load( rootserver+"doclib.php?"+timeStamp(),  {
                'providerid': $('#pid').val(),
                'page' : page,
                'mode' : 'CF',
                'sort' : sort,
                'filename' : filename,
                'altfilename' : altfilename,
                'target' : target,
                'caller' : caller,
                'folder' : folder,
                'folderid' : folderid,
                'passkey64' : passkey64,
                'timestamp' : timeStamp()
            }, function(html, status){
                    $('#popupwindow').html(html);
                    $(".mainview").scrollTop(0);
            });
            
            return;
        });        
        $('body').on('click','.fileedit', function()
        {
            if( $('.file_editarea').is(":visible") )
            {
                $('.file_editarea').hide();
                $('.file_not_editarea').show();
                $('.fileeditor').show();
            }
            else
            {
                $('.file_editarea').show();
                $('.file_not_editarea').hide();
                $('.fileeditor').hide();
            }
        });
        $('body').on('click','.filesend', function()
        {
            if( $('.file_sendarea').is(":visible") )
            {
                $('.file_sendarea').hide();
            }
            else
            {
                $('.file_sendarea').show();
            }
        });
        $('body').on('click','.filefolderoptions', function()
        {
            if( $('.filefolderoptionsarea').is(":visible") )
            {
                $('.filefolderoptionsarea').hide();
                $('.doclibsearcharea').show();
            }
            else
            {
                $('.filefolderoptionsarea').show();
                $('.doclibsearcharea').hide();
            }
        });
        $('body').on('click','.fileselect', function()
        {
                AbortAjax();
                PanelShow(9);
                var target = $(this).data('target');
                var page = $(this).data('page');
                var sort = $(this).data('sort');
                var folder = $(this).data('folder');
                var folderid = $(this).data('folderid');
                var roomfolderid = $(this).data('roomfolderid');
                var link = $(this).data('link');
                var caller = $(this).data('caller');
                var roomid = $(this).data('roomid');
                var passkey64 = $(this).data('passkey64');
                if( link !== ""){
                
                    if(target!==''){
                        $(target).val(link);
                    }
                    if( caller==='grouptext'){
                        PanelShow(15);
                    }
                    if( caller==='room'){
                        PanelShow(4);
                    }
                    if( caller==='chat'){
                        PanelShow(3);
                        SendChat(passkey64,'',false);
                        ScrollChat();
                    }
                    return;
                }
                if( caller==='chat'){
                    //Cancel Select
                    $('.chatextraarea').hide();
                    ActiveChat(true,chatinputpasskey);
                }
                
                $('#popupwindow').html(LoadingGIF);
                $('#popupwindow').load( rootserver+"doclib.php?"+timeStamp(),  {
                    'providerid': $('#pid').val(),
                    'target' : target,
                    'caller' : caller,
                    'page' : page,
                    'sort' : sort,
                    'folder' : folder,
                    'folderid' : folderid,
                    'roomfolderid' : roomfolderid,
                    'roomid': roomid,
                    'passkey64': passkey64,
                    'timestamp' : timeStamp()
                }, function(html, status){
                    PanelShow(9);
                    $('#popupwindow').html(html);
                    //$(".mainview").scrollTop(0);
                });
            
        });
        
       $('body').on('click','.shareitclose', function()
        {
            $('#shareitwindow').hide();
            $('#socialwindow').show();
            
        });

    
    
       $('body').on('click','.shareitbutton', function()
        {
            var filename = $(this).data('filename');
            var page = $(this).data('page');
            var sharetype = $(this).data('sharetype');
            var platform = $(this).data('platform');
            var privateflag = $(this).data('private');
            var alias = $(this).data('alias');
            
            if( typeof platform !=='undefined' && platform === 'X'){
            
                var directlink = $(this).data('directlink');
                var linkmsg = "<b>Direct Link</b><br><br>\
                    Caution: This is a direct link to the photo or album. This is safe \
                    only for sharing inside the App with trusted parties. Cut and paste the link for \
                    a direct share of the photo for Rooms or Chat. Do not post or send this\
                    publicly. Use a Share instead.<br><br><input type='text' value='"+directlink+"'/>";
                alertify.alert(linkmsg);
                return;
            }
            AbortAjax();
            
            var proxy = $('#shareit_proxy').val();
            var shareto = $('#shareit_shareto').val();
            var sharetitle = $('#shareit_sharetitle').val();
            var shareopentitle = $('#shareit_shareopentitle').val();
            var expire = $('#shareit_expire').val();
            
            //Public Post
            if( typeof alias !=='undefined' && alias!=='' ){
            
                proxy = alias;
            }
            
            var mode = $(this).data('mode');
            if( typeof mode !=='undefined' && mode!=='S' ){
            
                $('#shareitwindow').load( rootserver+"shareit.php?"+timeStamp(), 
                { 
                       'share': filename,
                       'sharetype': sharetype,
                       'platform': platform,
                       'proxy': proxy,
                       'shareto': shareto,
                       'sharetitle': sharetitle,
                       'shareopentitle': shareopentitle,
                       'expire': expire,
                       'mode': mode,
                       'page': page,
                       'private': privateflag,
                       'timestamp' : timeStamp()
                }, function(){
                    PanelShow(16);
                });
            }
            if( typeof mode !=='undefined' && mode==='S' ){
            
                var proxydefault = "N";
                if($('#proxy1').is(":checked"))
                    proxydefault = "Y";

                $.ajax({
                    url: rootserver+'shareout.php?'+timeStamp(),
                    context: document.body,
                    type: 'POST',
                    data: 
                     {
                       'share': filename,
                       'sharetype': sharetype,
                       'platform': platform,
                       'proxy': proxy,
                       'proxydefault': proxydefault,
                       'shareto': shareto,
                       'sharetitle': sharetitle,
                       'shareopentitle': shareopentitle,
                       'expire': expire,
                       'mode': mode,
                       'page': page,
                       'private': privateflag,
                       'timestamp' : timeStamp()
                   }
                }).done( function(data){
                    PanelShow(16);
               
                    $('#shareitwindow').html(data);
                    
                });
            }
        });
        
        
       $('body').on('click','.shareout', function(event)
        {
            event.preventDefault();

            var filename = $(this).data('filename');
            var platform = $(this).data('platform');
            var alias = $(this).data('alias');
            
            
            var proxy = $('#shareit_proxy').val();
            var shareto = $('#shareit_shareto').val();
            var sharetitle = $('#shareit_sharetitle').val();
            var shareopentitle = $('#shareit_shareopentitle').val();
            var expire = $('#shareit_expire').val();
            AbortAjax();
            alertify.alert(platform);

            if( platform === 'F' ){
            
                $.ajax({
                    url: rootserver+'shareout.php?'+timeStamp(),
                    context: document.body,
                    type: 'POST',
                    data: 
                     {
                           'share': filename,
                           'platform': platform,
                           'proxy': alias,
                           'shareto': shareto,
                           'sharetitle': sharetitle,
                           'shareopentitle': shareopentitle,
                           'expire': expire
                     }
                }).done(function( data, status ) {
                    var msg = jQuery.parseJSON(data);
                    window.open( msg.url,"_blank");
                });
            } else {
            
                $.ajax({
                    url: rootserver+'shareout.php?'+timeStamp(),
                    context: document.body,
                    type: 'POST',
                    data: 
                     {
                           'share': filename,
                           'platform': platform,
                           'proxy': alias,
                           'shareto': shareto,
                           'sharetitle': sharetitle,
                           'shareopentitle': shareopentitle,
                           'expire': expire
                     }
                });
            }           
        });
        
       $('body').on('click','.setproxy', function()
        {
            var proxydefault = $(this).data('proxydefault');
            
            $.ajax({
                url: rootserver+'shareout.php?'+timeStamp(),
                context: document.body,
                type: 'POST',
                data: 
                 {
                       'proxydefault': proxydefault,
                       'mode': 'P'
                 }
             });
        });
        
        
       $('body').on('click','.noproxy', function()
        {
            var target = $(this).data('target');
            var src = $(this).data('src');
            var alias = $(this).data('alias');
            var link = $(this).data('link');
            
            $(target).val(alias);
            $(src).attr('src',link);

        });
        
       $('body').on('click','.sharepostbutton', function()
        {
            if( xhr && xhr.readyState !== 4){
                xhr.abort();
            }

            var share = $(this).data('shareid');
            var sharetype = $(this).data('sharetype');
            var proxy = $(this).data('proxy');
            $('#shareitwindow').load( rootserver+"sharepost.php?"+timeStamp(), 
                     { 
                        'share': share,
                        'sharetype': sharetype,
                        'proxy': proxy,
                        'timestamp' : timeStamp()
                     }, function(data, status) {
                     if( status=="success")
                     {
                     }

             });
            
        });
        $('body').on('click','.sharedeletebutton', function()
        {
            if( xhr && xhr.readyState !== 4){
                xhr.abort();
            }

            var setid = $(this).data('setid');
            $('#socialwindow').load( rootserver+"photolib.php?"+timeStamp(), 
                { 
                   'setid': setid,
                   'save': 'U',
                   'timestamp' : timeStamp()
                }, function(data, status) {
                    if( status=="success"){
                    }

             });
            
        });
        $('body').on('click','.shareproxybutton', function()
        {
            if( xhr && xhr.readyState !== 4){
                xhr.abort();
            }
            //alertify.alert("Changed Proxy Photo to be Used for Private Shares<br><br>Takes effect only for future shares.");
            var filename = $(this).data('filename');
            var album = $(this).data('album');
            var page = $(this).data('page');
            $('#socialwindow').load( rootserver+"photolib.php?"+timeStamp(), 
                { 
                  'filename': filename,
                  'album': album,
                  'origalbum': album,
                  'page': page,
                  'save': 'P',
                  'timestamp' : timeStamp()
                }, function(data, status) {
                     if( status=="success"){
                     }

             });
            
        });
        $('body').on('click','.renamealbum', function()
        {
            if( $('.photolibalbum').val()!='All'){
            
                $('.showrename').show();
                $('.photolib_albumrename').val("");
            } else {
                alertify.set({ delay: 2000 });
                alertify.log("Select an album to rename first");
            }
        });
        $('body').on('click','.photochange', function()
        {
            AbortAjax();
            
            var filename = $(this).data('filename');
            var owner = $(this).data('owner');
            var title = $('#photoedit_title').val();
            var comment = $('#photoedit_comment').val();
            var album = $('.photolib_editalbum').val();
            var origalbum = $(this).data('album');
            var page = $(this).data('page');
            //Add New Album
            if( $('#photoedit_album').val()!==''){
                album = $('#photoedit_album').val();
            }
            if(album == '(New)'){
                $('.showrename').show();
                $('.hiderename').hide();
                exit();
            }
            
            $.ajax({
                url: rootserver+'photolib.php?'+timeStamp(),
                context: document.body,
                type: 'POST',
                data: 
                 {
                   'filename': filename,
                   'album': album,
                   'origalbum': origalbum,
                   'owner': owner,
                   'title': title,
                   'comment': comment,
                   'save'    : 'C',
                   'page' : page,
                   'timestamp' : timeStamp()
                 }
            }).done(function( data, status ) {
                //alert(data);
                alertify.set({ delay: 2000 });
                alertify.log("Photo Info Changed"); 
                if(data!==''){
                    PanelShow(13);
                    $('#socialwindow').html(data);
                    $(".mainview").scrollTop(0);
                }
                //alertify.alert('Photo info changed');
            })
             
             
            
        });
        $('body').on('click','.photogotoalbum', function()
        {
            AbortAjax();
            
            var filename = $(this).data('filename');
            var album = $(this).data('album');
            //Add New Album
            
            $.ajax({
                url: rootserver+'photolib.php?'+timeStamp(),
                context: document.body,
                type: 'POST',
                data: 
                 {
                   'filename': filename,
                   'album': album,
                   'save'    : '',
                   'timestamp' : timeStamp()
                 }
            }).done(function( data, status ) {
                //alert(data);
                PanelShow(13);
                $('#socialwindow').html(data);
                $(".mainview").scrollTop(0);
                //alertify.alert('Photo info changed');
            })
             
             
            
        });
        
        $('body').on('click','.newemailshare', function()
        {
                LoadingShowMessage1(true);
                PanelShow(20);
                
                var publicshare = $(this).data('publicshare');
                var directshare = $(this).data('directshare');
                var title = $(this).data('title');
                html = "<div><a href='"+publicshare+"'><img src='"+directshare+"' \><div><b>"+title+"</b></div></a></div>";

                $('#sharearea').html(html);
                $('form.newemail').prop('target','functioniframe');
                
                $('#sharetext').val(B64.encode( $('#sharearea').html() ) );
                
                $('.newemail').children('.imapno').val(ImapFlag);
                $('.newemail').submit();
        });

        

   /*****************************************************************************
    *
    *    
    *    
    *    *  EMAIL / IMAP
    *  
    * 
    * 
    *****************************************************************************/
        $('body').on('click','.selectemaillist', function()
        {
                LastFunc = '';
                PanelShow(9);
                AbortAjax();
                $('#popupwindow').html(LoadingGIF);
                
                $('#popupwindow').load( rootserver+"emaillist.php?"+timeStamp(),  {
                    'providerid': $('#pid').val(),
                    'timestamp' : timeStamp()
                }, function(html, status){
                    if( html!==""){
                    
                        PanelShow(9);
                    }

                });
            
        });
        $('body').on('click','.imapmovebutton', function()
        {
            $('#imapmovemenu').show();
            lastloadmsg = this;
            move_uid = $(this).data('uuid');
            move_folder = $(this).data('folder');
            if( typeof $('#imapmovemenu').menu('instance')==='undefined'){
            
                $('#imapmovemenu').menu().position({
                  my: "right top",
                  at: "left top",
                  of: this
                });             
            }
            $('#imapmovemenu').menu("refresh").position({
              my: "right top",
              at: "left top",
              of: this
            });                
                
        });

        
        $('body').on('click','.imapmovemenu', function(){
            
            var trueparent = lastloadmsg;
            var move_newfolder = $(this).data('folder');
            if(move_folder.toLowerCase()!==move_newfolder.toLowerCase() ){
                $(trueparent).parents('tr.messages').first().hide();
            $('#imapmovemenu').hide();

            $('#status').load( rootserver+"imapflag.php", 
                 { 'providerid': $('#pid').val(), 
                   'loginid': $('#loginid').val(), 
                   'uuid' : move_uid,
                   'imap' : ImapFlag,
                   'folder' : move_folder,
                   'newfolder' : move_newfolder,
                   'flag' : 'M'
                 },function(status){
                     
                 });
            }
            
        });

        
        
        $('body').on('click','.imapbutton', function()
        {
                LastFunc = '';
            
                ImapName = $(this).data("name");
                ImapFlag = $(this).data("imap");
                ImapFolder = $(this).data("folder");
                var subfolders = $(this).data("subfolders");
                
                
                $('.imapbutton').removeClass("divitem_sel").addClass("divitem_unsel");
                $('#received').removeClass('divitem_sel').addClass('divitem_unsel');
                $('#sent').removeClass('divitem_sel').addClass('divitem_unsel');
                
                $(this).removeClass("divitem_unsel").addClass("divitem_sel");
            
                if( subfolders === 'Y'){
                
                    GetFolders();
                }
                ChangeImap();
                if( ImapFolder!='INBOX')
                {
                    RefreshMessageList( ImapFolder, ImapFlag, FAST, 0);
                    //$('#status').html("");
                    alertify.set({ delay: 5000 });
                    alertify.log("Please Wait while the Folder is Synced"); 
                    
                }
                

        });
        $('body').on('click','#viewsubfolders', function()
        {
            ImapFlag = $(this).data("imap");
            GetFolders();
        });


        
        $('#showmessage1').contents().on('click',"a", function (event) {
            var href = $(this).attr("href");
            
            if( href.indexOf("#")!==0){
           
               $(this).prop("target","_blank");
            }
            else {
                event.preventDefault();
            }
        });

        $('body').on('click','td.messagesMsg', function() 
        {
        
            if( $(this).children('.shortText').is(":visible")){
            
                fulltext = $(this).children('.fullText').text();
                fullaction = $(this).children('.actionitem').html();
                msguuid = $(this).children('.uuid').html();
                $(this).children('.shortText').children('.unreadicon').hide();
                $(this).children('.shortText').children('.unreadstatus').removeClass('unread');

                if(ImapFlag!=0 ){
                

                    $('#currentmessage').text(B64.encode(fulltext));
                    //alertify.alert($('#currentmessage').text());

                    //$('#showmessage1').contents().find('html').html(B64.decode(fulltext));
                    $('#showmessage1').contents().find('html').html(linkify(fulltext));
                    $('.actionarea').html(fullaction);


                    PanelShow(2);


                    $('#status').load( rootserver+"imapflag.php?"+timeStamp(), 
                         { 'providerid': $('#pid').val(), 
                           'loginid': $('#loginid').val(), 
                           'uuid' : msguuid,
                           'imap' : ImapFlag,
                           'flag' : 'R',
                           'timestamp' : timeStamp()
                         });
                 } else {
                    $('#currentmessage').text(btoa(fulltext));
                    //alertify.alert($('#currentmessage').text());

                    $('#showmessage1').contents().find('html').html(fulltext);
                    $('.actionarea').html(fullaction);
                    
                    PanelShow(2);
                    
                    var sessionid = $('#showmessage1').contents().find('html').find('.sessionid').text();
                    var party = $('#showmessage1').contents().find('html').find('.party').text();
                    
                    $.ajax({
                        url: rootserver+'markasread.php?'+timeStamp(),
                        context: document.body,
                        type: 'POST',
                        data: 
                         { 'providerid': $('#pid').val(), 
                           'sessionid' : sessionid,
                           'party' : party,
                           'timestamp' : timeStamp()
                         } });


                 }

            }
        });
        $('body').on('click','.loadmsg', function() 
        {
            AbortAjax();
            msguuid = $(this).children('.uuid').html();
            thisparent = this;
            lastloadmsg = this;
            $('.mainview').scrollTop(0);
            
                $.ajax({
                    url: rootserver+'imapload.php',
                    context: document.body,
                    type: 'POST',
                    data: 
                     { 'providerid': $('#pid').val(), 
                       'imap' : ImapFlag,
                       'folder' : ImapFolder,
                       'uuid' : msguuid
                     }

                }).done(function( data, status ) {
                    var msg = jQuery.parseJSON(data);
                    $('#showmessage1').contents().find('html').html(B64.decode(msg.body));

                    //$('#showmessage1').contents().find('html').html(cleanHTMLfromMS(htmlUnEscape(msg.body)));
                    $('.actionarea').html( htmlUnEscape(msg.action ));
                    $('.actionarea').show();

                    //Base64 encoded
                    $('#currentmessage').text(msg.body );
                    $('#showmessage1').contents().on('click',"a", function (event) {
                        var href = $(this).attr("href");

                       if( href.indexOf("#")!==0){
                       
                           $(this).prop("target","_blank");
                       }
                       else
                            event.preventDefault();
                    });


                }).fail(function(xhrobj, status, error){
                    $('#showmessage1').contents().find('html').html(xhrobj.statusText+" - "+status+" - "+error);
                });
                
            




        });


        
            

        $('body').on('click','.imapdownload', function(){
                $(this).parent().submit();
        });
        
        $('body').on('click','.imapdeletebutton1', function(){
            var trueparent = lastloadmsg;
            $(trueparent).parents('tr.messages').hide();
            $(trueparent).parent('.deletemsg').submit();
            PanelShow(1);

            $(this).parent('form').submit();
        });
        
        
        $('body').on('click','.imapdeletebutton', function(){
            var trueparent = this;
            $(trueparent).parents('tr.messages').hide();
            $(trueparent).parent('.deletemsg').submit();

            $(this).parent('form').submit();
        });
        $('body').on('click','.deletebutton', function(){
            
            var trueparent = this;
            alertify.confirm('Delete Message?',function(ok){
                if( ok ){
                
                    $('.lastrefresh').val("Deletions Made");
                    $(trueparent).parents('tr.messages').hide();
                    //$('#alert').html("Refresh Needed - Deletions - Pending");
                    $(trueparent).parent('.deletemsg').submit();
                }
            });
            
        })
        $('body').on('click','#newmessage', function()
        {
                AbortAjax();
                LoadingShowMessage1(true);
                PanelShow(21);
                $('form.newmsg').prop('target','functioniframe');
                $('.newmsg').submit();
            
        });
        $('body').on('click','#getmessagelist', function()
        {
                RefreshMessageList( ImapFolder, ImapFlag, FAST, 0);
                $('#status').html("");
            
        });
        $('body').on('click','#newemail', function()
        {
                LoadingShowMessage1(true);
                PanelShow(21);
                
                AbortAjax();
                $('form.newemail').prop('target','functioniframe');
                
                $('#sharetext').val("");
                
                $('.newemail').children('.imapno').val(ImapFlag);
                $('.newemail').submit();
        });
        $('body').on('click','#searchbutton', function()
        {
                if($('.searchcriteria').is(":visible")){
                
                    $('.searchcriteria').hide();
                    $('.searchfilter').val("");
                }
                else
                    $('.searchcriteria').show();
        });
        $('body').on('click','#start', function()
        {
                $('#page').text("1");
                RefreshMessageList( ImapFolder, ImapFlag, FAST, 1);
        });
        $('body').on('click','#prev', function()
        {
                if( parseInt( $('#page').text(), 10 )> 1){
                
                    CurPage = parseInt($('#page').text(),10)-1;
                    $('#page').text(CurPage );
                }
                RefreshMessageList( ImapFolder, ImapFlag, FAST, 1);
        });
        $('body').on('click','#next', function()
        {
                CurPage = parseInt( $('#page').text(),10 )+1;
                $('#page').text(CurPage);
                RefreshMessageList( ImapFolder, ImapFlag, FAST, 2);
        });
        $('body').on('click','#hidemessage', function()
        {
                if( xhrload && xhrload.readyState !== 4){
                    xhrload.abort();
                }
                PanelShow(1);
        });
        $('body').on('click','#replybutton', function()
        {
                LoadingShowMessage1();
                PanelShow(20);
                
                $(this).parent('form').prop('target','functioniframe');
                $(this).parent('form').submit();
        });
        $('body').on('click','#imapreplyemailbutton', function()
        {
                LoadingShowMessage1();
                PanelShow(20);
                
                $(this).parents('form').prop('target','functioniframe');
                $(this).parents('form').children('.imapno').val(ImapFlag);
                $(this).parents('form').children(".imaporiginaltext").val($('#currentmessage').text());
                $(this).parents('form').submit();
        });
        $('body').on('click','#imapreplybutton', function()
        {
                LoadingShowMessage1();
                PanelShow(20);
                
                $('form.imapreply').prop('target','functioniframe');
                //$('form.newemail').prop('action','newemail-frame.php');
                //$('form.newemail').prop("target","functioniframe");
                
                //$('.newemail').children('.imapno').val(ImapFlag);
                $('form.imapreply').children(".imaporiginaltext").val($('#currentmessage').text());
                $('form.imapreply').submit();
        });
        $('body').on('click','#printbutton', function()
        {
                PrintIt();
        });
        
        $('body').on('click','.printbutton2',function(){
               //alert('Print');
                $("#orderview").focus();
                //$("#orderview").print();
                $("body #orderview").print();
        });
        
        $('body').on('click','#deletebutton', function()
        {
                var trueparent = this;
                alertify.confirm('Delete Message?',function(ok){
                    if( ok ){
                    
                        $('.lastrefresh').val("Deletions Made");
                        $(trueparent).parents('tr.messages').hide();
                        //$('#alert').html("Refresh Needed - Deletions - Pending");
                        $(trueparent).parent('.deletemsg').submit();
                    }
                });
        });
        $('body').on('click','#filebutton', function()
        {
                $(this).parent('form').submit();
        });
        

        $('body').on('click','.divbutton2', function()
        {
            if( $(this).data('button')=='imapdeleteundobutton'){
            
                alertify.alert('Deletions since last refresh will be reversed. Refresh to redisplay.');
                var trueparent = this;
                $(trueparent).parents('tr.messages').hide();
                $(trueparent).parent('.deletemsg').submit();
                
                $(this).parent('form').submit();
            }
        
        });
        


 
        $('body').on('click','#resentbutton', function()
        {
                $(this).parent('form').submit();
        });
        
        


   /*****************************************************************************
    *
    *    
    *    
    *    *  SECURE MESSAGES
    *  
    * 
    * 
    *****************************************************************************/


        $('#changekeygroup').hide();
        $('#updatekey').click( function() 
        {
           if( $('newkey').val()!=''){
           
               
               if($('#newkey').val()!=''){
               
                    if($('#challenge').val()==''){
                    
                        alert('Missing Challenge Question');
                        return;
                    }
               

                    $('#alert').load( rootserver+"sql_changekey.php", 
                             { 'providerid': $('#pid').val(), 
                               'sessionid': $('#sessionid').val(), 
                               'recipientname': $('#recipientname').val(), 
                               'responsekey' : $('#newkey').val(),
                               'challenge' : $('#challenge').val(),
                               'timestamp' : timeStamp()
                             }, function(data, status) {
                             if( status=="success")
                             {
                                    $('#changekeygroup').hide();
                                    $('#newkey').val('');
                                    RefreshMessageList(ImapFolder, ImapFlag, true, 0);
                             }

                     });
               }
           }
        });
        $('#nochangekey').click( function() 
        {
            $('#changekeygroup').hide();
        });
        
        
        $('body').on('click','.collection', function()
        {
                if(xhr && xhr.readyState !== 4){
                    xhr.abort();
                }
            PanelShow(20);
            $('#collectionform').submit();
            
        });
        
   /*****************************************************************************
    *
    *    
    *    
    *    *  TEXTING /CHAT
    *  
    * 
    * 
    *****************************************************************************/
        
        
        
        $('body').on('click','.chatinvite', function()
        {
            ToggleChatShow = false;
            
            var name='';
            var email='';
            var sms='';
            var handle='';
            var passkey64 ='';
            var recipientid = '';
            var roomid='';
            var radiostation='';
            
            var mode = $(this).data('mode');
            var techsupport = $(this).data('techsupport');
            if( mode === 'S'){
            
                recipientid = $(this).data("providerid");
                name = $(this).data('name');
                handle = $(this).data('handle');
                email = $('#invitechatemail').val();
                roomid = $(this).data('roomid');
                radiostation = $(this).data('radiostation');
            }
            if( mode === ''){

                techsupport = '';
                name = $('#invitechatname').val();
                email = $('#invitechatemail').val();
                sms = $('#invitechatsms').val();
                handle = $('#invitechathandle').val();
            }
            if( typeof handle === "undefined"){
                handle = "";
            }
            if( typeof email === "undefined"){
                email = "";
            }
            if( typeof sms === "undefined"){
                sms = "";
            }
            
            if(mode === 'S' && recipientid === ''){
                alertify.alert('Chat not permitted');
                return;
            }
            if( mode !== 'S' && (name === '' || (email ==='' && sms === '' && handle ==='')) ){
                alertify.alert('Missing required info (Name / Handle / Email / Sms)');
                return;
            }
            if( mode !== 'S' && handle.indexOf("@") === -1 && handle !== "" )
            {
                alertify.alert('Invalid @handle. Missing @');
                return;
            }
            
            AbortAjax();

            PanelShow(14);
            $('#chatmessage').val("");
            $('#socialwindow').scrollTop(0);
            $('#socialwindow').load( rootserver+"chatkey.php",  {
                
                'recipientid' : recipientid,
                'name' : name,
                'handle' : handle,
                'email' : email,
                'sms' : sms,
                'techsupport' : techsupport,
                'mode' : '',
                'radiostation' : radiostation,
                'roomid' : roomid
            }).done(function(data, status){
                alert( data );
                
            });
                
            
        });
        /* Initialize New E2E Secure Chat */
        $('body').on('click','.setchatpasskey', function()
        {
            
            var recipientid = $(this).data('recipientid');
            var handle = $(this).data('handle');
            var mode = $(this).data('mode');
            var techsupport = $(this).data('techsupport');
            var radiostation = $(this).data('radiostation');
            var title = $('#chattitle').val();
            var email = $(this).data('email');
            var name = $(this).data('name');
            var sms = $(this).data('sms');
            var passkey = $('#chatpasskey').val();
            var lifespan = $('#chatlifespan').val();
            var roomid = $(this).data('roomid');
            
            AbortAjax();
            $.ajax({
                url: rootserver+'chatinvite.php',
                context: document.body,
                type: 'POST',
                data: 
                { 'providerid': $('#pid').val(),
                   'recipientid': recipientid,
                   'passkey' : passkey,
                   'title' : title,
                   'email' : email,
                   'name' : name,
                   'sms' : sms,
                   'handle': handle,
                   'mode' : '',
                   'roomid' : roomid,
                   'techsupport' : techsupport,
                   'radiostation' : radiostation,
                   'lifespan' : lifespan
                }

            }).done(function( data, status ) {
                PanelShow(3);
                var msg = jQuery.parseJSON(data);
                if( msg.alert!=='C' && msg.msg!==''){

                    alertify.set({ delay: 2000 });
                    alertify.log(msg.msg);
                    $('#trigger_selectchat').trigger('click');
                } else
                if( msg.chatid > 0 ){

                    $('.suspendchatrefresh').hide();
                    $('.chatsendarea').show();
                    $('#endchatbutton').show();
                    PanelShow(3);

                    ChatId = msg.chatid;
                    PingCount = 0;
                    ChatTraffic = 0;
                    ActiveChat(true,msg.passkey);
                }
            });
            
        });
        /* Open Existing E2E Secure Chat */
        $('body').on('click','.usechatpasskey', function()
        {
               $('.chatwindow').html(LoadingGIF);
               PanelShow(3);
                PingCount = 0;
                ChatTraffic = 0;
                ActiveChat(true, $('#chatpasskey').val());
           
        });        
        
        $('body').on('click','.chatreturn', function()
        {
            PanelShow(3);
            PingCount = 0;
            ChatTraffic = 0;
            ActiveChat(true,'');
            
        });
        
        $('body').on('click','.chatpoke', function()
        {
            //PanelShow(14);
            var mode = $(this).data('mode');
            var email = $(this).data('email');
            var name = $(this).data('name');

            $.ajax({
                url: rootserver+'chatpoke.php?'+timeStamp(),
                context: document.body,
                type: 'POST',
                data: 
                { 'providerid': $('#pid').val(),
                   'email' : email,
                   'name' : name,
                   'mode' : mode,
                   'timestamp' : timeStamp()
                }

            }).done(function( data, status ) {
                alertify.set({ delay: 2000 });
                alertify.log('Email Notificationsent to '+name); 
            });
                
            
        });
        $('body').on('click','.chatedititem', function()
        {
            $('.chatcomment').val("");
            $('.chatextraarea').hide();
            $('.chatextrahide').hide();
            $('.chatextra').show();
            
            var msgid = $(this).data('msgid');
            if( $('#chatcontent-'+msgid).is(":visible")){
                
                chatScrollSuspend = true;
                chatEditing = true;
                $('#chatedit-'+msgid).show();
                $('#chatcontent-'+msgid).hide();
                //$('#chatbottom').hide();
                $('.chatentry').hide();
                
            } else {
                chatScrollSuspend = false;
                chatEditing = false;
                $('#chatedit-'+msgid).hide();
                $('#chatcontent-'+msgid).show();
                //$('#chatbottom').show();
                $('.chatentry').show();
                ActiveChat(true,chatinputpasskey);
            }
            
        });
        //$('body').on('click','.chateditpost', function()
        //{
        //    var msgid = $(this).data('msgid');
        //    $('#chatedit-'+msgid).hide();
        //    $('#chatcontent-'+msgid).show();
            
        //});
        
        $('body').on('click','.textsend', function()
        {
            PingCount = 0;
            var search = $('#textsearchfilter').val();
            //var text = $('#textmessage').val();
            var textalias = $('#textalias').val();
            var sms = $(this).data('sms');
            var send = $(this).data('send');
            var name = $(this).data('name');
            var text = "";
            
            $.ajax({
                url: rootserver+'textpoke.php?'+timeStamp(),
                context: document.body,
                type: 'POST',
                data: 
                {   'providerid': $('#pid').val(),
                    'sms': sms,
                    'name': name,
                    'textalias': textalias,
                    'send': send,
                    'textmessage': text,
                    'searchfilter': search,
                    'timestamp' : timeStamp()
                }

            }).done(function( data, status ) {
                alertify.set({ delay: 2000 });
                alertify.log('Text Notification sent to'+name); 
            });
            
        });
        $('body').on('click','.hl7orders', function()
        {
            if(xhr && xhr.readyState !== 4){
                xhr.abort();
            }
            var page = $(this).data('page');
            var msgid = $(this).data('msgid');
            var mode = $(this).data('mode');
            var filter = $(this).data('filter');
            var name = $("#hl7name").val();
            if(typeof mode !== "undefined" && mode ==='P'){
                window.location = rootserver+'hl7order.php?providerid='+$('#pid').val()+"&mode=P&msgid="+msgid+"&filter="+filter;
                return;
            }
            $('#socialwindow').html(LoadingGIF);

            PanelShow(15);
            $('#socialwindow').load( rootserver+"hl7order.php",  {
                'providerid': $('#pid').val(),
                'page': page,
                'msgid' : msgid,
                'mode': mode,
                'filter' : filter,
                'name' : name
            }, function(html, status){
            });
            
            return;
            
        });
        $('body').on('click','.hl7reports', function()
        {
            if(xhr && xhr.readyState !== 4){
                xhr.abort();
            }
            var page = $(this).data('page');
            var msgid = $(this).data('msgid');
            var mode = $(this).data('mode');
            var status = $(this).data('status');
            var filter = $(this).data('filter');
            var name = $("#hl7name").val();
            $('#socialwindow').html(LoadingGIF);
            PanelShow(15);
            $('#socialwindow').load( rootserver+"hl7reports.php",  {
                'providerid': $('#pid').val(),
                'page': page,
                'msgid' : msgid,
                'status' : status,
                'mode': mode,
                'filter' : filter,
                'name' : name
            }, function(html, status){
            });
            
            return;
            
        });
        $('body').on('click','.hl7viewreport', function()
        {
            if(xhr && xhr.readyState !== 4){
                xhr.abort();
            }
            var msgid = $(this).data('msgid');
            var page = $(this).data('page');
            var chatid = $(this).data('chatid');
            var mode = $(this).data('mode');
            if(typeof mode !== "undefined" && mode ==='P'){
                window.location = rootserver+'hl7viewreport.php?msgid='+msgid+"&mode=P";
                return;
            }
            PanelShow(9);
            $('#popupwindow').load( rootserver+"hl7viewreport.php",  {
                'providerid': $('#pid').val(),
                'page': page,
                'msgid' : msgid,
                'mode' : mode,
                'chatid' : chatid
            }, function(html, status){
            });
            
            return;
            
        });
        $('body').on('click','.grouptext', function()
        {
            if(xhr && xhr.readyState !== 4){
                xhr.abort();
            }
            PanelShow(15);
            $('#socialwindow').load( rootserver+"grouptext.php?"+timeStamp(),  {
                'providerid': $('#pid').val(),
                'timestamp' : timeStamp()
            }, function(html, status){
            });
            
            return;
            
        });
        $('body').on('click','.grouptextsend', function()
        {
            var text = $('#grouptext').val();
            var texttitle = $('#grouptexttitle').val();
            var photo = $('#grouptextphoto').val();
            var smstext = $('#groupsmstext').val();
            var roomid = $('#grouptextroomid').val();
            var textgroup = $('#grouptextgroup').val();
            var test = $(this).data('test');
            var excludesms = "";
            if($('#excludesms').is(":checked")){
            
                excludesms = 'Y';
            }
            if( roomid === ''){
            
                alertify.alert("Please select a room for the message");
                return;
            }
            if( smstext === '' && text === ''){
            
                alertify.alert("No Message");
                return;
                
            }
            
            $.ajax({
                url: rootserver+'grouptextsend.php?'+timeStamp(),
                context: document.body,
                type: 'POST',
                data: 
                {   
                    'providerid': $('#pid').val(),
                    'text': text,
                    'texttitle': texttitle,
                    'smstext': smstext,
                    'roomid': roomid,
                    'textgroup': textgroup,
                    'test': test,
                    'photo': photo,
                    'excludesms': excludesms
                }

            }).done(function( data, status ) {
                $('.socialwindow').html(data);
            });
            
        });        
        
        $('body').on('blur','.grouptextroom', function()
        {
            //$('.grouptextfilter').val('major=*');
            GetCredentialOptions($(this).val());
            
        });
        $('body').on('focus','.grouptextfilter', function()
        {
            //$('.grouptextfilter').val('major=*');
            GetCredentialOptions($('.grouptextroom').val());
            
        });
        $(document).on('click','.filterenable', function(){
            $('.filtergroup').show();
 
        });
        $(document).on('click','.filterdisable', function(){
            $('.filtergroup').hide();
 
        });
        function GetCredentialOptions(roomid )
        {
            $('.grouptextfilter').load(rootserver+"grouptext.php", { 
                'roomid': roomid,
                'mode': '2'
            }, function(data){
                $('.grouptextfilter').val(data);
            });
        }
        
        $('body').on('click','.textproc', function()
        {
            if(xhr && xhr.readyState !== 4){
                xhr.abort();
            }
            PingCount = 0;
            PanelShow(15);
            var sessionid = $(this).data('deletesessionid');
            $('#socialwindow').load( rootserver+"textpoke.php?"+timeStamp(),  {
                'providerid': $('#pid').val(),
                'send' : 'R',
                'deletesessionid' : sessionid,
                'timestamp' : timeStamp()
            }, function(html, status){
            });

            
        });
    
        $('body').on('click','#activatechatsession', function()
        {
                AbortAjax();
                
                ToggleChatShow = false;
                ToggleMembersShow=false;
                answerid = $(this).data('providerid');
                mode = $(this).data('mode');
                $('.chatsendarea').show();
                $('.chatactionarea').show();
                
                $('#chatwindow').load( rootserver+"chatactivate.php?"+timeStamp(),  {
                    'c': $('#pid').val(), 
                    'a'    : answerid,
                    'mode' : mode,
                    'timestamp' : timeStamp()
                }, function(html, status){

                });
                
        });
        $('body').on('click','.addchatsession', function()
        {
                AbortAjax();
                
                var answerid = $(this).data('providerid');
                var mode = $(this).data('mode');
                ChatId = $(this).data('chatid');
                var passkey64 = localStorage['chat-'+ChatId];
                //alert(passkey64);
                
                $('#chatwindow').load( rootserver+"chatadd.php",  {
                    'c': $('#pid').val(), 
                    'a'    : answerid,
                    'mode' : mode,
                    'chatid' : ChatId,
                    'passkey64' :passkey64
                }, function(html, status){
                    PanelShow(3);
                    $('#chatid').val(ChatId);
                    PingCount = 0;
                    ChatTraffic = 0;
                    $('.chatsendarea').show();
                    $('.chatactionarea').show();
                    ActiveChat(true,chatinputpasskey);

                });
                
        });
        
        /*
        $('#chatmessage').keydown(function(e){
            
            var code = e.keyCode || e.which;
            if(code < 32 ){
                //Enter keycode
                return;
            }            
        });
        $('.chatmessage').click(function(e){
            alert('focus');
            //$('#chatbottom').hide();
        });
        */
        
       
        $('body').on('click','.startchatbutton', function()
        {
            $('#chatmessage').val("");
            var passkey64 = $(this).data('passkey64');
            var lifespan = $(this).data('lifespan');
            var title = B64.decode( $(this).data('titlebase64'));
            chatSelect('','','', passkey64, title, lifespan);
            
        });
        $('body').on('click','.starthyperchatbutton', function()
        {
            chatSelect('','','','', '', '');
            
            /*
            PanelShow(14);
            $('#chatmessage').val("");
            $('#socialwindow').scrollTop(0);
            $('#socialwindow').load( rootserver+"chatkey.php",  {
            }, function(html, status){
            });
            */
            
        });
        $('body').on('click','.chatenableE2E', function()
        {
            $('.chatE2Earea').show();
            $('.chatenableE2E').hide();
        });
        

        
        function chatSelect(mode, chatid, passkey, passkey64, title, lifespan )
        {
            AbortAjax();
            //$('.chatactionarea').hide();
            var find = $('.chatselectfind').val(); 
            if( typeof find === "undefined"){
                find = "";
            }
            ToggleChatShow = false;
            ToggleMembersShow=false;
            $('.chatheading').html("");
            $('#socialwindow').html(LoadingGIF);
            PanelShow(8);
            $('#socialwindow').hide().load( rootserver+"chatselect.php",  {
                'providerid': $('#pid').val(),
                'mode' : mode,
                'chatid' : chatid,
                'passkey' : passkey,
                'passkey64' : passkey64,
                'title' : title,
                'lifespan' : lifespan,
                'find' : find
            }, function(html, status){
                if( html!=="" && mode ==='A')
                {
                    PanelShow(8);
                }
                ChatTraffic = 0;

            }).fadeIn(800);
            
        }
        $('body').on('click','.addchatbutton', function()
        {
                var chatid = $(this).data('chatid');
                ToggleChatShow = false;
                ToggleMembersShow=false;
                AbortAjax();
                chatSelect('A',chatid,'','',0);
            
        });
        $('body').on('click','.selectchatlist', function()
        {
                if(!TermsOfUseCheck()){
                    return;
                }
                LastFunc = '';
            
                AbortAjax();
                $('.chatactionarea').hide();
                //$('.chatwindow').hide();
                //$('#chatwindow').scrollTop(0);
                var sort = $(this).data('sort');
                var mode = $(this).data('mode');
                var find = $('#findchat').val();
                //PanelShow(3);
                PanelShow(8);
                ToggleChatShow = false;
                ToggleMembersShow=false;
                
                ChatId = 0;
                ChannelId = 0;
                $('#chatid').val("");
                $('#socialwindow').html(LoadingGIF);
                
                $.ajax({
                    url: rootserver+'chatlist.php',
                    context: document.body,
                    type: 'POST',
                    data: 
                    { 'providerid': $('#pid').val(),
                       'sort' : sort,
                       'mode' : mode,
                       'find' : find
                     }
                 
                }).done(function( data, status ) {
                    //PanelShow(8);
                    var msg = jQuery.parseJSON(data);
                    //$('#chatwindow').scrollTop(0);
                    if( msg.noitems === 'Y'){
                        /* Invite - No Contacts */
                        chatSelect('N','','','','',0);
                        return;
                        
                    }
                    $('#popupwindow').html("");
                    
                    //$('#endchatbutton').hide();
                    $('#socialwindow').html(msg.list);
                    
                    $('#socialwindow').show();
                    
                }).fail(function( data, status ) {
                    $('#socialwindow').html(ConnectError);
                });
                
            
        });

        $('body').on('click','.selectchattech', function()
        {
                AbortAjax();
                $('.chatactionarea').hide();
                $('#chatwindow').scrollTop(0);
                PanelShow(3);
                
                ChatId = 0;
                ChannelId = 0;
                $('#chatid').val("");
                
                $.ajax({
                    url: rootserver+'chattech.php',
                    context: document.body,
                    type: 'POST',
                    data: 
                    { 'providerid': $('#pid').val(),
                       'timestamp' : timeStamp()
                    }
                 
                }).done(function( data, status ) {
                    PanelShow(3);
                    var msg = jQuery.parseJSON(data);
                    
                    $('#endchatbutton').hide();
                    $('.chatheading').html("");
                    $('#chatwindow').html(msg.list);
                    $('#chatwindow').scrollTop(0);
                });
                
            
        });        
        $('#chatwindow').on('scroll mousewheel DOMMouseScroll MozMousePixelScroll',function(){
            if(chatEditing){
                return;
            }
            if( LastChatScrollBottom === 0 )
            {
                
                ResetScrollSensor();
                return;
            }
            //$('.chatscrollsuspended').html( $('#chatwindow').scrollTop()+"/"+LastChatScrollBottom );
            if( LastChatScrollBottom > 0 && 
                $('#chatwindow').scrollTop() < LastChatScrollBottom - 100 ){
            
            
                    chatScrollSuspend = true;
                    $('.chatextra').show();
                    $('.chatextrahide').hide();
                    
                    return;
            
            }
            if( LastChatScrollBottom > 0 && 
                $('#chatwindow').scrollTop() > LastChatScrollBottom - 100 ){
            
                    if(chatScrollSuspend===true &&
                        $('#chatwindow').scrollTop() > LastChatScrollBottom - 20
                        ){
                        chatScrollSuspend = false;
                        $('#status').load( rootserver+"notifyreset.php");
                        
                        //ActiveChat(true,chatinputpasskey);
                        $('.chatextra').show();
                        $('.chatextrahide').hide();
                        setTimeout(
                            ResetScrollSensor(),500);
                    } else {
                        setTimeout(
                            ResetScrollSensor(),500);
                    }
                return;
            
            }
            
            return;

        });
        $('body').on('click','.refreshchatsession', function()
        {
            ChatId = $(this).data('chatid');
            $('.chatentry').html("");
            $('.chatextraarea').hide();
            ActiveChat(true, chatinputpasskey );
            
        });
        
        $('body').on('click','#refreshchatbutton', function()
        {
            PanelShow(3);
            
        });
        $('body').on('click','.sendchatbutton', function()
        {
            var msgid = $(this).data('msgid');
            var streaming = $(this).data('streaming');
            var msg;
            if(msgid!==""){
                
                $('.chatentry').show();
                $('#chatedit-'+msgid).hide();
                $('#chatcontent-'+msgid).show();
                msg = $('#chatcontent2-'+msgid).val();
                $('#chatmessage').val($('#chatcontent2-'+msgid).val());
                
            }
            ToggleChatShow = false;
            ToggleMembersShow=false;
            ShowBanner();
            
            $('#chatmessage').height( stdEntryHeight);
            $('.chatentry').height(stdEntryHeight+30);
            $('#chatmessage').removeClass('chatwidth2e').addClass('chatwidth2');
            
            SendChat($(this).data('passkey64'),msgid, streaming);
            
        });
        $('body').on('click','.formrequestadd', function()
        {
            var formid = $(this).data('formid');
            var chatid = $(this).data('chatid');
            var roomid = $(this).data('roomid');
            var passkey64 = $(this).data('passkey64');
            if($(this).is(":checked")){
                AbortAjax();
                $.ajax({
                    url: rootserver+'chatformrequest.php',
                    context: document.body,
                    type: 'POST',
                    data: 
                     { 'providerid': $('#pid').val(), 
                       'chatid' : chatid,
                       'roomid' : roomid,
                       'formid' : formid,
                       'passkey64' : passkey64,
                       'mode' : 'A'
                     }
                 }).done(function( data, status ) {
                    if(data!==''){
                        alertify.alert(data);
                    }
                 });
             }
            
        });
        $('body').on('click','.chatformrequest', function()
        {
            var chatid = $(this).data('chatid');
            var passkey64 = $(this).data('passkey64');
            PanelShow(9);
            AbortAjax();
            $.ajax({
                url: rootserver+'chatformrequest.php',
                context: document.body,
                type: 'POST',
                data: 
                 { 'providerid': $('#pid').val(), 
                   'chatid' : chatid,
                   'formid' : '',
                   'passkey64' : passkey64,
                   'mode' : 'E'
                 }
             }).done(function( data, status ) {
                    
                    $('#popupwindow').html(data);
             });

            
        });
        
        $('body').on('click','.chatemailnotify', function()
        {
            var chatid = $(this).data('chatid');
            alertify.confirm( "Send Email Notification to Chat Party?",function(ok){
                if( ok ){
                        AbortAjax();
                        $.ajax({
                            url: rootserver+'chatsend.php',
                            context: document.body,
                            type: 'POST',
                            data: 
                             { 'providerid': $('#pid').val(), 
                               'chatid' : chatid,
                               'mode' : 'E'
                             }
                         }).done(function( data, status ) {
                             //alertify.alert(data);
                         });
                    
                }
            });
            
        });
        $('body').on('click','.scrollup', function()
        {
            $('.mainview').scrollTop(0);
            $('#chatwindow').animate({scrollTop:0}, 'slow');
        });
        $('body').on('click','.scrolldown', function()
        {
            ScrollChat();
        });

        
        
       $('body').on('click','#endchatbutton', function()
        {
                ToggleChatShow = false;
                ToggleMembersShow=false;
                var chatid = $(this).data('chatid');
                var archive = $(this).data('archive');
                var archivemessage;
                if( archive === 'Y'){
                
                    archivemessage = 'Archive Chat Conversation?';
                } else {
                
                    archivemessage = "Delete Chat Conversation?";
                    
                }
                alertify.confirm( archivemessage,function(ok){
                    if( ok ){
                        
                        AbortAjax();
                        $.ajax({
                            url: rootserver+'chatfinish.php',
                            context: document.body,
                            type: 'POST',
                            data: 
                             { 'providerid': $('#pid').val(), 
                               'chatid' : chatid,
                               'archive' : archive
                             }
                         }).done(function( data, status ) {
                             
                            $('#trigger_selectchat').trigger('click');
                             
                         });

                    
                    } else {
                        
                    }
                });
        });
        $('body').on('click','.showchatinvite', function()
        {
                $('.newchatinvite').show();
                $('.showchatinvite').hide();
                $('.hidechatinvite').show();
                //$('.showchatinvite').hide();
                $('.chatmembers').hide();
                
        });
        $('body').on('click','.hidechatinvite', function()
        {
                $('.newchatinvite').hide();
                $('.showchatinvite').show();
                $('.hidechatinvite').hide();
                $('.chatmembers').show();
        });
        
        $('body').on('click','.restorechatsession', function()
        {
            if( ChatId === "" || ChatId === 0){
                $('#trigger_selectchat').click();
                return;
            }
            PanelShow(3);
        });
        $('body').on('click','.setchatsession', function()
        {
                chatScrollSuspend = false;
                ChannelId = 0;
                TempChatId = $(this).data('chatid');
                if( TempChatId !== ""){
                    ChatId = TempChatId;
                    ChannelId = $(this).data('channelid');
                }
                
                if( TempChatId === "0" || TempChatId === ""){
                    $('#trigger_selectchat').click();
                    return;
                }
                
                var keyhash = $(this).data('keyhash');
                var passkey64 = localStorage['chat-'+ChatId];
                var error = $(this).data('error');
                ToggleMembersShow=false;
                
                $('.chatwindow').html(LoadingGIF);
                $('.chatentry').html("");
                $('.chatextraarea').hide();
                PanelShow(3);
                Sizing();

                $.ajax({
                    url: rootserver+'lastfunc.php?'+timeStamp(),
                    context: document.body,
                    type: 'POST',
                    data: 
                     { 'providerid': $('#pid').val(), 
                       'mode' : 'S',
                       'parm1' : ChatId,
                       'lastfunc' : 'C',
                       'timestamp' : timeStamp()
                     }
                 });
            
            
                 //A Valid Key Exists
                if(keyhash!=='' && typeof error === "undefined" ){
                    //Decrypt the key
                    $.ajax({
                        url: rootserver+'chatkey.php',
                        context: document.body,
                        type: 'POST',
                        data: 
                         { 'providerid': $('#pid').val(), 
                           'mode' : 'U',
                           'chatid' : ChatId,
                           'passkey64' : passkey64
                         }
                     }).done(function(data, status){
                        ToggleMembersShow = false;
                        
                        SwipeFromChat();
                         
                        ActiveChat(true,data);
                        return;
                         
                     });
                     return;
                }
                 //A Valid Key Exists
                if(keyhash!=='' && error === "Y" ){
                    PanelShow(8);
                    $('#socialwindow').html(LoadingGIF);
                    $('#socialwindow').load( rootserver+"chatkey.php", 
                         { 'providerid': $('#pid').val(), 
                           'mode' : 'C',
                           'chatid' : ChatId,
                           'keyhash' : keyhash,
                           'passkey64' : passkey64
                         }
                    , function(html, status){
                    }).fadeIn(800);
                     return;
                }
                
                
                AbortAjax();
                $.ajax({
                    url: rootserver+'lastfunc.php?'+timeStamp(),
                    context: document.body,
                    type: 'POST',
                    data: 
                     { 'providerid': $('#pid').val(), 
                       'mode' : 'S',
                       'parm1' : ChatId,
                       'lastfunc' : 'C',
                       'timestamp' : timeStamp()
                     }
                 });
        
                
                //$('.chatsendarea').show();
                //$('#endchatbutton').show();
                
                SwipeFromChat();
                
                //$('#chatid').val(ChatId);
                PingCount = 0;
                ChatTraffic = 0;
                ActiveChat(true,'');
                

        });
        
        $('body').on('click','.refreshchatsession', function()
        {
                ChannelId = 0;
                TempChatId = $(this).data('chatid');
                if( TempChatId !== ""){
                    ChatId = TempChatId;
                    ChannelId = $(this).data('channelid');
                }
                var keyhash = $(this).data('keyhash');
                var passkey64 = localStorage['chat-'+ChatId];
                var error = $(this).data('error');
                ToggleMembersShow=false;
                $('.chatextraarea').hide();
                
                ActiveChat(true,'');

        });
        
        $('body').on('click','.memberchat', function()
        {
            alertify.alert("You are now a member of this channel. Go the LIVE area to access the channels.");
            
        });
        
        $('body').on('focus','.roominputfocus', function()
        {
            if( MobileType === 'I' || MobileType=== 'A'){
            
                $('.banner .bannerflush').hide();
                //$(this).animate({scrollTop: 0}, fast);            
                $('html, body').animate({ scrollTop: $(this).offset().top - 500 }, 'slow');          
            }
            
        });

        $('body').on('blur','.roominputfocus', function()
        {
            if( MobileType === 'I' || MobileType=== 'A'){
            
                //$('.banner .bannerflush').show();
            }
            
        });
        
                
        /*
        $('#chatwindow .feedphotochat').imagesLoaded( function(){
        }).progress( function() {
                setTimeout( function(){
                    ScrollChat();
                }, 250 );
        }).done( function(){
                setTimeout( function(){
                    ScrollChat();
                }, 250 );

        });
        */
        
        $('body').on('click','.chatexpand', function()
        {
                //$('#chatbottom').hide();
                AbortAjax();
                
                $('.mainview').scrollTop(0);
                var height = $('.chatentry').height();
                if( height < stdEntryHeight*4 ){
                    $('#chatmessage').height(stdEntryHeight*6);
                    $('.chatentry').height((stdEntryHeight*8));
                    $('#chatmessage').focus();
                    $('.chatexpandbutton').hide();
                    $('.chatshrinkbutton').show();
                    
                    $('.chatextraarea').hide();
                    $('.chatextrahide').hide();
                    $('.chatextra').show();
                    ResizeChatWindow();
                    //Temp effect only
                    //chatScrollSuspend = true;
                    setTimeout( function(){
                        ScrollChat();
                        }, 100 
                    );
                    
                } else {
                    
                    $('#chatmessage').height( stdEntryHeight);
                    $('.chatentry').height(stdEntryHeight+50);
                    $('#chatmessage').removeClass("chatwidth2e").addClass("chatwidth2");
                    //$('#chatmessage').focus();
                    $('.chatexpandbutton').show();
                    $('.chatshrinkbutton').hide();
                    $('.chatextraarea').hide();
                    $('.chatextrahide').hide();
                    ResizeChatWindow();
                }
            
        });
        $('body').on('input','#chatmessage',function(e) {
            var fieldlen = $(this).val().length;
            if( fieldlen > 0 ){
                $('.broadcastdiv').hide();
                
            }
            
            if(fieldlen >=15){
                $('.mainview').scrollTop(0);
                    
                $('#chatmessage').height(stdEntryHeight*6);
                $('.chatentry').height((stdEntryHeight*10));
                $('#chatmessage').removeClass('chatwidth2').addClass('chatwidth2e');
                //$('#chatmessage').focus();
                $('.chatexpandbutton').hide();
                $('.chatshrinkbutton').show();
                $('#chatmessage').show();
                $('#chatnessage').css('position','absolute');

                $('.chatextraarea').hide();
                $('.chatextrahide').hide();
                $('.chatextra').show();
                ResizeChatWindow();
                ScrollChat();
                
            }
            
        });
        $('body').on('keyup','#chatmessage',function(e) {
            
            $('.chatextraarea').hide();
            $('.chatextrahide').hide();
            $('.chatextra').show();
            
            var fieldlen = $(this).val().length;
            
            if ((e.keyCode === 10 || e.keyCode === 13) && !e.shiftKey){
                var streaming = $(this).data('streaming');
                ToggleChatShow = false;
                ToggleMembersShow=false;
                ShowBanner();
                
                $('#chatmessage').height( stdEntryHeight);
                $('.chatentry').height(stdEntryHeight+50);
                $('#chatmessage').removeClass('chatwidth2e').addClass('chatwidth2');

                SendChat($(this).data('passkey64'),'', streaming);
                return;
                
            }
            if( fieldlen===0 && e.keyCode === 8){
                $('#chatmessage').height( stdEntryHeight);
                $('.chatentry').height(stdEntryHeight+30);
                $('#chatmessage').removeClass('chatwidth2e').addClass('chatwidth2');
                if(MobileCapable){
                    $('#chatmessage').blur();
                }
                $('.broadcastdiv').show();
            }
            if( fieldlen > 0 ){
                $('.broadcastdiv').hide();
                
            }
    
            if( fieldlen >= 15 ){
                e.which = 13;
            }
            
            //if(enlarge === 1) {
            if(e.which === 13) {
                $('.mainview').scrollTop(0);
                    
                $('#chatmessage').height(stdEntryHeight*6);
                $('.chatentry').height((stdEntryHeight*10));
                $('#chatmessage').removeClass('chatwidth2').addClass('chatwidth2e');
                //$('#chatmessage').focus();
                $('.chatexpandbutton').hide();
                $('.chatshrinkbutton').show();
                $('#chatmessage').show();
                $('#chatnessage').css('position','absolute');

                $('.chatextraarea').hide();
                $('.chatextrahide').hide();
                $('.chatextra').show();
                ResizeChatWindow();
                ScrollChat();
            }
        });        
        
        $('body').on('keyup','.dataentry',function(e) {
            
            
            var fieldlen = $(this).val().length;
            
            if( fieldlen===0 && e.keyCode === 8 && MobileCapable){
                $('.dataentry').blur();
            }
            if(e.keyCode ===13){
                if($(this).data('field')==='roomselect'){
                    
                }
                
            }
        });                
        
        $('body').on('click','.chatcomment', function()
        {
                //$('#chatbottom').hide();
                AbortAjax();
                
                $('.mainview').scrollTop(0);
                ShowBanner();
                
                //$('.inputfocuscontent').hide();
                $('.inputfocuscontent').hide();
                $('.chatextraarea').hide();
                $('.chatextrahide').hide();
                $('#chatmessage').focus(800);
                ScrollChat();
            
        });
        $('body').on('click','.chatcomment2', function()
        {
                //$('#chatbottom').hide();
                AbortAjax();
                
                $('.mainview').scrollTop(0);
                ShowBanner();
                
                $('.chatextraarea').hide();
                $('#chatmessage').focus(0);
                ScrollChat();
                
        });

        $('body').on('click','.chatcommenthide', function()
        {
                $('.chatcomment').val("");
                $('#chatmessage').val("");
                $('#chatmessage').removeClass('chatwidth2e').addClass('chatwidth2');
                $('.chatcomment').show(1000);
                $('.inputfocuscontent').show();
                $('.chatcommenthide').hide();
                $('.chatsendarea').hide();
                $('.chatsendarea2').hide();
                $('#chatmessage').blur();
                $('.chatextrahide').hide();
                $('.chatextraarea').hide();
                $('body').animate({scrollTop:$('.bannerflush').offset().top}, 'fast');
                ScrollChat();
                ActiveChat(true,chatinputpasskey);
            
        });
        $('body').on('click','.chatcommenthide2', function()
        {
                $('.chatsendarea').hide();
                $('.chatcommenthide').hide();
                $('.chatextraarea').hide();
                
                $('.chatcomment').show();
                $('.inputfocuscontent').show();
                $('#chatwindow').show();
                //$('#chatbottom').show();
                
                ScrollChat();
                ActiveChat(true,chatinputpasskey);
                
                
            
        });
        $('body').on('click','.chatextra', function()
        {
                if($('#chatmessage').val()!==''){
                    return;
                }
                //chatScrollSuspend = true;
                $('#chatmessage').val("");
                $('.chatextraarea').show();
                $('.chatextrahide').show();
                $('.chatextra').hide();
                ScrollChat();
            
        });
        $('body').on('click','.chatextrahide', function()
        {
                //$('#chatbottom').show();
                chatScrollSuspend = false;
                
                $('.chatcomment').val("");
                $('.chatextraarea').hide();
                $('.chatextrahide').hide();
                $('.chatextra').show();
                
                $('#chatmessage').height( stdEntryHeight);
                $('.chatentry').height(stdEntryHeight+30);
                $('#chatmessage').removeClass('chatwidth2e').addClass('chatwidth2');
                $('#chatmessage').blur();
                
                ScrollChat();
                ActiveChat(true,chatinputpasskey);
            
        });
        $('body').on('click','.chatextrahide2', function()
        {
                //$('#chatbottom').show();
                chatScrollSuspend = false;
                
                $('.chatcomment').val("");
                $('.chatextraarea').hide();
                $('.chatextrahide').hide();
                $('.chatextra').show();
                
                $('#chatmessage').height( stdEntryHeight);
                $('.chatentry').height(stdEntryHeight+30);
                $('#chatmessage').removeClass('chatwidth2e').addClass('chatwidth2');
                $('#chatmessage').blur();
                
                ScrollChat();
                ActiveChat(true,chatinputpasskey);
            
        });
        $('body').on('focus','#chatmessage', function()
        {
            ScrollChat();
        });
        $('body').on('click','#chatmessage', function()
        {
            ScrollChat();
        });
        
        
        $('body').on('click','.chatdeleteparty', function()
        {
                //PanelShow(3);
                var pid = $(this).data('providerid');
                var chatid = $(this).data('chatid');
                var mode = $(this).data('mode');
                var prompt = 'Delete party from chat?';
                
                if( typeof mode === "undefined"){
                    mode = '';
                }
                if( mode === 'L'){
                    prompt = 'Leave this chat?'
                }
                if( mode === 'R'){
                    prompt = 'Leave this live streaming station?'
                }
                
                alertify.confirm( prompt ,function(ok){
                    prompt = '';
                    if( ok ){
                    
                        $.ajax({
                            url: rootserver+'chatsend.php',
                            context: document.body,
                            type: 'POST',
                            data: 
                            { 'providerid': pid,
                              'mode' : 'DP',
                              'chatid': chatid
                            }
                        }).done(function( data, status ) {
                            ScrollChat();

                            if( mode !== 'R'){
                                $('#trigger_selectchat').trigger('click');
                            } else {
                                $('#trigger_selectlive').trigger('click');
                            }
                        });
                    }
                });

        });
        
        
        $('body').on('click','.chatdeleteitem', function()
        {
                //PanelShow(3);
                var msgid = $(this).data('msgid');
                $.ajax({
                    url: rootserver+'chatsend.php',
                    context: document.body,
                    type: 'POST',
                    data: 
                    { 'providerid': $('#pid').val(),
                      'mode' : 'D',
                      'msgid' : msgid,
                      'chatid': ChatId
                    }
                }).done(function( data, status ) {
                
                    $('.chatentry').show();
                    ScrollChat();
                    ActiveChat(true,chatinputpasskey);
                });

        });
        $('body').on('click','.chatflagitem', function()
        {
                //PanelShow(3);
                var msgid = $(this).data('msgid');
                var action = $(this).data('action');
                $.ajax({
                    url: rootserver+'chatsend.php',
                    context: document.body,
                    type: 'POST',
                    data: 
                    { 'providerid': $('#pid').val(),
                      'mode' : 'F',
                      'msgid' : msgid,
                      'chatid': ChatId,
                      'action' : action
                    }
                }).done(function( data, status ) {
                
                    ScrollChat();
                    ActiveChat(true,chatinputpasskey);
                });

        });
        
        $('body').on('click','.chatsettitleopen', function()
        {
                if($('.chattitle').not(":visible")){
                    $('.chattitlearea').show();
                    return;
                }
        });
        $('body').on('click','.chatsettitle', function()
        {
                //PanelShow(3);
                var chatid = $(this).data('chatid');
                var title = $('.chattitle').val();
                var radio = $('.chatradio').val();
                $.ajax({
                    url: rootserver+'chatsend.php',
                    context: document.body,
                    type: 'POST',
                    data: 
                    { 'providerid': $('#pid').val(),
                      'mode' : 'T',
                      'title' : title,
                      'chatid': chatid,
                      'radio' : radio
                    }
                }).done(function( data, status ) {
                    $('.chattitle').val('');
                    $('.chattitlearea').hide();
                    $('.chatextraarea').hide();
                
                    ScrollChat();
                    ActiveChat(true,chatinputpasskey);
                });

        });
        $('body').on('click','.chatothertext', function()
        {
                //PanelShow(3);
                var reply = $(this).data('reply');
                var icon = ">";
                if(reply!==''){
                    $('#chatmessage').focus().val("").val(icon+reply+" - ");
                    ScrollChat();
                }

        });
        $(document).on( 'click',".mute", function() 
        { 
            AbortAjax();
            var chatid = $(this).data('chatid');
            var roomid = $(this).data('roomid');
            
                $.ajax({
                    url: rootserver+'notifymute.php',
                    context: document.body,
                    type: 'POST',
                    data: 
                    { 'providerid': $('#pid').val(),
                      'chatid' : chatid,
                      'roomid' : roomid
                    }
                 
                }).done(function( data, status ) {
                    if( data ==='Y'){
                        alertify.alert("Notifications muted ");
                    } else {
                        alertify.alert("Notifications unmuted ");
                        
                    }
                    
                    
                });
            
        });
        

        $(document).on( 'click',".togglechat", function() 
        { 
            if(ToggleChatShow){
                ToggleChatShow=false;
                //$('.oldchat').hide();
                $('.togglechat').html(LoadingGIF2);
            }
            else{
               ToggleChatShow=true;
                //$('.oldchat').show();
                $('.togglechat').html(LoadingGIF2);
                //$('#chatwindow').animate({scrollTop:$('#chatbottom').offset().top+999999}, 'slow');
           }
           $('.chatextraarea').hide();
           ScrollChat();
           chatScrollSuspend = false;
           ActiveChat(true,chatinputpasskey);
           
            
        });
        $(document).on( 'click',".togglemembers", function() 
        { 
            if(ToggleMembersShow){
                ToggleMembersShow=false;
                //$('.oldchat').hide();
                $('.togglemembers').html(LoadingGIF2);
                //$('.togglechat').html("Show Members");
            }
            else{
               ToggleMembersShow=true;
                //$('.oldchat').show();
                $('.togglemembers').html(LoadingGIF2);
           }
           
            
        });
        $(document).on( 'click',".displaychatmembers", function() 
        { 
            $('#trigger_members').click();
        });
        
        $(document).on( 'click',".togglememberson", function() 
        { 
            var find =  $('.chatmemberfind').val();
            
            //ToggleMembersShow=true;
            $('.chatextraarea').hide();
            $('#chatwindow').scrollTop(0);
            $('#popupwindow').load(rootserver+"chatpeople.php",{
                'providerid': $('#pid').val(),
                'chatid' : ChatId,
                'find': find,
            }, function(html, status){
                PanelShow(9);
                $('#chatwindow').scrollTop(0);
            });
            //$('.togglemembers').html(LoadingGIF2);
            //ActiveChat(true,chatinputpasskey);
        });
        $(document).on( 'click',".showchatmsg", function(e) 
        { 
            
            $('.roomcomment').hide();
            $('.roomcommentheader').show();
            $('.roomcommenthideheader').hide();
            
        });
        $('body').on('click','.userview', function()
        {
            AbortAjax();
            var userid = $(this).data('providerid');
            var caller = $(this).data('caller');
            
                $.ajax({
                    url: rootserver+'userview.php',
                    context: document.body,
                    type: 'POST',
                    data: 
                    { 'providerid': $('#pid').val(),
                      'userid' : userid,
                      'caller' : caller
                    }
                 
                }).done(function( data, status ) {
                    PanelShow(9);
                    $('#popupwindow').html(data);
                    
                });
        });
    
   /*****************************************************************************
    *
    *    
    *    
    *    *  
    *  
    * 
    * 
    *****************************************************************************/
        $('body').on('click','.meetupconnectshow', function()
        {
           $('.meetuprecentshow').hide();
           $('.meetuppublicshow').hide();
           $('.meetupcontactlistarea').hide();
           $('.meetupenterpriselistarea').hide();
        });
        $('body').on('click','.meetuprecent', function()
        {
           $('.meetuprecentshow').show();
           $('.meetuppublicshow').hide();
           $('.meetupcontactlistarea').hide();
           $('.meetupenterpriselistarea').hide();
        });
        $('body').on('click','.meetuppublic', function()
        {
           $('.meetuprecentshow').hide();
           $('.meetuppublicshow').show();
           $('.meetupcontactlistarea').hide();
           $('.meetupenterpriselistarea').hide();
           //$('.meetupconnectshow').hide();
        });
        $('body').on('click','.meetupcontactlist', function()
        {
           $('.meetuprecentshow').hide();
           $('.meetuppublicshow').hide();
           $('.meetupcontactlistarea').show();
           $('.meetupenterpriselistarea').hide();
           //$('.meetupconnectshow').hide();
        });
        $('body').on('click','.meetupenterpriselist', function()
        {
           $('.meetupconnectarea').hide(); 
           $('.identityarea').hide(); 
           $('.meetuprecentshow').hide();
           $('.meetuppublicshow').hide();
           $('.meetupconnectrequestarea').hide();
           $('.meetupcontactlistarea').hide();
           $('.meetupenterpriselistarea').show();
           //$('.meetupconnectshow').hide();
        });
        $('body').on('keyup','#meetuppublicfind',function(e) {
            
            if ((e.keyCode === 10 || e.keyCode === 13) && !e.shiftKey){
                $("#meetuplistbutton1").click();
            }
        });        
        $('body').on('keyup','#findchat',function(e) {
            
            if ((e.keyCode === 10 || e.keyCode === 13) && !e.shiftKey){
                $("#selectchatlistbutton").click();
            }
        });        
        $('body').on('click','#findchatbyname',function(e) {
            $('#findchat').focus();
            
        });        
        $('body').on('click','#findpeoplebyname',function(e) {
            $('#meetuppublicfind').focus();
            
        });        
        $('body').on('keyup','#filefiltername',function(e) {
            if ((e.keyCode === 10 || e.keyCode === 13) && !e.shiftKey){
                $("#refreshalbum").click();
            }
            
        });        
        
        $('body').on('click','.meetuplist', function()
        {
            
                ChatId = 0;
                ChannelId = 0;
                LastFunc = '';
            
                AbortAjax();
                $('.chatactionarea').hide();
                $('#chatwindow').html("");
                $('#socialwindow').scrollTop(0);
                var mode =  $(this).data('mode');
                if( typeof mode === 'undefined'){
                    mode = '';
                }
                var find = ''; 
                if( mode === 'P5'){
                    find =  $('.meetupcontactlistfind').val();
                } else 
                if( mode === 'P6'){
                    find =  $('.meetupenterpriselistfind').val();
                } else {
                    find =  $('.meetuppublicfind').val();
                    
                }
                //alert("mode-"+mode);

                $('#socialwindow').html(LoadingGIF);
                //PanelShow(31);
                $.ajax({
                    url: rootserver+'findpeople.php',
                    context: document.body,
                    type: 'POST',
                    data: 
                    { 'providerid': $('#pid').val(),
                      'find' : find,
                      'mode' : mode
                    }
                 
                }).done(function( data, status ) {
                    PanelShow(31);
                    var msg = jQuery.parseJSON(data);
                    $('#socialwindow').html(msg.list);//.fadeIn("800");
                    if( mode === ''){
                        //mode = msg.mode;
                        //alertify.alert('mode = blank')
                    }
                    
                    $('#socialwindow').scrollTop(0);
                    if( mode === 'P1' || mode === '' || mode == 'LIVE' ){
                        $('.meetuppublicshow').show();
                        //$('body .meetuppublic').trigger('click');
                    }
                    if( mode === 'P2' ){
                        $('.meetuprecentshow').show();
                        //$('body .meetuprecent').trigger('click');
                    }
                    if( mode === 'P5' ){
                        $('.meetupcontactlistarea').show();
                        //$('body .meetupcontactlist').trigger('click');
                    }
                    if( mode === 'P6' ){
                        $('.meetuppublicshow').show();
                        //$('body .meetupenterpriselist').trigger('click');
                    }
                });
                
            
        });
        $('body').on('keyup','.contactfill', function(e)
        {
            
            var s;
            
            var code = e.keyCode || e.which;
            
            s = $('.contactfill').val();
            if( s.length <= 3 ){
                $('.hidecontactbook').click();
            }
            
            if(code < 32 || code > 122) { //Enter keycode
              return;
            }            
            if( s.length > 3  ){
            
               RefreshContactList(s);
            }
        });

         $('#membername').keyup(function(e){
            
            var s;
            var searchitem;
            
            var code = e.keyCode || e.which;
            if(code < 32 || code > 122) { //Enter keycode
              return;
            }            
             
            s = $('#membername').val();
            s = s.replace(";",",");
            $('#membername').val(s);
            n1 = s.lastIndexOf(",");
            if( n1 === -1 ){
            
                searchitem = $('#membername').val();
            }
            else {
            
                searchitem = s.substr(n1+1);
            }
            if( searchitem.length > 3  ){
            
               FindContact(searchitem);
            }
        });
        $('body').on('click','.blockbutton', function() 
        {
            var email = $(this).data('email');
            var name = $(this).data('name');
            var handle = $(this).data('handle');
            alertify.confirm( "Block "+handle+"?",function(ok){
                if( ok ){
                    AbortAjax();
                    $.ajax({
                        url: rootserver+'contactbooksave.php',
                        context: document.body,
                        type: 'POST',
                        data: 
                        { 
                            'mode': 'B', 
                            'providerid': $('#pid').val(), 
                            'recipientemail': email, 
                            'recipientname': name,
                            'handle': handle
                        }

                    }).done(function( data, status ) {
                    });
                    
                }
            });
                     
        });
        $('body').on('click','.unblockbutton', function() 
        {
            var email = $(this).data('email');
            var name = $(this).data('name');
            var handle = $(this).data('handle');
            alertify.confirm( "Unblock "+handle+"?",function(ok){
                if( ok ){
                    AbortAjax();
                    $.ajax({
                        url: rootserver+'contactbooksave.php',
                        context: document.body,
                        type: 'POST',
                        data: 
                        { 
                            'mode': 'U', 
                            'providerid': $('#pid').val(), 
                            'recipientemail': email, 
                            'recipientname': name,
                            'handle': handle
                        }

                    }).done(function( data, status ) {
                    });
                }
            });
                     
        });
        $('body').on('click','.quizbutton', function()
        {
            var mode = $(this).data('mode');
            var chatid = $(this).data('chatid');
            var roomid = $(this).data('roomid');
            var question = $('#quizquestion').val();
            
            $.ajax({
                url: rootserver+'quiz.php',
                context: document.body,
                type: 'POST',
                data: 
                 { 'providerid': $('#pid').val(), 
                   'chatid' : chatid,
                   'mode' : mode,
                   'roomid' : roomid,
                   'question' : question
                 }
             }).done(function( data, status ) {
                $('.chatwindow').html(LoadingGIF);
                PanelShow(3);
                 PingCount = 0;
                 ChatTraffic = 0;
                 TimerFieldCount = 0;
                 ActiveChat(true, '');
            });
            
        });
        $('body').on('click','.store', function()
        {
            if(xhr && xhr.readyState !== 4){
                xhr.abort();
            }
            $('#socialwindow').html(LoadingGIF);
            PanelShow(15);
            $('#socialwindow').load( rootserver+"store.php",  {
                'providerid': $('#pid').val()
            }, function(html, status){
            });
            
            return;
            
        });
        
        $('body').on('click','.pinlock', function()
        {
            $('#trigger_pin').click();
            
        });
        $('body').on('click','.pinentry', function()
        {
            
            if(pin === ''){
                if(mobileversion!=='000' && mobileversion!==''  ){
                     NativeCall("restart");
                     return;
                 }
                
                //alert('no pin');
                window.location = rootserver1+startupphp+"?s="+source+"&e="+enterprise+"&apn="+apn+"&gcm="+gcm+"&v="+mobileversion;
                return;
            }
            if(pinlock === 'Y'){
                //alert('pinlock');
                //return;
            }
                //alert('pin');
            
            
            TryPin = "";
            pintries = 0;
            $('.sidebararea').html("");
            $('#banner').html("")
            $('#socialwindow').html(LoadingGIF);
            PanelShow(15);
            $('#socialwindow').load( rootserver+"keypad.php",  {
                'providerid': $('#pid').val(),
                'version' : mobileversion,
                'apn' : localStorage.apn,
                'gcm' : localStorage.gcm
            }, function(html, status){
                pinlock = 'Y';
                //new FastClick(',keypadcontainer');
            });
        });
        $('body').on('click','.testlink', function()
        {
            TryPin = "";
            pintries = 0;
            $('.sidebararea').html("");
            $('#banner').html("")
            $('#socialwindow').html(LoadingGIF);
            PanelShow(15);
            $('#socialwindow').load( rootserver+"keypad.php",  {
                'providerid': $('#pid').val(),
                'version': mobileversion
            }, function(html, status){
            });
        });

    
        $('body').on('click','.keypadbutton', function(event)
        {
            //event.preventDefault();
            $(this).animate({opacity:0.5}, 0);
            $(this).animate({opacity:1}, 0);
            if( MobileCapable ===false){
                GetPinEntry( event, $(this).data("value") );
            }
            
        });
        $('body').on('touchend','.keypadbutton', function(event)
        {
            //event.preventDefault();
            $(this).animate({opacity:0.5}, 0);
            $(this).animate({opacity:1}, 0);
            if( MobileCapable ===true){
                GetPinEntry( event, $(this).data("value") );
            }
            
        });
        
        
        $(this).mousemove(function(e){
              idleTime = 0;
              ResetTimeout();
        });



       $('body').on('click','#screenshot-button', function(){
           snapshot();
           
       });
        $('input[type=text][title],input[type=password][title],input[type=email][title],textarea[title]').each(function(i){
            $(this).addClass('input-prompt-' + i);
            var promptSpan = $('<span class="input-prompt"/>');
            $(promptSpan).attr('id', 'input-prompt-' + i);
            $(promptSpan).append($(this).attr('title'));
            $(promptSpan).click(function(){
                $(this).hide();
                $('.' + $(this).attr('id')).focus();
            });
            if($(this).val() !== ''){
              $(promptSpan).hide();
            }
            $(this).before(promptSpan);
            $(this).focus(function(){
                  $('#input-prompt-' + i).hide();
            });
            $(this).blur(function(){
                if($(this).val() === ''){
                  $('#input-prompt-' + i).show();
                }
            });
         });       

        $('body').on('click','.scroller-right',function() {
        //    alertify.alert('right');
        });

        $('body').on('click','.scroller-left',function() {

        //    alertify.alert('left');
        });    

        $('body').on('click','.videotwitch', function() 
        {
            $('.videotype').val('twitch');
            $('.videotypechannelheading').text("Twitch Username");
            $('.videochannelinfo').show();
            $('.videochannel').val('');
            broadcastsave();
        });
        $('body').on('click','.videoyoutube', function() 
        {
            $('.videotype').val('youtube');
            $('.videotypechannelheading').text("Youtube Channel ID");
            $('.videochannelinfo').show();
            $('.videochannel').val('');
            broadcastsave();
        });
        $('body').on('click','.videoyoutubevideo', function() 
        {
            $('.videotype').val('youtubevideo');
            $('.videotypechannelheading').text("Youtube Video Embed Code");
            $('.videochannelinfo').show();
            $('.videochannel').val('');
            broadcastsave();
        });
        $('body').on('click','.videobraxlive', function() 
        {
            $('.videotype').val('braxlive');
            $('.videotypechannelheading').text("Channel");
            $('.videochannelinfo').hide();
            $('.videochannel').val('');
            broadcastsave();
        });
        $('body').on('click','.videowebcam', function() 
        {
            $('.videotype').val('webcam');
            $('.videotypechannelheading').text("Channel");
            $('.videochannelinfo').hide();
            $('.videochannel').val('');
            broadcastsave();
        });
        
        $('body').on('blur','.videochannel', function() 
        {
            broadcastsave();
        });
        $('body').on('blur','.videobroadcasttitle', function() 
        {
            broadcastsave();
        });
        $('body').on('blur','.broadcasttype', function() 
        {
            broadcastsave();
        });
        function broadcastsave()
        {
            if($('.videotype').val()===''){
                alertify.alert('Select a Broadcast Medium');
                return;
            }
            
            var title = $('.videobroadcasttitle').val();
            $('.videoselectaction').show();
            $('#status').load(rootserver+"videotypesave.php", { 
                'pid': $('#pid').val(),
                'broadcasttype': $('.videotype').val(),
                'channel': $('.videochannel').val(),
                'title': title
            }, function(data, status){
                if(data!==''){
                    var msg = jQuery.parseJSON(data);
                    $('.videochannel').val(msg.channel);
                    $('.videobroadcasttitle').val( msg.title );
                    //broadcastsave();
                }
            });
        }

        function FindContact(searchitem)
        {
            alertify.alert(searchitem);
        }

        function RefreshContactList( searchfilter )
        {
            
            $.ajax({
                url: rootserver+'contactbook.php',
                context: document.body,
                type: 'POST',
                data: 
                 { 'providerid': $('#pid').val(), 
                   'searchfilter': searchfilter, 
                   'page': $('#page').val() 
                  },
                        
            }).done(function( html, status ) {
                if( $(".invitechatemail").val()!=='' || 
                    $(".invitechatemail").val()!==''

                  )
                {
                    $('.addressbookcontent').hide();
                    return;
                }
                $('#addressbookcontent').html(html);
                xhr = null;
                if( status=="success"){
                
                    $('.invitechatemail').val( "");
                    $('.invitechatsms').val( "");

                    $('.hidecontactbook').click( function(){
                        $('.addressbookcontent').hide();
                    });

                    $('.addressbookcontent').show();

                    $('tr.addressbook').on('click', function() {
                        var handle = $(this).find('.addressbook6').text().trim() ;
                        /*
                        if(handle!=='')
                        {
                            $('.invitechatname').val();
                            $('.invitechathandle').val( handle  );
                            $('.invitechatemail').val();
                            $('.invitechatsms').val();

                            $('.addressbookcontent').hide();
                            return;
                        }
                        */
                        $('.invitechatname').val(  $(this).find('.addressbook1').text().trim() );
                        $('.invitechatemail').val( $(this).find('.addressbook3').text().trim() );
                        $('.invitechatsms').val( $(this).find('.addressbook4').text().trim() );
                        $('.invitechathandle').val( $(this).find('.addressbook6').text().trim() );

                        $('.addressbookcontent').hide();
                    });


                }
            });    
        }
        PreStartup();
        if(TermsOfUseCheck()){
            RunAtStartup();
        }
        
        function GetPinEntry( event, value )
        {
            if(TryPin === ''){
                $('.pintextview').html("");
            }
            TryPin = TryPin + value;
            var hold = $('.pintextview').html();
            hold += "*";
            $('.pintextview').html(hold);
            
            if( value==='l' || pintries > 4 ){
                TryPin = "";
                
                try {

                    localStorage.removeItem("swt");
                    localStorage.removeItem("password");
                    localStorage.removeItem("pw");
                    localStorage.removeItem("lchat");
                    localStorage.removeItem("chat");

                } catch(err) {}
                
                AbortAjax();
                $.ajax({
                    url: rootserver+'keypad_unlock.php',
                    context: document.body,
                    type: 'POST',
                    data: 
                    { 
                    }

                }).done(function( data, status ) {
                    $('.socialwindow').html('');
                });
                if(mobileversion!=='000' && mobileversion!==''  ){
                    NativeCall("restart");
                    return;
                }
                
                window.location = rootserver1+startupphp+"?s="+source+"&e="+enterprise+"&apn="+apn+"&gcm="+gcm+"&v="+mobileversion;
                return;
            } else
            if( value==='x' ){
                
                TryPin = "";
                pintries = 0;
                $('.pintextview').html("");
                
            } else {
                //alertify.alert(pin);
                
            }
            if(TryPin.length === 4){
                pintries = pintries +1;
                if(TryPin === pin){
                    
                    
                    AbortAjax();
                    $.ajax({
                        url: rootserver+'keypad_unlock.php',
                        context: document.body,
                        type: 'POST',
                        data: 
                        { 
                        }

                    }).done(function( data, status ) {
                        if(mobileversion!=='000' && mobileversion!==''  ){
                            NativeCall("restart");
                            return;
                        }
                        
                        window.location = rootserver1+startupphp+"?s="+source+"&e="+enterprise+"&apn="+apn+"&gcm="+gcm+"&v="+mobileversion;
                   });
                    
                    
                    //alertify.alert(pin);
                    //$('#trigger_tilebutton').click();
                } else {
                    alertify.alert( TryPin+' rejected' );
                }
                TryPin = "";
                pintries = 0;
                //$('.pintextview').html("");
            }
            
        }
        function videoLoad() {
                console.log('Video loading');

                document.getElementById('video').style.display = 'none'; // replace video element with
                document.getElementById('poster').style.display = 'block'; // poster while buffering

                document.getElementById('video-progress-container').innerHTML = 'Buffering: <progress id="video-progress" value="0" max="100">0%</progress>';
                document.getElementById('video-button').style.display = 'none'; // no longer needed

                videoBuffer.load();
        }

        function videoProgress(percentBuffered) {
                console.log('Video progress: ' + percentBuffered + '%');
                document.getElementById('video-progress').setAttribute('value', percentBuffered);
                document.getElementById('video-progress').innerHTML = percentBuffered + '%';
        }

        function videoReady() {
                console.log('Video ready!');

                document.getElementById('video-progress').setAttribute('value', 100);
                document.getElementById('video-progress').innerHTML = '100%';

                document.getElementById('video').style.display = 'block'; // restore video element
                document.getElementById('poster').style.display = 'none';

                document.getElementById('video').setAttribute('controls', 'controls');

                document.getElementById('video').play();
        }

        // ================================================================================

        function audioLoad() {
                console.log('Audio loading');

                document.getElementById('audio-progress-container').innerHTML = 'Buffering: <progress id="audio-progress" value="0" max="100">0%</progress>';
                document.getElementById('audio-button').style.display = 'none'; // no longer needed

                audioBuffer.load();
        }

        function audioProgress(percentBuffered) {
                console.log('Audio progress: ' + percentBuffered + '%');
                document.getElementById('audio-progress').setAttribute('value', percentBuffered);
                document.getElementById('audio-progress').innerHTML = percentBuffered + '%';
        }

        function audioReady() {
                console.log('Audio ready!');

                document.getElementById('audio-progress').setAttribute('value', 100);
                document.getElementById('audio-progress').innerHTML = '100%';

                document.getElementById('audio').play();
        }	        
        function NativeCall(command)
        {
            var pid = $('#pid').val();
            //Not supported prior to 201
            if(command==='nosleep' || command==='sleep'){
                //do not execute here
                return;
            }
            if( mobileversion === '' ){
                alertify.alert("Please download the mobile app to access these features");
                return;
            }
            if(MobileType ==='A'){
                window.location = "https://brax.me/command/"+command;
            }
            if(MobileType ==='I'){
                window.location = "https://brax.me/command/"+command;
            }
            return;
            if(mobileversion === '201'){
                window.location = "https://brax.me/command/"+command;
                return;
            }
            if(mobileversion === '200'){
                window.location = "http://brax.me/command/"+command;
                return;
            }
            //} else {
            //    if(command === 'photofilepicker'){
            //        command = 'photolibrary';
            //    }
            //    window.open("braxme://"+command,"_self");
            //}
            
        }
        
        $("body").on("click",".gift", function() {
            
            var step;
            for (step = 1; step < 100; step++) {            
                setTimeout(function() {
                    GiveHeart('heart');
                }, step*100);
            }
        });
        $("body").on("click",".thanks", function() {
            
            var step;
            for (step = 1; step < 100; step++) {            
                setTimeout(function() {
                    GiveHeart('star');
                }, step*100);
            }
        });
        $("body").on("click",".money", function() {
            
            var step;
            for (step = 1; step < 100; step++) {            
                setTimeout(function() {
                    GiveHeart('money');
                }, step*100);
            }
        });
        $('body').on('click','.savetip', function()
        {
                var lasttip = $(this).data('tip');
                
                $.ajax({
                    url: rootserver+'tip.php?'+timeStamp(),
                    context: document.body,
                    type: 'POST',
                    data: 
                     { 
                    'providerid': $('#pid').val(),
                    'lasttip': lasttip
                     }

                }).done(function( data, status ) {
                });
        });

    
       
});



