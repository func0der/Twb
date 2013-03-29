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
	 * Setup a generic ajax indicator
	 */	
	$.ajaxSetup({
		beforeSend: function() {
			Twb.loading(true)
		},
		complete: function() {
			Twb.loading(false)
		}
	});
	
	
	/**
	 * jQuery Ready - Launcher
	 */
	$(document).ready(function() {
		
		// Collect some flags about client configuration
		Twb.is = {
			ie:			!$.support.cssFloat,
			mobile: 	/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent),
			phone:		/iPhone|iPod/i.test(navigator.userAgent),
			tablet:		/Android|webOS|iPad|BlackBerry/i.test(navigator.userAgent),
			ios:		/iPhone|iPad|iPod/i.test(navigator.userAgent),
			iphone:		/iPhone/i.test(navigator.userAgent),
			ipad:		/iPad/i.test(navigator.userAgent),
		};
		
		
		// handle notification messages
		Twb.msg.init();
		
		// make some UI components stiky
		Twb.stickyUI();
		
		// init forms behaviors
		Twb.formInputPopover();
		Twb.formErrorsAction('.form-standard, .form-horizontal');
		Twb.formErrorsTooltip('.form-horizontal');
		Twb.ajaxForm('form[data-twb-ajax=on]');
		
		// init media table plugin for configured tables
		Twb.mediaTable();
		
		// textarea grows and shrinks
		$('textarea[data-autosize="on"],textarea[data-autosize="true"],textarea[data-autosize=1]').data('autosize', null).autosize({append: "\n"})
		
	});
	
	
	
	
	
	
// ------------------------------------------------------------- //
// ---[[   A J A X   A C T I V I T Y   I N D I C A T O R   ]]--- //
// ------------------------------------------------------------- //
	
	Twb.loading = function(show) {
		$target = $('#twb-ajax-activity');
		if (!$target.length) {
			$target = $('<div>')
				.attr('id', 'twb-ajax-activity')
				.html('loading...')
				.hide();
			;
			$('body').append($target);
		}
		
		if (show == 'show') show = true;
		if (show == 'hide') show = false;
		
		if (show) {
			$target.show().addClass('visible');
		} else {
			$target.removeClass('visible');
		}
	}
	
	
	
	
