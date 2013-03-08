<?php
/**
 * Layout Helper
 * Twitter Bootstrap - UI Plugin
 */
class TwbLayoutHelper extends AppHelper {
	
	public $helpers = array(
		'Html',
		'Twb.Twb'
	);
	





// ------------------------------------------- //
// ---[[   G R I D   U T I L I T I E S   ]]--- //
// ------------------------------------------- //

	public function container($content, $options = array()) {
		
		// true/flase to render or return full array configuration
		if (is_bool($options)) $options['render'] = $options;
		
		// intercept the "fluid" param given to the container
		$base_class = 'container';
		if ($options === 'fluid') {
			$base_class = 'container-fluid';
			$options = array();
		} elseif (isset($options['fluid']) && $options['fluid']) {
			$base_class = 'container-fluid';
			unset($options['fluid']);
		}
		
		$options = BB::setDefaultAttrsId(BB::clear($options), array(
			'class' => $base_class,
			'content' => $content,
			'render' => true
		));
		
		
		$options = BB::extend(array(
			'defaults' => array(
				'xtag' => 'row',
				'fluid' => ($base_class === 'container-fluid')
			),
		), $options);
		
		#debug($options);
		
		$options = $this->Twb->fluidOptions($options);
		$options = $this->Twb->visibleOptions($options);
		return $this->Twb->renderReturn($options);
	}
	
	public function row($content, $options = array()) {
		
		// true/flase to render or return full array configuration
		if (is_bool($options)) $options['render'] = $options;
		
		// intercept the "fluid" param given to the container
		$base_class = 'row';
		if ($options === 'fluid') {
			$base_class = 'row-fluid';
			$options = array();
		} elseif (isset($options['fluid']) && $options['fluid']) {
			$base_class = 'row-fluid';
			unset($options['fluid']);
		}
		
		$options = BB::setDefaultAttrsId($options, array(
			'class' => $base_class,
			'content' => $content,
			'defaults' => array('xtag' => 'col')
		));
		
		$options = $this->Twb->fluidOptions($options);
		$options = $this->Twb->visibleOptions($options);
		return $this->Twb->renderReturn($options);
	}
	
	public function col($content, $options = array()) {
		
		// true/flase to render or return full array configuration
		if (is_bool($options)) $options['render'] = $options;
		
		// numeric options set colum's size
		if (is_numeric($options)) {
			$options = array('size' => $options);
		}
		
		$options = BB::setDefaultAttrsId($options, array(
			'class' => '',
			'span' => 12,
			'offset' => 0,
			'content' => $content
		));
		
		// extract custom class
		if (!empty($options['class'])) {
			$custom_class = $options['class'];
		}
		
		// remark default span class
		if (empty($options['span'])) $options['span'] = 12;
		$options['class'] = ' span' . $options['span'];
		
		// offset column
		if (!empty($options['offset'])) {
			$options['class'] .= ' offset' . $options['offset'];	
		}
		
		// apply custom class
		if (!empty($custom_class)) {
			$options['class'] .= ' ' . $custom_class;
		}
		
		$options = $this->Twb->visibleOptions($options);
		$options = BB::clear($options, array('span', 'offset'));
		return $this->Twb->renderReturn($options);
	}
	
	
	
	
	
	
	
	
	
	

	
// ------------------------------------ //
// ---[[   C O M P O N E N T S   ]] --- //
// ------------------------------------ //

	public function navbar($options = array()) {
		
		$options = BB::extend(array(
			'fixed' => false,
			'container' => false,
			'inverse' => false,
			'responsive' => false,
			'outerOptions' => array(),
			'innerOptions' => array(),
			// contend desctiption options
			'logo' => array(),
			'menu' => array(),
			'content' => array()
		), $options);
		#ddebug($options);
		
		return $this->_View->element('Twb.navbar', $options);
	}


	
	
	/**
	 * Generates a thumbnail block code
	 */
	public function thumb($options = array()) {
		
		$defaults = array(
			'src' => '',
			'href' => '',
			'caption' => '',
			'actions' => '',
			'imageOptions' => array(),
			'linkOptions' => array()
		);
		
		$options = BB::extend($defaults, BB::setDefaultAttrsId($options));
		list($src, $href, $caption, $actions, $imageOptions, $linkOptions) = array($options['src'], $options['href'], $options['caption'], $options['actions'], $options['imageOptions'], $options['linkOptions']);
		$options = BB::clear($options, array_keys($defaults));
		
		// fill link options
		$linkOptions = BB::setDefaultAttrs($linkOptions);
		if (!empty($options['title'])) {
			$linkOptions['title'] = $options['title'];
			unset($options['title']);
		}
		
		// fill image options
		if (!empty($options['alt'])) {
			$imageOptions['alt'] = $options['alt'];
			unset($options['alt']);
		}
		
		// compose image block
		$image = null;
		if (!empty($src)) $image = $this->Html->image($src, $imageOptions);
		
		// compose image link
		if (!empty($href)) $image = $this->Html->tag(BB::extend(array(
			'xtag' => 'link',
			'href' => $href,
			'show' => $image
		), $linkOptions));
		
		$actions = array(
			'tag' => 'div',
			'class' => 'caption-actions',
			'defaults' => array('xtag' => 'linkbtn'),
			'content' => $actions
		);
		
		// compose caption block
		$caption = array(
			'class' => 'caption',
			'content' => array(
				array(
					'class' => 'caption-content',
					'defaults' => array('tag' => 'p'),
					'content' => $caption
				), 
				$actions
			)
		);
		
		#ddebug($caption);
		
		// render
		return $this->Html->tag(BB::extend($options, array(
			'$++class' => ' thumbnail',
			'content' => array(
				$image,
				$caption
			)
		)));
		
	}
	
	
	
	
	

	
	
	
}

