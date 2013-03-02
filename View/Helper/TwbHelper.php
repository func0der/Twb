<?php
/**
 * HtmlHelper Extension
 * Twitter Bootstrap - UI Plugin
 */
App::import('View/Helper', 'BB.BbHtmlHelper');

class TwbHelper extends BbHtmlHelper {
	
	/**
	 * Construct
	 * register xtags
	 */
	public function __construct(View $View, $settings = array()) {
		parent::__construct($View, $settings);
		$this->_registerXtags();
	}
	
	
	


// ------------------------------------------- //
// ---[[   G R I D   U T I L I T I E S   ]]--- //
// ------------------------------------------- //

	public function container($content, $options = array()) {
		
		// true/flase to render or return full array configuration
		if (is_bool($options)) $options['render'] = $options;
		
		$options = BB::setDefaultAttrsId($options, array(
			'class' => 'container',
			'content' => $content,
			'render' => true
		));
		
		$options = BB::extend(array(
			'defaults' => array('xtag' => 'row'),
		), $options);
		
		#ddebug($options);
		
		$options = $this->_fluidOptions($options);
		$options = $this->_visibleOptions($options);
		return $this->_renderReturn($options);
	}
	
	public function row($content, $options = array()) {
		
		// true/flase to render or return full array configuration
		if (is_bool($options)) $options['render'] = $options;
		
		$options = BB::setDefaultAttrsId($options, array(
			'class' => 'row',
			'content' => $content,
			'defaults' => array('xtag' => 'col')
		));
		
		$options = $this->_fluidOptions($options);
		$options = $this->_visibleOptions($options);
		return $this->_renderReturn($options);
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
		
		$options = $this->_visibleOptions($options);
		$options = BB::clear($options, array('span', 'offset'));
		return $this->_renderReturn($options);
	}
	
	protected function _renderReturn($options) {
		
		$options = BB::setExtend(array('render' => true), $options);
		
		$render = $options['render'];
		unset($options['render']);
		if ($render) {
			return $this->tag($options);
		} else {
			return $options;
		}
	}
	
	protected function _fluidOptions($options) {
		$options = BB::extend(array(
			'fluid' => false
		), $options);
		
		if ($options['fluid']) {
			$options['class'] = str_replace('container', 'container-fluid', $options['class']);
		}
		
		return BB::clear($options, array('fluid'));
	}
	
	protected function _visibleOptions($options) {
		$options = BB::extend(array(
			'class' => '',
			'visible' => '',
			'hidden' => ''
		), $options);
			
		if (!empty($options['visible'])) {
			$options['visible'] = str_replace(array(',', ';'), ' ', $options['visible']);
			foreach(explode(' ', $options['visible']) as $tmp) {
				$options['class'] .= ' visible-' . trim($tmp);
			}
		}
		
		if (!empty($options['hidden'])) {
			$options['hidden'] = str_replace(array(',', ';'), ' ', $options['hidden']);
			foreach(explode(' ', $options['hidden']) as $tmp) {
				$options['class'] .= ' hidden-' . trim($tmp);
			}
		}
		
		return BB::clear($options, array('visible', 'options'));	
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
		
		return $this->_View->element('Twb.navbar', $options);
	}



// ------------------------------------------------------- //
// ---[[   T Y P O G R A P H Y   U T I L I T I E S   ]]--- //
// ------------------------------------------------------- //

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
		
