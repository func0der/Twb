<?php
/**
 * Twitter Bootstrap - UI Plugin
 */
class TwbCoreComponent extends Component {
	
	public function initialize(Controller $Controller) {
		
		// Optional Markdown Helper
		if (BB::check('plugin.Markdown')) {
			$Controller->helpers[] = 'Markdown.Markdown';
		}
		
		// need a Less compiler to compile Twb's LESS sources!
		$Controller->helpers[] = 'BB.BbLess';
		
		// Extend app helpers with plugin's base helpers
		$Controller->helpers[] = 'Twb.Twb';
		$Controller->helpers[] = 'Twb.TwbLayout';
		$Controller->helpers[] = 'Twb.TwbTypo';
		$Controller->helpers[] = 'Twb.TwbLink';
		$Controller->helpers[] = 'Twb.TwbDropdown';
		
		// Setup default layout
		if ($Controller->layout === 'default') {
			$Controller->layout = 'Twb.default';
		}
		
		
		
	}
	
	/**
	 * Alias FormHelper only if a Twb layout is used
	 */
	public function beforeRender(Controller $Controller) {
		if (substr($Controller->layout, 0, 4) === 'Twb.') {
			$Controller->helpers = BB::extend($Controller->helpers, array(
				'Form' => array('className' => 'Twb.TwbForm')
			));
		}
	}
	
}