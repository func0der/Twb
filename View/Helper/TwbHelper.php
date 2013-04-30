<?php
/**
 * HtmlHelper Extension
 * Twitter Bootstrap - UI Plugin
 */
App::import('View/Helper', 'BB.BbHtmlHelper');

class TwbHelper extends BbHtmlHelper {
	
	public $helpers = array(
		'Twb.TwbDropdown',
		'Twb.TwbLayout',
		'Twb.TwbLink',
		'Twb.TwbTypo',
		'Form',
		'Session'
	);
	
	/**
	 * Construct
	 * register xtags
	 */
	public function __construct(View $View, $settings = array()) {
		parent::__construct($View, $settings);
		
		BB::registerXtag('container',	array($this, 'xtagContainer'));
		BB::registerXtag('row',			array($this, 'xtagRow'));
		BB::registerXtag('col',			array($this, 'xtagCol'));
		BB::registerXtag('navbar',		array($this, 'xtagNavbar'));
		BB::registerXtag('title',		array($this, 'xtagTitle'));
		BB::registerXtag('link',		array($this, 'xtagLinkIco')); // override BB
		BB::registerXtag('linkbtn',		array($this, 'xtagLinkBtn'));
		BB::registerXtag('btngroup',	array($this, 'xtagBtnGroup'));
		
		BB::registerXtag('thumb',		array($this, 'xtagThumb'));
		BB::registerXtag('alert',		array($this, 'xtagAlert'));
		
		BB::registerXtag('table',		array($this, 'xtagTable'));
		
		BB::registerXtag('pageheader',	array($this, 'xtagPageHeader'));
		
		// forms
		BB::registerXtag('form',		array($this, 'xtagForm'));
		BB::registerXtag('input',		array($this, 'xtagInput'));
		
		// setup custom element for flash messages
		$this->Session->setFlashFallbackElement('Twb.flash/{key}');
	}
	
	



	
	
	
	
	
	
// ------------------------------------------------- //
// ---[[   M E T H O D S   U T I L I T I E S   ]]--- //
// ------------------------------------------------- //
	
	public function renderReturn($options) {
		
		$options = BB::setExtend(array('render' => true), $options);
		
		$render = $options['render'];
		unset($options['render']);
		if ($render) {
			return $this->tag($options);
		} else {
			return $options;
		}
	}
	
	public function fluidOptions($options) {
		$options = BB::extend(array(
			'fluid' => false
		), $options);
		
		if ($options['fluid']) {
			$options['class'] = str_replace('container', 'container-fluid', $options['class']);
		}
		
		return BB::clear($options, array('fluid'));
	}
	
