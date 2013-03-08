<?php
/**
 * Typographic Utilities Helper
 * Twitter Bootstrap - UI Plugin
 */
class TwbTypoHelper extends AppHelper {
	
	public $helpers = array(
		'Html',
		'Twb.Twb'
	);
	
	

	public function title($content, $options = array()) {
		
		// true/flase to render or return full array configuration
		if (is_bool($options)) $options['render'] = $options;
		
		$options = BB::setDefaultAttrsId($options, array(
			'size' => 1,
			'outerOptions' => array(),
			'innerOptions' => array(),
			'before' => array(),
			'after' => array(),
			'subtitle' => '',
			'visible' => '',
			'hidden' => ''
		));
		
		// Implement subtitle
		if (!is_array($content) && !empty($options['subtitle'])) {
			$content = array(
				$content,
				' ',
				array('tag' => 'small', 'show' => $options['subtitle'])
			);
		}
		
		$title = BB::extend(array(
			'tag' => 'h' . $options['size'],
			'content' => $content
		), BB::setDefaultAttrs($options['innerOptions']));
		
		$wrap = BB::extend(array(
			'class' => 'page-header',
			'content' => array($options['before'], $title, $options['after']),
			'visible' => $options['visible'],
			'hidden' => $options['hidden']
		), BB::setDefaultAttrsId($options['outerOptions']));
		
		$options = BB::clear($options, array(
			'class',
			'size',
			'outerOptions',
			'innerOptions',
			'before',
			'after',
			'subtitle',
			'visible',
			'hidden'
		));
		
		$wrap = $this->Twb->visibleOptions($wrap);
		$options = BB::extend($wrap, $options);
		return $this->Twb->renderReturn($options);
		
	}
	
	
	
	
	/**
	 * Generates an icon I tag
	 */
	public function icon($name = '', $white = false) {
		if (empty($name)) return;
		$icon = 'icon-' . $name;
		if ($white) {
			$icon.= ' icon-white';
		}
		return '<i class="' . $icon . '"></i>';
	}
	
	
	
}

