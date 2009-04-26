jQuery.fn.iPhone = {

	/*

	Name: jQuery iPhone Plugin
	Release: 0.1.2
	Date: 2008-04-21 15:20:45

	About jQuery iPhone Plugin
	--------------------------

		jQuery is a lightweight JavaScript library that emphasizes interaction between JavaScript and HTML, allowing you to easily include many components that enrich and benefit the developer's coding experience.

		jQuery iPhone Plugin extends jQuery's functionality for specific iPhone usages.

		Further documentation for jQuery can be found at: http://docs.jquery.com/Tutorials:How_jQuery_Works

		Javascript Example of jQuery:
			$(document).ready(
				function(){
					$('body').html('<p>The entire site body was replaced with this text before you even saw it!</p>');
				}
			);


	Object: version
	---------------

		version allows you to detect the current version of Safari and Webkit currently installed on the iPhone.

		If no version of safari or webkit is present, either will return false.

		Javascript Example of version:
			$(document).iPhone.orientchange(
				alert('Your current version of safari is: ' + $.iPhone.version.safari);
				alert('Your current version of webkit is: ' + $.iPhone.version.webkit);
			);


	Function: orientchange
	----------------------

		orientchange allows you to write javascript that is to be executed when the user rotates their iPhone display.

		By default one function is passed and this function will execute on any iPhone rotation.

		Javascript Example of orientchange:
			$(document).iPhone.orientchange(
				function(){
					alert('the iphone has rotated the screen');
				}
			);

		By passing in a second function, separated by a comma, you can now specify separate functions for portrait and landscape rotations.

		Javascript Example of orientchange (two arguments):
			$(document).iPhone.orientchange(
				function(){
					alert('the iphone has rotated the screen to portrait');
				},
				function(){
					alert('the iphone has rotated the screen to landscape');
				}
			);

		By passing in a third function, separated by a comma, you can now specify separate functions for portrait, 90 degree landscape, and -90 degree landscape rotations.

		Javascript Example of orientchange (three arguments):
			$(document).iPhone.orientchange(
				function() {
					alert('the iphone has rotated the screen to portrait');
				},
				function() {
					alert('the iphone has rotated the screen to a 90 degree landscape');
				},
				function() {
					alert('the iphone has rotated the screen to a -90 degree landscape');
				}
			);


	Function: hideURLbar
	--------------------

		hideURLbar allows you to tuck the URL bar above the visibile portion of the screen if the URL bar is currently visible.
	
		By default hideURLbar will check to see if the position of the page would already indicate that the address bar was hidden.

		Javascript Example of hideURLbar:
			$(document).ready(
				function(){
					$.iPhone.hideURLbar(); // This will hide the URL bar when the page first appears.
				}
			);

		By passing an argument into hideURLbar you can now force it to shift the user to the top of the document where the URL bar is still hidden.

		HTML Example of hideURLbar:
			<!-- TAKES USER TO THE TOP OF THE PAGE WITHOUT SHOWING THE ADDRESS BAR -->
			<a href="javascript:$.iPhone.hideURLbar(1);">Back to top</a>


	Function: disableTextSizeAdjust
	-------------------------------

		disableTextSizeAdjust allows you to keep the iPhone from automatically resizing text on your page.

		Javascript Example of disableTextSizeAdjust:
			$(document).ready(
				function(){
					$.iPhone.disableTextSizeAdjust();
				}
			);

		If the function is excuted after the page has loaded, the text may resize itself to it's originally intended size, where it will then cease resizing.

		HTML Example of disableTextSizeAdjust:
			<a href="javascript:$.iPhone.disableTextSizeAdjust();"></a>


	Function: enableTextSizeAdjust
	--------------------

		enableTextSizeAdjust allows you to specify or reenable the iPhone to automatically resize text on your page.

		Javascript Example of enableTextSizeAdjust:
			$(document).ready(
				function(){
					$.iPhone.enableTextSizeAdjust();
				}
			);

		If the function is excuted after the page has loaded and text resizing has been previously disabled, the text may resize itself to it's newly desired size, where it will then continue to resize.

		HTML Example of enableTextSizeAdjust:
			<a href="javascript:$.iPhone.enableTextSizeAdjust();"></a>

	*/

	version: {
		safari: (/AppleWebKit\/([^\s]+)/.exec(navigator.userAgent) || [,false])[1],
		webkit: (/Safari\/(.+)/.exec(navigator.userAgent) || [,false])[1]
	},

	orientchange: function(p,l,r) {
		p = p||function(){};
		l = l||p;
		r = r||l;
		window.onorientationchange = function() {
			switch(window.orientation) {
				case 0:
					p();
					break;
				case 90:
					l();
					break;
				case -90:
					r();
					break;
			};
		};
	},

	hideURLbar: function(f) {
		if(window.innerHeight < (window.outerHeight+20)) { $('html').css({'min-height':(window.outerHeight+20)+'px'}); }
		setTimeout(function() { if(window.pageYOffset<1||f) { window.scrollTo(0, 1); jQuery.iPhone.hideURLbar(); } }, 0);
	},

	disableTextSizeAdjust: function() {
		jQuery('html').css({'-webkit-text-size-adjust':'none'});
	},

	enableTextSizeAdjust: function() {
		jQuery('html').css({'-webkit-text-size-adjust':'auto'});
	}
};

/* This will slow the timer on jQuery from 13ms to 83ms, or roughly 12fps */
jQuery.timerId = setInterval(function(){
	var timers = jQuery.timers;
	for (var i = 0; i < timers.length; i++) {
		if (!timers[i]()) {
			timers.splice(i--, 1);
		}
	}
	if (!timers.length) {
		clearInterval(jQuery.timerId);
		jQuery.timerId = null;
	}
}, 83);

/* This will allow you to call the iPhone plugin as $.iPhone too */
jQuery.iPhone = jQuery(document).iPhone;