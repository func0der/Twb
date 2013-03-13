/* 
 * Twb Plugin
 */


/**
 * Global Namespace
 */
window.Twb = {};







/**
 * Private Closure
 */
!function ($) {
	
	/**
	 * jQuery Ready - Launcher
	 */
	$(document).ready(function() {
		Twb.formInputPopoverLauncher();
	});
	
	
	
	/**
	 * activates form input's popover helper
	 */
	Twb.formInputPopoverLauncher = function() {
		$('input[data-helper="on"]').each(function() {
			$(this).popover();
		});
	};
	

}(window.jQuery);
