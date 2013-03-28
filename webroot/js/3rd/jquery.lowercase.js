/**
 * jQuery Lowercase Field Plugin
 * ============================
 *
 * UI kit plugin to be applied to a text input.
 * transform every uppercase A-Z into lowercase while typing
 *
 * - apply a keyboard filter to digits only
 * - "esc" to clear value
 * - fixed decimals paradigm. you don't need to type "." or "," to set decimals
 *
 * // initialize:
 * $('input#foo').lowercase();
 *
 *
 * Automagically Setup
 * ====================
 *
 * <input type="text" data-lowercase="true">
 *
 */

;(function($){
	
	
	/**
	 * Global info and configurations
	 */
	$.lowercase = {
		version: '1.0',
		author: {
			nick: 'MPeg',
			mail: 'marco(dot)pegoraro(at)gmail(dot)com'
		},
		dataName: 'lowercaseFieldPlugin',
		defaults: {},
		auto: function() {
			$('*[data-lowercase=on],*[data-lowercase=true]').each(function() {
				var cfg = {};
				$(this).lowercase(cfg);
			});
		}
	};
	
	
	
	
	/**
	 * Plugin's APIs
	 * list all public apis
	 */
	var apis = {
		init: 		function(cfg) {}
	};
	
	
	
	/**
	 * The Plugin
	 * is able to handle named apis calls
	 */
	$.fn.lowercase = function(method) {
		if (apis[method]) {
			return apis[method].apply(this[0], Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || ! method) {
			apis.init.apply(this, arguments);	
		} else {
			$.error( 'Method "' +  method + '" does not exist on "jQuery.lowercase" plugin' );
		}
		return this;
	}
	
	
	
	/**
	 * Initialize configuration and internal data object to each UI
	 */
	apis.init = function(cfg) {
		
		// apply defaults to given configuration
		cfg = cfg || {};
		cfg = $.extend({}, $.lowercase.defaults, cfg);
		
		// step to each given control
		$(this).each(function() {
			$(this).delegate(null, 'change', _valueToLower);
			$(this).delegate(null, 'blur', _valueToLower);
			$(this).delegate(null, 'keypress', _keyToLower);
		});
		return this;
	}
	
	
	var _keyToLower = function(e) {
		if (e.charCode >= 65 && e.charCode <= 90) {
			e.preventDefault();
			// append to or detect caret position to 
			$(this).val($(this).val() + String.fromCharCode(e.charCode+32));
		}
	}
	
	var _valueToLower = function(e) {
		$(this).val($(this).val().toLowerCase());
	}
	
	
	/**
	 * Automagically Plugin Initialization
	 */
	$(document).ready(function() {
		$.lowercase.auto();
	});
	
	
	
	
})(jQuery);