		$wrap = $this->_visibleOptions($wrap);
		$options = BB::extend($wrap, $options);
		return $this->_renderReturn($options);
		
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
			'type' => '',
			'size' => '',
			'block' => false,
			'icon' => '',
			'icon-right' => false,
			'icon-white' => false
		), $options, array(
			'$++class' => 'btn'
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
		
		if (!empty($options['icon'])) {
			$icon = $this->icon($options['icon'], $options['icon-white']);
			if ($options['icon-right']) {
				$show .= ' ' . $icon;
			} else {
				$show = $icon . ' ' . $show;
			}
			$options['escape'] = false;
		}
		
		$options = BB::clear($options, array('type', 'size', 'block', 'icon', 'icon-right', 'icon-size', 'icon-white'));
		return $this->link($show, $url, $options);
	}
	
	/**
	 * Generates a buttons group control
	 */
	public function btnGroup($buttons = array(), $options = array()) {
		
		$options = BB::setDefaultAttrsId($options);
		$options['class'] = 'btn-group ' . $options['class'];
		
		return $this->tag(BB::extend(array(
			'defaults' => array('xtag' => 'linkbtn'),
			'content' => $buttons
		), $options));
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
		if (!empty($src)) $image = $this->image($src, $imageOptions);
		
		// compose image link
		if (!empty($href)) $image = $this->tag(BB::extend(array(
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
		return $this->tag(BB::extend($options, array(
			'$++class' => ' thumbnail',
			'content' => array(
				$image,
				$caption
			)
		)));
	}




	
	
	
	
	
// -------------------------- //	
// ---[[   X T A G S   ]] --- //
// -------------------------- //
	
	protected function _registerXtags() {
		BB::registerXtag('container', array($this, 'xtagContainer'));
		BB::registerXtag('row', array($this, 'xtagRow'));
		BB::registerXtag('col', array($this, 'xtagCol'));
		BB::registerXtag('navbar', array($this, 'xtagNavbar'));
		BB::registerXtag('title', array($this, 'xtagTitle'));
		BB::registerXtag('linkbtn', array($this, 'xtagLinkBtn'));
		BB::registerXtag('btngroup', array($this, 'xtagBtnGroup'));
		BB::registerXtag('thumb', array($this, 'xtagThumb'));
	}
	
	public function xtagContainer($mode, $name, $text, $options) {
		switch ($mode) {
			case 'options' : return array($name, $text, $options);
			case 'beforeRender' : return $this->container($text, BB::clear($options, 'xtag'));
		}
	}
	
	public function xtagRow($mode, $name, $text, $options) {
		switch ($mode) {
			case 'options' : return array($name, $text, $options);
			case 'beforeRender' : return $this->row($text, BB::clear($options, 'xtag'));
		}
	}
	
	public function xtagCol($mode, $name, $text, $options) {
		switch ($mode) {
			case 'options' : return array($name, $text, $options);
			case 'beforeRender' : return $this->col($text, BB::clear($options, 'xtag'));
		}
	}
	
	public function xtagNavbar($mode, $name, $text, $options) {
		switch ($mode) {
			case 'options' : return array($name, $text, $options);
			case 'beforeRender' : 
				if (!empty($text)) $options['content'] = $text;
				return $this->navbar(BB::clear($options, 'xtag', false));
		}
	}
	
	public function xtagTitle($mode, $name, $text, $options) {
		switch ($mode) {
			case 'options' : return array($name, $text, $options);
			case 'beforeRender' : return $this->title($text, BB::clear($options, 'xtag'));
		}
	}
	
	public function xtagLinkBtn($mode, $name, $text, $options) {
		switch ($mode) {
			case 'options': 
				$options = BB::extend(array('href' => ''), $options);
				return array($name, $text, $options);
			case 'beforeRender': 
				$href = $options['href'];
				$options = BB::clear($options, array('xtag', 'href'));
				$options = BB::clear($options, array_keys($this->_tagInteralOptions));
				return $this->linkBtn($text, $href, $options);
		}
	}
	
	public function xtagBtnGroup($mode, $name, $text, $options) {
		switch ($mode) {
			case 'options' : return array($name, $text, $options);
			case 'beforeRender' : return $this->btnGroup($text, BB::clear($options, 'xtag'));
		}
	}
	
	public function xtagThumb($mode, $name, $text, $options) {
		switch ($mode) {
			case 'options' :
				return array($name, $text, $options);
			case 'beforeRender' : 
				if (empty($options['caption'])) $options['caption'] = $text;
				return $this->thumb(BB::clear($options, 'xtag'));
		}
	}
	
}