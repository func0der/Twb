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
		Twb.noty.init();
		
		Twb.formInputPopover();
		Twb.formStaticActions('#twb-main-form');
		Twb.formErrorsHandling('.form-standard,.form-horizontal');
		Twb.formErrorsTooltip('.form-horizontal');
				
	});
	
	
	
	
	
	
	
	
	// look at:
	// http://pinesframework.org/pnotify/
	
	Twb.noty = {
		success: function(msg) {
			if ($.notify) {
				$.notify.success(msg, {
					autoClose: 5000,
					close: true
				});
			} else if (window.noty) {
				window.noty({
					text: msg,
					type: 'success',
					layout: 'bottomRight'
				});
			}
		},
		error: function(msg) {
			if ($.notify) {
				$.notify.error(msg, {
					autoClose: 5000,
					close: true
				});
			} else if (window.noty) {
				window.noty({
					text: msg,
					type: 'error',
					layout: 'bottomRight'
				});
			}
		},
		warning: function(msg) {
			if ($.notify) {
				$.notify.alert(msg, {
					autoClose: 5000,
					close: true
				});
			} else if (window.noty) {
				window.noty({
					text: msg,
					type: 'warning',
					layout: 'bottomRight'
				});
			}
		},
		info: function(msg) {
			if ($.notify) {
				$.notify.basic(msg, {
					autoClose: 5000,
					close: true
				});
			} else if (window.noty) {
				window.noty({
					text: msg,
					type: 'information',
					layout: 'bottomRight'
				});
			}
		},
		
		init: function() {
			$('.alert').each(function() {
				var $this = $(this).hide();
				$this.find('button').remove();
				
				if ($this.hasClass('alert-error')) {
					Twb.noty.error($this.html());
				} else if ($this.hasClass('alert-success')) {
					Twb.noty.success($this.html());
				} else if ($this.hasClass('alert-warning')) {
					Twb.noty.warning($this.html());
				} else if ($this.hasClass('alert-info')) {
					Twb.noty.info($this.html());
				}
				
			});
		}
	};
	
	
	
	
	
	
	
	
// --------------------------------------------- //
// ---[[   F O R M   M A N A G E M E N T   ]]--- //	
// --------------------------------------------- //
	
	/**
	 * Staticize form's action buttons at the bottom of the visible page
	 */
	Twb.formStaticActions = function(form) {
		var $form = $(form);
		var $actions = $form.find('.form-actions');
		
		var _offsetBottom = 0;
		var $footer = $('.navbar-fixed-bottom');
		if ($footer.length) {
			_offsetBottom = $footer.outerHeight(true);
		}
		
		var onResize = function() {
			$actions.css({
				position: 'fixed',
				left:0,
				margin:0,
				top: $(window).height() - (_offsetBottom + $actions.outerHeight()),
				width: $(window).outerWidth()
			});	
		};
		$(window).resize(onResize);
		onResize();
	};
	
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
