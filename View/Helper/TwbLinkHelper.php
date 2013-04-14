<?php
/**
 * Links Utility Helper
 * Twitter Bootstrap - UI Plugin
 */
class TwbLinkHelper extends AppHelper {
	
	public $helpers = array(
		'Html',
		'Twb.TwbTypo'
	);
	
	
	/**
	 * Generate a link with icon capabilities
	 */
	public function linkIco($show = '', $url = array(), $options = array()) {
		
		$options = BB::extend(array(
			'icon' => '',
			'icon-right' => false,
			'icon-white' => false,
			'icon-only' => false
		), BB::set($options, 'icon'));
		
		// icons-only on mobile devices to save space!
		if ($options['icon-only'] === 'mobile') {
			$options['icon-only'] = $this->_View->request->is('mobile');
		}
		
		if (!empty($options['icon'])) {
			$icon = $this->TwbTypo->icon($options['icon'], $options['icon-white']);
			if ($options['icon-only']) {
				$show = $icon;
			} elseif ($options['icon-right']) {
				$show .= ' ' . $icon;
			} else {
				$show = $icon . ' ' . $show;
			}
			$options['escape'] = false;
		}
		
		$options = BB::clear($options, array('icon', 'icon-right', 'icon-size', 'icon-white', 'icon-only'));
		return $this->Html->link($show, $url, $options);
		
	}
	
	
	
	/**
	 * Generates a link with button UI style.
	 * it uses HtmlHelper::link() method with customized options
	 */
	public function linkBtn($show = '', $url = array(), $options = array()) {
		
		// 3rd param as string means styled button or icon name
		if (is_string($options)) {
			if (in_array($options, array('success', 'error', 'warning', 'info', 'link', 'danger', 'inverse'))) {
				$options = BB::set($options, 'type');
			} else {
				$options = BB::set($options, 'icon');
			}
		}
		
		$options = BB::extend(array(
			'type' => '',	// [primary|success|info|warning|danger|inverse|link]
			'size' => '',	// [large|small|mini]
			'block' => false,
			'icon' => '',
			'icon-right' => false,
			'icon-white' => false,
			'icon-only' => false,
			'escape' => false
		), $options, array(
			'$++class' => ' btn'
		));
		
		if (!empty($options['type'])) {
			$options['class'] .= ' btn-' . $options['type'];
		}
		
		if (!empty($options['size'])) {
			$options['class'] .= ' btn-' . $options['size'];
		}
		
		if ($options['block']) {
			$options['class'] .= ' btn-block';
		}
		
		// icons-only on mobile devices to save space!
		if ($options['icon-only'] === 'mobile') {
			$options['icon-only'] = $this->_View->request->is('mobile');
		}
		
		if (!empty($options['icon'])) {
			$icon = $this->TwbTypo->icon($options['icon'], $options['icon-white']);
			if ($options['icon-only']) {
				$show = $icon;
			} elseif ($options['icon-right']) {
				$show .= ' ' . $icon;
			} else {
				$show = $icon . ' ' . $show;
			}
			$options['escape'] = false;
		}
		
		$options = BB::clear($options, array('type', 'size', 'block', 'icon', 'icon-right', 'icon-size', 'icon-white', 'icon-only'));
		return $this->Html->link($show, $url, $options);
	}
	
	/**
	 * Generates a buttons group control
	 */
	public function btnGroup($buttons = array(), $options = array()) {
		
		$options = BB::setDefaultAttrsId($options);
		$options['class'] = 'btn-group ' . $options['class'];
		
		return $this->Html->tag(BB::extend(array(
			'defaults' => array('xtag' => 'linkbtn'),
			'content' => $buttons
		), $options));
	}
	
}

