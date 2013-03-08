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
	
}