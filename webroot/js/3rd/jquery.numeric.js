/**
 * jQuery Numeric Field Plugin
 * ============================
 *
 * UI kit plugin to be applied to a text input.
 * transform generic text input into a numeric specific
 *
 * - apply a keyboard filter to digits only
 * - "esc" to clear value
 * - fixed decimals paradigm. you don't need to type "." or "," to set decimals
 *
 * // initialize:
 * $('input#foo').numeric();
 * $('input#foo').numeric({decimals: 3});
 *
 * // public apis
 * $('input#foo').numeric('add', 9);
 * $('input#foo').numeric('remove');
 * $('input#foo').numeric('reset');
 *
 * // chaining apis
 * $('input#foo').numeric('reset').numeric('add', 2).numeric('add', 4);
 *
 *
 * Automagically Setup
 * ====================
 *
 * UI Numeric plugin auto initialize itself on controls who implement
 * "data-numeric=true" or "data-numeric=on" params
 * 
 * you should set up decimals number with:
 * data-numeric-decimals
 *
 * <input type="text" data-numeric="true" data-decimals="3">
 *
 */

;(function($){
	
	
	/**
	 * Global info and configurations
	 */
	$.numeric = {
		version: '1.0',
		author: {
			nick: 'MPeg',
			mail: 'marco(dot)pegoraro(at)gmail(dot)com'
		},
		dataName: 'numericFieldPlugin',
		defaults: {
			decimals: 	2,			// number of decimals to show
			pad:		0,			// apply left padding to the int part
			sep:		'.',		// separator from int part to decimals
			abs:		false,		// if true negative values are not allowed
			pattern: 	'[0-9]*',	// default pattern to apply to text control - html5 only
			align: 		true,		// by default add a right alignment to the control
			format: 	null,		// custom float2string formatter to display value
			display:	true		// create a display field for rendered value
		},
		auto: function() {
			$('*[data-numeric=on],*[data-numeric=true]').each(function() {
				var cfg = {};
				if ($(this).attr('data-numeric-decimals') !== undefined) {
					cfg.decimals = parseInt($(this).attr('data-numeric-decimals'));
				}
				if ($(this).attr('data-numeric-align') !== undefined) {
					cfg.align = $(this).attr('data-numeric-align');
				}
				if ($(this).attr('data-numeric-sep') !== undefined) {
					cfg.sep = $(this).attr('data-numeric-sep');
				}
				if ($(this).attr('data-numeric-pad') !== undefined) {
					cfg.pad = $(this).attr('data-numeric-pad');
				}
				if ($(this).attr('data-numeric-abs') !== undefined) {
					cfg.abs = $(this).attr('data-numeric-abs');
				}
				$(this).numeric(cfg);
			});
		}
	};
	
	
	
	
	/**
	 * Plugin's APIs
	 * list all public apis
	 */
	var apis = {
		init: 		function(cfg) {},
		add: 		function(digit) {},
		abs:		function() {},
		negative: 	function() {},
		remove: 	function() {},
		update: 	function() {},
		reset: 		function() {},
		val:		function() {}
	};
	
	
	
	/**
	 * The Plugin
	 * is able to handle named apis calls
	 */
	$.fn.numeric = function(method) {
		if (apis[method]) {
			return apis[method].apply(this[0], Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || ! method) {
			apis.init.apply(this, arguments);	
		} else {
			$.error( 'Method "' +  method + '" does not exist on "jQuery.decimal" plugin' );
		}
		return this;
	}
	
	
	
	/**
	 * Initialize configuration and internal data object to each UI
	 */
	apis.init = function(cfg) {
		
		// apply defaults to given configuration
		cfg = cfg || {};
		cfg = $.extend({}, $.numeric.defaults, cfg);
		
		// step to each given control
		$(this).each(function() {
			
			// prevent multiple initializations
			if ($(this).data($.numeric.dataName)) return;
		
			// create instance context
			var instance = {
				cfg: 		cfg,
				id:			null,
				name:		null,
				$: 			$(this),
				$display: 	null,
				digits: 	0,
				value: 		0,
				negative: 	false
			};
			
			// setup full ids and name attribute
			instance.id = instance.$.attr('id') || (new Date()).getTime();
			instance.name = instance.$.attr('name') || instance.$.attr('id');
			
			// tie instance context to the DOM
			instance.$.data('numericFieldPlugin', instance);		
			
			// initial display setup
			instance.$.attr('pattern', cfg.pattern);
			if (cfg.align === true) {
				instance.$.css('text-align', 'right');
			}
			
			// create display field to show rendered value but preserve real
			// float value into orignal form field who becomes an hidden
			if (instance.cfg.display === true || instance.cfg.display == "on") {
				instance.$display = instance.$.clone();
				instance.$display.attr('name', instance.name + "__numeric");
				instance.$display.attr('id', instance.id + "__numeric");
				// tie instance to the display DOM too!
				instance.$display.data('numericFieldPlugin', instance);
				// insert the display form
				instance.$.before(instance.$display);
				instance.$.hide();
				// bind events to the ghost field
				instance.$display.bind('keydown', _keydown);
				instance.$display.bind('keyup', _keyup);
			} else {
				// bind events to the original field
				instance.$.bind('keydown', _keydown);
				instance.$.bind('keyup', _keyup);
			}
			
			// initial value setup
			if (val = instance.$.attr('value')) {
				val = val.replace(instance.cfg.sep, '.');
				val = val.replace(',', '.');
				if (val == "NaN" || val == NaN) {
					val = 0;
				}
				if (val < 0) {
					val = Math.abs(val);
					instance.negative = true;
				}
				if (instance.cfg.decimals > 0) {
					instance.digits = (Math.round(val * __floater(instance.cfg.decimals))).toString();
				} else {
					instance.digits = parseInt(val).toString();
				}
			}
			apis.update.call(this);
			
		});
		return this;
	}
	
	/**
	 * public, append a digit
	 */
	apis.add = function(digit) {
		var instance = $(this).data($.numeric.dataName);
		
		if (instance.digits == "0" ) {
			instance.digits = "";
		}
		
		instance.digits += '' + digit;
		
		apis.update.call(this);
		return instance.$;
	}
	
	// public, convert a positive value into a negative
	apis.negative = function() {
		var instance = $(this).data($.numeric.dataName);
		instance.negative = true;
		apis.update.call(this);
		return instance.$;
	}
	
	/**
	 * public, convert a negative value into an absolute value
	 */
	apis.abs = function() {
		var instance = $(this).data($.numeric.dataName);
		instance.negative = false;
		apis.update.call(this);
		return instance.$;
	}
	
	/** 
	 * public, remove last digit
	 */
	apis.remove = function() {
		var instance = $(this).data($.numeric.dataName);
		
		if (instance.digits.length > 0) {
			instance.digits = instance.digits.substr(0,instance.digits.length-1);
		}
		if ( instance.digits.length <= 0 ) {
			instance.digits = "0";
		}
		apis.update.call(this);
		return instance.$;
	}
	
	/**
	 * public, reset digits to 0
	 */
	apis.reset = function() {
		var instance = $(this).data($.numeric.dataName);
		instance.digits = "0";
		apis.update.call(this);
		return instance.$;
	}
	
	
	/**
	 * public - returns control's numeric value.
	 */
	apis.val = function() {
		var instance = $(this).data($.numeric.dataName);
		return instance.value;
	}
	
	/**
	 * public - updates control's value
	 */
	apis.update = function() {
		var instance = $(this).data($.numeric.dataName);
		var floatv = __floatv(instance);
		
		// updates instance's value
		// config "abs" should prevent to negative values!
		if (instance.negative && !instance.cfg.abs) {
			instance.value = (0 - floatv);
		} else {
			instance.value = floatv;
		}
		
		// apply custom value format
		if (typeof instance.cfg.format == "function") {
			instance.$.val(instance.cfg.format.call(this, floatv));	
		
		// apply default decimal format
		} else {
			var sval = floatv.toFixed(instance.cfg.decimals).toString();
			sval = sval.replace('.', instance.cfg.sep);
			
			// apply padding to the int part of the number
			if (instance.cfg.decimals > 0 && instance.cfg.pad > 0) {
				var parts = sval.split(instance.cfg.sep);
				var ip = parseInt(parts[0]);
				var fp = parts[1];
				sval = __padLeft(ip, instance.cfg.pad) + instance.cfg.sep + fp;
			// apply padding to an integer only value - no decimals
			} else if (instance.cfg.pad > 0) {
				sval = __padLeft(parseInt(sval), instance.cfg.pad);
			}
			
			// apply negative symbol
			if (instance.value < 0) {
				sval = '-' + sval;
			}
			
			// update display
			if (instance.$display) {
				instance.$display.val(sval);
				instance.$.val(instance.value);
			} else {
				instance.$.val(sval);
			}
			
		}
		
	}
	
	
	
	
	
	/**
	 * private - bind keypad actions to isolate digits only
	 * and call public api
	 *
	 * "enter" and "tab" keys are ignored!
	 */
	var _keydown = function(e) {
		if (e.keyCode != 13 && e.keyCode != 9) {
			e.preventDefault();
			e.stopPropagation();
		}
	};
	var _keyup = function(e) {
		if (e.keyCode != 13 && e.keyCode != 9) {
			_keydown(e);
			
			// numbers
			if (e.keyCode >= 48 && e.keyCode <= 57) {
				apis.add.call(this, e.keyCode - 48);
				
			// backspace
			} else if (e.keyCode == 8) {
				apis.remove.call(this);
				
			// esc
			} else if (e.keyCode == 27) {
				apis.reset.call(this);	
				
			// "-"
			} else if(e.keyCode == 173 || e.keyCode == 189) {
				apis.negative.call(this);
				
			// "+"
			} else if(e.keyCode == 171 || e.keyCode == 187) {
				apis.abs.call(this);
				
			}
			
			//else {console.log(e.keyCode);}
			return false;
		}
	}
	
	/**
	 * converts decimal amounts into a multipier of 10:
	 * 2 -> 100
	 * 3 -> 1000
	 */
	var __floater = function(decimals) {
		var floater = 10;
		for (var i=1; i<decimals; i++) {
			floater = floater * 10;
		}
		return floater;
	}
	
	/**
	 * converts digits into it's float value considering 
	 * configured amount of decimals
	 */
	var __floatv = function(instance) {
		var intv = parseInt(instance.digits);
		if (instance.cfg.decimals > 0) {
			return  intv / __floater(instance.cfg.decimals);
		} else {
			return intv;
		}
	}
	
	/**
	 * private, pads a number
	 */
	function __padLeft(nr, n, str){
		if (nr.toString().length > n) return nr;
	    return Array(n-String(nr).length+1).join(str||'0')+nr;
	}
	
	
	
	
	
	
	/**
	 * Automagically Plugin Initialization
	 */
	$(document).ready(function() {	
		$.numeric.auto();
	});
	
	
	
	
})(jQuery);