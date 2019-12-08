'use strict';

var API_KEY = 'AIzaSyAMiz7TJ-8WMPBr7QrlVy6RD30eL7bgunY';
var GCM_ENDPOINT = 'https://android.googleapis.com/gcm/send';

var curlCommandDiv = document.querySelector('.js-curl-command');
var isPushEnabled = false;
var serviceWorker = null;

// This method handles the removal of subscriptionId
// in Chrome 44 by concatenating the subscription Id
// to the subscription endpoint
function endpointWorkaround(pushSubscription) {
  // Make sure we only mess with GCM
  if (pushSubscription.endpoint.indexOf('https://android.googleapis.com/gcm/send') !== 0) {
    return pushSubscription.endpoint;
  }

  var mergedEndpoint = pushSubscription.endpoint;
  // Chrome 42 + 43 will not have the subscriptionId attached
  // to the endpoint.
  if (pushSubscription.subscriptionId &&
    pushSubscription.endpoint.indexOf(pushSubscription.subscriptionId) === -1) {
    // Handle version 42 where you have separate subId and Endpoint
    mergedEndpoint = pushSubscription.endpoint + '/' +
      pushSubscription.subscriptionId;
  }
  return mergedEndpoint;
}

function sendSubscriptionToServer(subscription, source) {

  var mergedEndpoint = endpointWorkaround(subscription);
  
  var endpointSections = mergedEndpoint.split('/');
  var subscriptionId = endpointSections[endpointSections.length - 1];
  
    $.ajax({
        url: 'notifywebstore.php?'+timeStamp(),
        context: document.body,
        type: 'POST',
        data:
         { 
             'gcm': subscriptionId,
             'mode' : 'A'
         }
    }).done(function(){
        localStorage.notification = true;
        //if( source ){
        //    alertify.alert("Web push notifications enabled.")
        //}
    });
  
  
  
  //alertify.alert(mergedEndpoint);
  // This is just for demo purposes / an easy to test by
  // generating the appropriate cURL command
  //showCurlCommand(mergedEndpoint);
}

// NOTE: This code is only suitable for GCM endpoints,
// When another browser has a working version, alter
// this to send a PUSH request directly to the endpoint
function showCurlCommand(mergedEndpoint) {
  // The curl command to trigger a push message straight from GCM
  if (mergedEndpoint.indexOf(GCM_ENDPOINT) !== 0) {
    //window.Demo.debug.log('This browser isn\'t currently ' +
    //  'supported for this demo');
    return;
  }

  var endpointSections = mergedEndpoint.split('/');
  var subscriptionId = endpointSections[endpointSections.length - 1];

  var curlCommand = 'curl --header "Authorization: key=' + API_KEY +
    '" --header Content-Type:"application/json" ' + GCM_ENDPOINT +
    ' -d "{\\"registration_ids\\":[\\"' + subscriptionId + '\\"]}"';

  curlCommandDiv.textContent = curlCommand;
}

function notificationunsubscribe() {
  var pushButton = document.querySelector('.js-push-button');
  //pushButton.disabled = true;

  //curlCommandDiv.textContent = '';

  navigator.serviceWorker.ready.then(function(serviceWorkerRegistration) {
    // To unsubscribe from push messaging, you need get the
    // subcription object, which you can call unsubscribe() on.
    serviceWorkerRegistration.pushManager.getSubscription().then(
      function(pushSubscription) {
        // Check we have a subscription to unsubscribe
        if (!pushSubscription) {
          // No subscription object, so set the state
          // to allow the user to subscribe to push
          isPushEnabled = false;
          pushButton.disabled = false;
          pushButton.textContent = 'Enable Push Notifications';
          console.log("Enable Push Notifications");
          return;
        }
        
        notificationsendpid(localStorage.pid);
        console.log("Store pid = "+localStorage.pid);
        
        $.ajax({
            url: 'notifywebstore.php?'+timeStamp(),
            context: document.body,
            type: 'POST',
            data:
             { 
                 'mode' : 'D'
             }       
            }).done(function(data){
                if(data!==''){
                    alertify.alert(data);
                }
            });

        // TODO: Make a request to your server to remove
        // the users data from your data store so you
        // don't attempt to send them push messages anymore

        // We have a subcription, so call unsubscribe on it
        pushSubscription.unsubscribe().then(function(successful) {
          pushButton.disabled = false;
          pushButton.textContent = 'Enable Push Notifications';
          isPushEnabled = false;
          console.log("Unsubscribe");
        }).catch(function(e) {
          // We failed to unsubscribe, this can lead to
          // an unusual state, so may be best to remove
          // the subscription id from your data store and
          // inform the user that you disabled push
          //alertify.alert("Could not unsubscribe to push notifications");
          //window.Demo.debug.log('Unsubscription error: ', e);
          pushButton.disabled = false;
        });
      }).catch(function(e) {
        //window.Demo.debug.log('Error thrown while unsubscribing from ' +
        //  'push messaging.', e);
      });
  });
}

