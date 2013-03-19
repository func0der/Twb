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
		
		// handle notification messages
		Twb.msg.init();
		
		// make some UI components stiky
		Twb.stickyUI();
		
		// init forms behaviors
		Twb.formInputPopover();
		Twb.formErrorsHandling('.form-standard,.form-horizontal');
		Twb.formErrorsTooltip('.form-horizontal');
				
	});
	
	
	
	
	
	
	
	
// --------------------------------- //
// ---[[   S T I C K Y   U I   ]]--- //
// --------------------------------- //
	
	Twb.stickyUI = function() {
		
		// disable stiky to IE!
		if (!$.support.cssFloat) return;
		
		var $top = $('#twb-sticky-pageheader');
		var $bottom = $('#twb-sticky-form .form-actions');
		
		// initialize pageheader
		if ($top.length) {
			var $ghost = $('<div>').html("&nbsp;").addClass('twb-sticky-ghost').css({
				height:		$top.outerHeight(true)
			});
			$top.addClass('twb-sticky').after($ghost);
			// move standard flash messages after ghost item!
			if ($('#twb-flash-messages .alert').length) $ghost.after($('#twb-flash-messages .alert'));
		}
		
		// initialize form actions
		if ($bottom.length) {
			$bottom.addClass('twb-sticky').after($('<div>').html("&nbsp;").addClass('twb-sticky-ghost').css({
				height:		$bottom.outerHeight()
			}));
		}
		
		// window resize utility
		var _onResize = function() {
			var _w = $(window).outerWidth();
			if ($top.length) $top.css({
				width: 	_w,
				top:	$('.navbar-fixed-top').length ? $('.navbar-fixed-top').outerHeight(true) : 0
			});
			if ($bottom.length) $bottom.css({
				width: 	_w,
				bottom: $('.navbar-fixed-bottom').length ? $('.navbar-fixed-bottom').outerHeight(true) : 0
			});
		}
		
		$(window).resize(_onResize);
		_onResize();
		
	};
	
	
	
	
	
	
	
	
	
// ------------------------------------------------------------- //
// ---[[   N O T I F I C A T I O N S   F R A M E W O R K   ]]--- //	
// ------------------------------------------------------------- //
	
	// look at:
	// http://pinesframework.org/pnotify/
	Twb.msg = {
		success: function(text, title) {
			$.pnotify({
				title: 	title,
				text: 	text,
				type: 	'success'
			});
		},
		error: function(text, title) {
			$.pnotify({
				title: 	title,
				text: 	text,
				type: 	'error'
			});
		},
		warning: function(text, title) {
			$.pnotify({
				title: 	title,
				text: 	text,
				hide: false
			});
		},
		info: function(text, title) {
			$.pnotify({
				title: 	title,
				text: 	text,
				type: 	'info',
				hide: false
			});
		},
		
		init: function() {
			// pnotify defaults
			$.pnotify.defaults.history = false;
			$.pnotify.defaults.sticker = false;
			$.pnotify.defaults.icon = false;
			$.pnotify.defaults.addclass = 'pnotify-stack-bottom-right';
			$.pnotify.defaults.stack = {"dir1": "up", "dir2": "left", "push": "top"};
			$.pnotify.defaults.animate_speed = 'normal';
			// convert session messages into notifications
			$('.alert').each(function() {
				var $this = $(this).hide();
				$this.find('button').remove();
				
				// extract notice title
				var $title, title = '';
				$title = $this.find('h4');
				if ($title.length) {
					title = $title.text();
					$title.remove();
				}
				
				if ($this.hasClass('alert-error')) {
					Twb.msg.error($this.text(), title);
					
				} else if ($this.hasClass('alert-success')) {
					Twb.msg.success($this.text(), title);
					
				} else if ($this.hasClass('alert-warning')) {
					Twb.msg.warning($this.text(), title);
					
				} else if ($this.hasClass('alert-info')) {
					Twb.msg.info($this.text(), title);
					
				}
				
			});
		}
	};
	
	
	
	
	
	
	
	
// --------------------------------------------- //
// ---[[   F O R M   M A N A G E M E N T   ]]--- //	
// --------------------------------------------- //
	
	/**
	 * activates form input's popover helper
	 */
	Twb.formInputPopover = function() {
		$('input[data-helper="on"]').each(function() {
			$(this).popover();
		});
	};
	
	/**
	 * Removes form error styles when focus on fields
	 */
	Twb.formErrorsHandling = function(form) {
		var $form = $(form);
		var _clearFieldError = function($control) {
			// hide error message (preserve control's height)
			$control.find('.help-inline:hidden').remove();
			$control.find('.help-inline:visible').each(function() {
				$control.css('min-height', $control.outerHeight());
				$(this).fadeOut(function(){$(this).remove()});
			});
			// remove error status class
			setTimeout(function() {
				$control.removeClass('error');
				$control.find('.form-error').removeClass('form-error');
			}, 300);
		}
		$form.find('.control-group.error').each(function() {
			var $this = $(this);
			// bind clear error
			$this.bind('click', function(){
				_clearFieldError($this);
			});
			$this.find('input, select').bind('focus', function() {
				_clearFieldError($this);
			});
		});
	}
	
	/**
	 * intercepts form errors messages and transform into tooltips
	 * form error status is removed when field gets focus
	 */
	Twb.formErrorsTooltip = function(form) {
		$(form).find('.control-group.error').each(function() {
			var $this = $(this);
			var $tooltipHandler = $('<span>');
			var $msg = $this.find('.help-inline').hide().after($tooltipHandler);
			
			// attach tooltip & show
			$tooltipHandler.attr('data-toggle', 'tooltip')
				.attr('data-placement', 'right')
				.attr('title', $msg.text())
				.tooltip('show');
			
			// apply custom style
			$tooltipHandler.tooltipColor('#900');
			
			// bind clear error login
			$this.bind('click', function(){
				$tooltipHandler.tooltip('hide');
			});
			
			$this.find('input, select').bind('focus', function() {
				$tooltipHandler.tooltip('hide');
			});
		});
	};
	
	
	
	
	
	
	
	
	
	
	










// ----------------------------- //
// ---[[   P L U G I N S   ]]--- //	
// ----------------------------- //
	
	
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
