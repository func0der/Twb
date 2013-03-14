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
		Twb.formInputPopover();
		Twb.formErrorsTooltip($('.form-horizontal'));
	});
	
	
	
	/**
	 * activates form input's popover helper
	 */
	Twb.formInputPopover = function() {
		$('input[data-helper="on"]').each(function() {
			$(this).popover();
		});
	};
	
	/**
	 * intercepts form errors messages and transform into tooltips
	 * form error status is removed when field gets focus
	 */
	Twb.formErrorsTooltip = function($form) {
		
		var _clearFieldError = function($control, $tooltipHandler) {
			$tooltipHandler.tooltip('hide');
			$control.removeClass('error');
			$control.find('.form-error').removeClass('form-error');
		};
		
		$form.find('.control-group.error').each(function() {
			var $this = $(this);
			var $tooltipHandler = $('<span>');
			var $msg = $this.find('.help-inline').hide().after($tooltipHandler);
			var msg = $msg.text();
			
			// attach tooltip & show
			$tooltipHandler.attr('data-toggle', 'tooltip')
				.attr('data-placement', 'right')
				.attr('title', msg)
				.tooltip('show');
			
			// apply custom style
			//$this.find('.tooltip-arrow').css('border-right-color', '#900');
			//$this.find('.tooltip-inner').css('background-color', '#900');
			$tooltipHandler.tooltipColor('#900');
			
			// bind clear error login
			$this.bind('click', function(){
				_clearFieldError($this, $tooltipHandler);
			});
			
			$this.find('input, select').bind('focus', function() {
				_clearFieldError($this, $tooltipHandler);
			});
			
		});
		
	};
	
	
	/**
	 * change tooltip's color
	 * jQuery Plugin
	 */
	$.fn.tooltipColor = function(color) {
		$(this).each(function(){
			var $this = $(this);

			// apply to a tooltip handler
			if ($this.data('tooltip')) {
				$this = $this.data('tooltip').$tip;
			}

			// apply custom color
			$this.find('.tooltip-inner').css('background-color', color)
			if ($this.hasClass('right'))	$this.find('.tooltip-arrow').css('border-right-color', color);
			if ($this.hasClass('top'))		$this.find('.tooltip-arrow').css('border-top-color', color);
			if ($this.hasClass('left'))		$this.find('.tooltip-arrow').css('border-left-color', color);
			if ($this.hasClass('bottom'))	$this.find('.tooltip-arrow').css('border-bottom-color', color);
		});
		return this;
	}
	

}(window.jQuery);
