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
				'fixed' => ($base_class != 'container-fluid')
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
		
		// intercept the "fixed" param given to the container
		// by default a row is fluid
		$base_class = 'row-fluid';
		if ($options === 'fixed') {
			$base_class = 'row';
			$options = array();
		} elseif (isset($options['fixed']) && $options['fixed']) {
			$base_class = 'row';
			unset($options['fixed']);
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
	 * compose a page header block with title, subtitle and action buttons
	 */
	public function pageHeader($title = '', $actions = array(), $options = array()) {
		$options = BB::extend(array(
			'title'		=> $title,
			'subtitle'	=> '',
			'actions'	=> $actions,
			'groupActions' => true,
			'actionOptions' => array(),
			'sticky' => true
		), BB::setDefaultAttrs($options));
		
		if ($options['sticky'] === true) {
			$options['id'] = 'twb-sticky-pageheader';
		} unset($options['sticky']);
		
		// split title and subtitle by simple string pattern
		if (strpos($options['title'], '>>') !== false) {
			list($options['title'], $options['subtitle']) = explode('>>', $options['title']);
		}
		
		// setup base class
		$options['class'] = trim('page-header twb-page-header ' . $options['class']);
		
		// setup title
		$title = $this->Html->tag(array(
			'tag' => 'h1',
			'content' => array(
				$options['title'],
				' ',
				array(
					'tag' => 'small',
					'content' => $options['subtitle']
				)
			)
		));
		
		// render a list of actions
		if (is_array($actions)) {
			ob_start();
			foreach ($actions as $actionName=>$actionConfig) {
				if (is_numeric($actionName)) {
					$actionName = $actionConfig;
				}
				if (!is_array($actionConfig)) {
					$actionConfig = array(
						'show' => $actionName,
						'title' => $actionName,
						'href' => array('action' => $actionConfig)
					);
				}
				$actionConfig = BB::extend(array(
					'xtag' => 'linkbtn',
					'show' => $actionName,
					'href' => array('action' => $actionName)
				), $actionConfig);
				echo $this->Html->tag($actionConfig);
			}
			$actions = ob_get_clean();
		}
		
		// apply button group wrapper
		if ($options['groupActions']) {
			$actions = $this->Html->tag(array(
				'class' => 'btn-group',
				'content' => $actions
			));
		}
		
		// title structure
		return $this->Html->tag(BB::extend(array(
			'content' => array(
				BB::extend(array(
					'class' => 'pull-right',
					'content' => $actions
				), BB::setDefaultAttrs($options['actionOptions'])),
				$title,
			)
		), BB::clear($options, array(
			'title',
			'subtitle',
			'actions',
			'groupActions',
			'actionOptions'
		))));
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