	public function visibleOptions($options) {
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
	
	
	
	
	
	



	
	
	
	
	
// -------------------------- //	
// ---[[   X T A G S   ]] --- //
// -------------------------- //
	
	public function xtagContainer($mode, $name, $text, $options) {
		switch ($mode) {
			case 'options' : return array($name, $text, $options);
			case 'beforeRender' : return $this->TwbLayout->container($text, BB::clear($options, 'xtag'));
		}
	}
	
	public function xtagRow($mode, $name, $text, $options) {
		switch ($mode) {
			case 'options' : return array($name, $text, $options);
			case 'beforeRender' : return $this->TwbLayout->row($text, BB::clear($options, 'xtag'));
		}
	}
	
	public function xtagCol($mode, $name, $text, $options) {
		switch ($mode) {
			case 'options' : return array($name, $text, $options);
			case 'beforeRender' : return $this->TwbLayout->col($text, BB::clear($options, 'xtag'));
		}
	}
	
	public function xtagThumb($mode, $name, $text, $options) {
		switch ($mode) {
			case 'options' :
				return array($name, $text, $options);
			case 'beforeRender' : 
				if (empty($options['caption'])) $options['caption'] = $text;
				return $this->TwbLayout->thumb(BB::clear($options, 'xtag'));
		}
	}
	
	public function xtagAlert($mode, $name, $text, $options) {
		switch ($mode) {
			case 'options' :
				return array($name, $text, $options);
			case 'beforeRender': 
				$options['content'] = $text;
				return $this->TwbLayout->alert(BB::clear($options, 'xtag'));
		}
	}
	
	public function xtagNavbar($mode, $name, $text, $options) {
		switch ($mode) {
			case 'options' : return array($name, $text, $options);
			case 'beforeRender' : 
				if (!empty($text)) $options['content'] = $text;
				return $this->TwbLayout->navbar(BB::clear($options, 'xtag', false));
		}
	}
	
	public function xtagTitle($mode, $name, $text, $options) {
		switch ($mode) {
			case 'options' : return array($name, $text, $options);
			case 'beforeRender' : return $this->TwbTypo->title($text, BB::clear($options, 'xtag'));
		}
	}
	
	public function xtagLinkIco($mode, $name, $text, $options) {
		switch ($mode) {
			case 'options': 
				return array($name, $text, BB::extend(array(
					'href' => '',
					'confirm' => '',
					'escape' => false
				), $options));
			case 'render': 
				$href = $options['href'];
				$options = BB::clear($options, array('xtag', 'href'));
				$options = BB::clear($options, array_keys($this->_tagInteralOptions));
				return $this->TwbLink->linkIco($text, $href, $options);
		}
	}
	
	public function xtagLinkBtn($mode, $name, $text, $options) {
		if ($mode == 'beforeRender') {
			$options = BB::extend(array('href' => '/'), $options);
			$href = $options['href'];
			$options = BB::clear($options, array('xtag', 'href'));
			$options = BB::clear($options, array_keys($this->_tagInteralOptions));
			return $this->TwbLink->linkBtn($text, $href, $options);
		}
	}
	
	public function xtagBtnGroup($mode, $name, $text, $options) {
		switch ($mode) {
			case 'options' : return array($name, $text, $options);
			case 'beforeRender' : return $this->TwbLink->btnGroup($text, BB::clear($options, 'xtag'));
		}
	}
	
	public function xtagTable($mode, $name, $text, $options) {
		$options = BB::extend(array(
			'helper' => 'Twb.TwbTable',
			'data' => array()
		), $options);
		// load table helper
		$tableHelper = $this->_View->loadHelper($options['helper']);
		// render
		return $tableHelper->render($options['data'], BB::clear($options, array('xtag', 'helper', 'data')));
	}
	
	public function xtagPageHeader($mode, $name, $text, $options) {
		$options = BB::extend(array(
			'title' => '',
			'actions' => array()
		), $options);
		return $this->TwbLayout->pageHeader($options['title'], $options['actions'], BB::clear($options, array('xtag', 'title', 'actions')));
	}
	
	public function xtagForm($mode, $name, $text, $options) {
		
		$options = BB::extend(array(
			'model' => '',
			'actions' => 'Save',
			'end' => array(),
			'sections' => array(),
			'fields' => array()
		), $options);
		
		
		// extract create() options
		$_create = BB::clear($options, array(
			'model',
			'actions',
			'end',
			'xtag',
			'fields',
			'sections'
		));
		
		// prepare form content as a list of tags to be rendered
		if (BB::isAssoc($text)) {
			$text = array($text);
		}
		
		// append form sections to be rendered
		if (!empty($options['sections'])) {
			foreach($options['sections'] as $sectionId=>$sectionFields) {
				if (is_numeric($sectionId)) {
					$sectionId = uniqid('form-') . '-' . $sectionId;
				}
				if (is_array($sectionFields)) {
					$sectionFields = BB::extend(array(
						'legend' => $sectionId,
						'options' => array()
					), $sectionFields);
					$sectionName = $sectionFields['legend'];
					$sectionOptions = $sectionFields['options'];
					unset($sectionFields['legend']);
					unset($sectionFields['options']);
				} else {
					$sectionName = $sectionId;
					$sectionOptions = array();
				}
				
				$sectionContent = BB::extend(array(array(
					'tag' => 'legend',
					'show' => $sectionName
				)), $this->_xtagFormFields($sectionFields));
				
				$text[] = BB::extend(array(
					'tag' => 'fieldset',
					'id' => $sectionId,
					'content' => $sectionContent
				), BB::setDefaultAttrs($sectionOptions));
				
				
			}
		}
		
		// append wide fields fields
		if (!empty($options['fields'])) {
			$text = BB::extend($text, $this->_xtagFormFields($options['fields']));
		}
		
		$options['end'] = BB::extend(array(
			'actions' => $options['actions']
		), $options['end']);
		
		$code = $this->Form->create($options['model'], $_create);
		$code.= $this->tag($text);
		$code.= $this->Form->end($options['end']);
		
		return $code;
	}
	
	
	/**
	 * translate a list of fields or a string into a list of tag() configure
	 * array.
	 * 
	 * $fields = 'field1, field2'
	 * $fields = array('field1', 'field2')
	 * $fields = array(
	 *	'field1' => 'label',
	 *  'field2' => array(
	 *    'type' => 'checkbox',
	 *    'label' => 'Field Label'
	 *  )
	 * )
	 */
	protected function _xtagFormFields($fields) {
		
		if (is_string($fields)) {
			$fields = explode(',', $fields);
		}
		
		$list = array();
		foreach ($fields as $name=>$cfg) {
			if (is_numeric($name)) {
				$name = $cfg;
				$cfg = array();
			}
			$cfg = BB::setDefaults($cfg, array(
				'xtag' => 'input',
				'name' => $name
			), 'label');
			$list[] = $cfg;
		}
		return $list;
	}
	
	public function xtagInput($mode, $name, $text, $options) {
		
		$options = BB::extend(array(
			'name' => ''
		), $options);
		
		return $this->Form->input($options['name'], BB::clear($options, array(
			'xtag',
			'name'
		)));
		
	}
	
	
}