// --------------------------------- //
// ---[[   S T I C K Y   U I   ]]--- //
// --------------------------------- //
	
	Twb.stickyUI = function() {
		
		// prevent behavior by body data- attribute
		if ($('body').attr('data-stickyUi') != 'true') return;
		
		// disable stiky to IE!
		if (Twb.is.ie) return;
		
		// assign class to isolate CSS rules only if behavior is applied
		$('html').addClass('twb-stickyUi');
		
		var $top = $('#twb-sticky-pageheader');
		var $bottom = $('form[data-twb-sticky=on] .form-actions');
		
		// initialize pageheader
		if ($top.length) {
			var $ghost = $('<div>').html("&nbsp;").addClass('twb-sticky-ghost').css({
				height:		$top.outerHeight(true)
			});
			$top.addClass('twb-sticky').after($ghost);
			// move standard flash messages after ghost item!
			if ($('#twb-flash-messages .alert').length) $ghost.after($('#twb-flash-messages .alert'));
			// add internal container to fit centered layout into sticky pageader
			if (!$top.parents('.container-fluid').length) {
				var $wrap = $('<div>').addClass('container').html($top.html());
				$top.html('').append($wrap);
			}
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
		show: function(cfg) {
			cfg = cfg || {};
			cfg = $.extend({},{
				title: 	'',
				text: 	'',
				type: 	'info'
			},cfg);
			if (!cfg.text.length) {
				return;
			}
			// use standard "alert" on phones
			if (Twb.is.phone) {
				if (cfg.title.length) {
					cfg.text = cfg.title.toUpperCase() + "\n" + cfg.text;
				}
				alert(cfg.text);
			// use jQuery's plugin for big devices
			} else {
				$.pnotify(cfg);
			}
		},
		success: function(text, title) {
			Twb.msg.show({
				title: 	title,
				text: 	text,
				type: 	'success'
			});
		},
		error: function(text, title) {
			Twb.msg.show({
				title: 	title,
				text: 	text,
				type: 	'error'
			});
		},
		warning: function(text, title) {
			Twb.msg.show({
				title: 	title,
				text: 	text,
				type:	'alert'
			});
		},
		info: function(text, title) {
			Twb.msg.show({
				title: 	title,
				text: 	text,
				type: 	'info',
				hide: false
			});
		},
		
		
		/**
		 * fetch all "alert" messages and translate them to high level js messages
		 */
		init: function() {
			
			// prevent behavior by body 
			// - data- attribute
			// - mobile devices
			// - small screen resolution
			if ($('body').attr('data-smartMsg') != 'true') return;
			if (Twb.is.phone) return;
			if ($(window).width() < 768) return;
			
			// pnotify defaults
			$.pnotify.defaults.history = false;
			$.pnotify.defaults.sticker = false;
			$.pnotify.defaults.icon = false;
			$.pnotify.defaults.animation = 'slide';
			$.pnotify.defaults.addclass = 'pnotify-stack-bottom-right';
			$.pnotify.defaults.cornerclass = 'ui-pnotify-sharp';
			$.pnotify.defaults.stack = {"dir1": "up", "dir2": "left", "push": "bottom"};
			$.pnotify.defaults.animate_speed = 'fast';
			$.pnotify.defaults.opacity = 0.85;
			// convert session messages into notifications
			// @TODO: setup a delay between each notification?
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
	
	Twb.setFormErrors = function(errors, form) {
		var $form = $(form);
		$.each(errors.fields, function(fid, msg) {
			$field = $('#'+fid);
			$group = $field.parents('.control-group');
			if (!$group.hasClass('error')) {
				$group.addClass('error');
				$field.addClass('form-error');
				if ($form.hasClass('form-horizontal')) {
					$field.after($('<div>').addClass('help-inline').html(msg));
				} else {
					$field.parent().after($('<div>').addClass('help-inline').html(msg));
				}
			}
		});
		// apply field's behaviors
		Twb.formErrorsAction(form);
		if ($form.hasClass('form-horizontal')) Twb.formErrorsTooltip(form);
	}
	
	/**
	 * Removes form error styles when focus on fields
	 */
	Twb.formErrorsAction = function(form) {
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
		if (Twb.is.ie) return;
		$(form).find('.control-group.error').each(function() {
			var $this = $(this);
			var $tooltipHandler = $('<span>');
			var $msg = $this.find('.help-inline').hide().after($tooltipHandler);
			
			$this.find('.tooltip, [data-toggle="tooltip"]').remove();
			
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
	
	/**
	 * Submit forms via AJAX
	 */
	Twb.ajaxForm = function(form) {
		
		// prevent behavior by body data- attribute
		if ($('body').attr('data-ajaxForm') != 'true') return;
		
		$(form).each(function() {
			var $form = $(this);
			
			// disable ajax form if an attachment is sent!
			if ($form.find('input[type=file]').length) {
				//return;
			}
			
			$form.ajaxForm({
				dataType: 'json',
				success: function(data) {
					
					data = $.extend({},{
						ajax: {
							msg: null,
							title: null
						}
					},data);
					
					// display validation errors
					if (data.formErrors) Twb.setFormErrors(data.formErrors, $form);
					
					// apply notification based on given "type"
					// or fallback to generic waring message.
					if (typeof Twb.msg[data.ajax.type] == 'function') {
						Twb.msg[data.ajax.type].call(this, data.ajax.text, data.ajax.title);
					} else {
						Twb.msg.warning(data.ajax.text, data.ajax.title);
					}
					
					// apply redirect - if required by ajax response object
					// (skip notification on phones!)
					if (data.ajax.redirect) {
						setTimeout(function() {
							if (!Twb.is.phone) Twb.msg.info('please wait while redirecting');
							location.href = data.ajax.redirect;
						}, 700);
					}
				},
				// ajax error - disable ajax form
				error: function() {
					Twb.msg.error("AJAX request could not be solved!<br>Sending form the standard way now...", "AJAX Error:");
					setTimeout(function() {
						//$form.unbind('submit').submit();	
					}, 500);
				}
			});
			
			/*
			$form.bind('submit', function(e) {
				e.preventDefault();
				e.stopPropagation();
				
				$form.ajaxSubmit();
			});
			*/
			
			
			/*
			$form.delegate('[data-twb-upload="on"] input', 'change', function() {
				$form.unbind('submit');
			});
			*/
		});
	};
	
	
	
	
	
	
	
	
	
	
// ---[[ MEDIA TABLE PLUGIN ]]--- //
	Twb.mediaTable = function() {
		
		// prevent behavior by body data- attribute
		if ($('body').attr('data-mediaTable') != 'true') return;
		
		var cfg = {
			menuTitle: '<i class="icon-chevron-down pull-right" style="margin-top:4px"></i> Colonne Visibili:'
		};
		$('table[data-responsive=on]').mediaTable(cfg);
		if (Twb.is.mobile) {
			$('table[data-responsive=mobile]').mediaTable(cfg);	
		}
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
