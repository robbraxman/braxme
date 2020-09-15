'use strict';


function showNotification( event ) {  
    
    var title = 'Brax.Me Activity';
    
    var notificationOptions = {  
        body:  'Notification',  
        icon:  'https://brax.me/img/logo-b1a.png',  
        tag:   'brax-me-push-notification-tag',  
        data:   'p=test&t=a' 
    };  
    
    fetch("notificationworker.php/"+notificationOptions.data 
            ,{
        method: 'get'
    }
            
            ).then(function(response) {            
            response.text().then(function(text) {
                self.registration.showNotification(title, notificationOptions);  
            })
    })
  /*
   * this causes message - this web site has been updated in the background
    ,
    {
        method: 'post',
        headers: {  
            "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"  
        },  
        body: notificationOptions.data  }
        */
  return;  
}

self.addEventListener('push', function(event) {
    //console.log('Received a push message', event);

  event.waitUntil(
    showNotification( event )
  );
});




self.addEventListener('notificationclick', function(event) {
  //console.log('On notification click: ', event.notification.tag);
  // Android doesn’t close the notification when you click on it
  // See: http://crbug.com/463146
  event.notification.close();

  // This looks to see if the current is already open and
  // focuses if it is
  event.waitUntil(clients.matchAll({
    type: "window"
  }).then(function(clientList) {
        for (var i = 0; i < clientList.length; i++) {
          var client = clientList[i];
          if (client.url === '/' && 'focus' in client)
            return client.focus();
        }
        if (clients.openWindow){
          return clients.openWindow('/l.php');
        }
  }));

});