function notificationsubscribe(source) {
  // Disable the button so it can't be changed while
  // we process the permission request
  var pushButton = document.querySelector('.js-push-button');
  pushButton.disabled = true;

  navigator.serviceWorker.ready.then(function(serviceWorkerRegistration) {
    serviceWorkerRegistration.pushManager.subscribe({userVisibleOnly: true})
      .then(function(subscription) {
        // The subscription was successful
        isPushEnabled = true;
        pushButton.textContent = 'Disable Push Notifications';
        pushButton.disabled = false;
        console.log("Subscribe");

        // TODO: Send the subscription subscription.endpoint
        // to your server and save it to send a push message
        // at a later date
        return sendSubscriptionToServer(subscription, source);
      })
      .catch(function(e) {
        if (Notification.permission === 'denied') {
          // The user denied the notification permission which
          // means we failed to subscribe and the user will need
          // to manually change the notification permission to
          // subscribe to push messages
          //window.Demo.debug.log('Permission for Notifications was denied');
          console.log("Subscribe Denied");
          pushButton.disabled = true;
        } else {
          // A problem occurred with the subscription, this can
          // often be down to an issue or lack of the gcm_sender_id
          // and / or gcm_user_visible_only
          //window.Demo.debug.log('Unable to subscribe to push.', e);
          pushButton.disabled = false;
          pushButton.textContent = 'Enable Push Notifications (error)';
          console.log("Subscribe Error");
        }
      });
  });
}

// Once the service worker is registered set the initial state
function notificationinitialiseState() {
    
    var isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);  
    if(!isChrome){
        //$('.js-push-button').hide();
        //return;
    }
    var isAndroid = /Android/.test(navigator.userAgent);  
    if(isAndroid){
        $('.js-push-button').hide();
        return;
    }
    
  // Are Notifications supported in the service worker?
  if (!('showNotification' in ServiceWorkerRegistration.prototype)) {
    //window.Demo.debug.log('Notifications aren\'t supported.');
    return;
  }

  // Check the current Notification permission.
  // If its denied, it's a permanent block until the
  // user changes the permission
  if (Notification.permission === 'denied') {
    //window.Demo.debug.log('The user has blocked notifications.');
    return;
  }

  // Check if push messaging is supported
  if (!('PushManager' in window)) {
    //window.Demo.debug.log('Push messaging isn\'t supported.');
    return;
  }
  

  // We need the service worker registration to check for a subscription
  navigator.serviceWorker.ready.then(function(serviceWorkerRegistration) {
    // Do we already have a push message subscription?
    serviceWorkerRegistration.pushManager.getSubscription()
      .then(function(subscription) {
        // Enable any UI which subscribes / unsubscribes from
        // push messages.
        var pushButton = document.querySelector('.js-push-button');
        pushButton.disabled = false;
        

        if (!subscription) {
            if(typeof localStorage.notification === "undefined" )
            {
                setTimeout(function(){
                    $('#trigger_notifysubscribe').trigger('click');
                    //$(document).trigger('click','.mainbutton');
                }, 50);
                return;
            }
            
            notificationsubscribe(false);
            //notificationsubscribe();
          // We aren’t subscribed to push, so set UI
          // to allow the user to enable push
          return;
        }

        // Keep your server in sync with the latest subscription
        sendSubscriptionToServer(subscription, false);

        // Set your UI to show they have subscribed for
        // push messages
        pushButton.textContent = 'Disable Push Notifications';
        isPushEnabled = true;
      })
      .catch(function(err) {
        //window.Demo.debug.log('Error during getSubscription()', err);
      });
  });
}

window.addEventListener('load', function() {
  var pushButton = document.querySelector('.js-push-button');
  
    //$('.js-push-button').show();
  
    var isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor)  ;  
    if(!isChrome){
        $('.js-push-button').hide();
        return;
    }
    $('.js-push-button').show();
  
  
  pushButton.addEventListener('click', function() {
    if (isPushEnabled) {
      notificationunsubscribe();
    } else {
      notificationsubscribe();
    }
  });

  // Check that service workers are supported, if so, progressively
  // enhance and add push messaging support, otherwise continue without it.
  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('./service-worker.js')
    .then(notificationinitialiseState);
  } else {
    //window.Demo.debug.log('Service workers aren\'t supported in this browser.');
  }
});

function notificationsendpid( pid ) {
    navigator.serviceWorker.controller.postMessage({token: localStorage.pid});
};
