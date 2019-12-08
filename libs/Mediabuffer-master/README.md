#Mediabuffer

Buffer HTML5 audio/video for uninterrupted playback.

Provides a workaround for [Chrome's incorrect `canplaythrough` behaviour](https://code.google.com/p/chromium/issues/detail?id=73609) and adds some other useful functionality.

###[Demo](http://static.simianstudios.com/mb/)

Native JavaScript, no dependencies.

Released under the MIT license, based on [code by Denis Nazarov](https://github.com/denisnazarov/canplaythrough).

Uses [code by Michael Zaporozhets](http://stackoverflow.com/a/11381730/1646470), based on [detectmobilebrowsers.com by Chad Smith](http://detectmobilebrowsers.com/).

----------

##Usage
```javascript
var foo = new Mediabuffer(element, progressCallback, readyCallback[, disableMobileCheck[, forceFullDownload]]);
foo.load();
```

###element
The `<audio>` or `<video>` element you want to buffer e.g. `document.getElementById('example')`. The media source should be defined before initialising Mediabuffer using the element's `src` attribute or `<source>` child elements.

###progressCallback
Function to run as the media buffers. Takes a single parameter, `percentBuffered` which you can use to provide feedback to the user as the file buffers.

###readyCallback
Function to run when the file is buffered. You'll probably want to use the element's `play()` function here.

###disableMobileCheck
Optional boolean to disable the check for mobile browsers. Defaults to `false`.

###forceFullDownload
Optional boolean to force the browser to buffer the whole media file before calling the `readyCallback`. Defaults to `false`.

----------

##Example

```javascript
function progress(percentBuffered) {
	console.log('Buffering: ' + percentBuffered + '%');
}

function ready() {
	console.log('Ready!');
	document.getElementById('example').play();
}

var mbExample = new Mediabuffer(document.getElementById('example'), progress, ready);
mbExample.load();
```

----------

##Browser Compatibility
Tested and working in Chrome 40, Firefox 35, Safari 7.1.3 (see notes), Opera 12.16 and Internet Explorer 9+. *Should* work in anything that supports HTML5 media elements, the `preload` attribute and the `progress` media event. Mobile browsers will fail gracefully by calling the `readyCallback` immediately due to the lack of preloading in the major mobile browsers.

###Notes
* Safari won't call the `progressCallback` if the buffer time is short, but it will call the `readyCallback` as expected. 
* Mobile browsers will only play media as the result of a user action so if you want your media to auto-play after buffering you'll need to make sure your `.load()` call is triggered by a user-initated event such as a `click`.

----------

##Known issues
* Mediabuffer works by measuring the element's download progress and estimating when it can be played back without further buffering. If a user's average download rate drops significantly after buffering completes they may still experience buffering towards the end of playback. If it's absolutely imperative that there is no chance of buffering, use the `forceFullDownload` option.
* Chrome has [a ~~bug~~feature](https://code.google.com/p/chromium/issues/detail?id=111281) that means it won't buffer past a certain point without a [workaround](https://code.google.com/p/chromium/issues/detail?id=111281#c82). That's OK except that if you have controls visible they won't work as the user may expect and it can cause them to flicker between states. If you want to enable controls your best bet is to have them hidden initially and then show them in your `readyCallback`. For the best user feedback, you could replace your controls with an indicator of the loading status by using your `progressCallback`.

----------

##Changelog
v1.1 - added `forceFullDownload` option.

v1.0 - initial release.
