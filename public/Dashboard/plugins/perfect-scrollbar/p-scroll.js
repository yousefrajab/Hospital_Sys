(function($) {
	"use strict";
	
	//P-scrolling
	
	
	if (document.querySelector('.chat-scroll')) new PerfectScrollbar('.chat-scroll', {
	  useBothWheelAxes:true,
	  suppressScrollX:true,
	});
	if (document.querySelector('.Notification-scroll')) new PerfectScrollbar('.Notification-scroll', {
	  useBothWheelAxes:true,
	  suppressScrollX:true,
	});
	
	
	
})(jQuery);