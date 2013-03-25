<?php
/**
 * FormHelper Customization Class
 * Twitter Bootstrap - UI Plugin
 *
 * @author mpeg
 */

App::import( 'View/Helper', 'FormHelper' );

class TwbFormHelper extends FormHelper {
	
	public $type = '';
	
	/**
	 * Form Opening
	 * customizes defaults for various scenario of forms
	 */
	public function create($model = null, $options = array()) {
		
		$options = BB::setDefaults($options, array(
			'validate' => false,
			'type' => '',
			'class' => '',
			'sticky' => true,
			'ajax' => true,
			'inputDefaults' => array(),
		), array(
			'boolean' => 'validate',
			'else' => 'type'
		));
		
		// sticky form data-option
		if ($options['sticky'] === true) {
			$options['data-twb-sticky'] = 'on';
		} unset($options['sticky']);
		
		// ajax form data-option
		if ($options['ajax'] === true) {
			$options['data-twb-ajax'] = 'on';
		} unset($options['ajax']);
		
		// apply "novalidate" options to prevent HTML5 validation
		if ($options['validate'] === false) {
			$options['novalidate'] = true;
		} unset($options['validate']);
		
		
		switch ($options['type']) {
			
			case 'inline':
				$this->type = 'inline';
				$options['class'] = trim('form-inline ' . $options['class']);
				$options['inputDefaults'] = array(
					'div' => false,
					'label' => false,
					'error' => false
				);
				break;
			
			case 'horizontal':
				$this->type = 'horizontal';
				$options['class'] = trim('form-horizontal ' . $options['class']);
				$options['inputDefaults'] = BB::extend(array(
					'div' => array(
						'class' => 'control-group'
					),
					'label' => array(
						'class' => 'control-label'
					),
					'error' => array(
						'attributes' => array(
							'wrap'	=> 'div',
							'class' => 'help-inline'
						)
					),
					'between' => '<div class="controls">',
					'after'	=> '</div>',
					'format' => array('before', 'label', 'between', 'input', 'error', 'after' )
				), $options['inputDefaults']);
				break;
			
			case 'standard':
			default:
				$this->type = 'standard';
				$options['class'] = trim('form-standard ' . $options['class']);
				$options['inputDefaults'] = BB::extend(array(
					'div' => array(
						'class' => 'control-group'
					),
					'error' => array(
						'attributes' => array(
							'wrap'	=> 'div',
							'class' => 'help-inline'
						)
					),
					'between' => '<div class="controls">',
					'after'	=> '</div>',
					'format' => array('before', 'label', 'between', 'input', 'after', 'error' )
				), $options['inputDefaults']);
				break;
				
		}
		
		return parent::create($model, BB::clear($options, array(
			'type'
		)));
	}
	
	
	/**
	 * Close Form
	 * handles multiple action buttons.
	 * 
	 * $options = 'save,exit,cancel'
	 * $options = array('save', 'exit')
	 * $options = array('save' => 'custom label')
	 * $options = array(
	 *		'action' => 'save, exit'
	 * )
	 * 
	 * 
	 */
	public function end($options = null) {
		
		$actions = array();
		
		// fetch options and translates into options and actions array
		if (func_num_args() == 1) {
			if (is_string($options)) {
				$actions = BB::extend($actions, explode(',', $options));
				$options = array();
			} elseif (BB::isVector($options) || (BB::isAssoc($options) && !isset($options['actions']))) {
				$actions = $options;
				$options = array();
			}
			$options = BB::setDefaults($options, array(
				'actions' => array()
			));
			if (is_string($options['actions'])) {
				$options['actions'] = explode(',', $options['actions']);
			}
			$actions = BB::extend($actions, $options['actions']);
			unset($options['actions']);
		} else {
			$actions = func_get_args();
		}
		
		// render actions
		$ractions = '';
		foreach ($actions as $action) {
			
			// action button from string!
			if (is_string($action)) {
				$ActionName = trim($action);
				$actionName = strtolower($ActionName);
				switch ($actionName) {
					case 'save':
						$action = array(
							'value' => __('save'),
							'name' => 'form-action-save',
						);
						break;
					case 'saveexit':
						$action = array(
							'value' => __('save and exit'),
							'name' => 'form-action-save-and-exit',
						);
						break;
					case 'exit':
						$action = array(
							'value' => __('save and exit'),
							'name' => 'form-action-save-and-exit',
						);
						break;
					case 'cancel':
						$action = array(
							'value' => __($actionName),
							'name' => 'form-action-cancel'
						);
						if (empty($options['group'])) {
							$action['$++class'] = ' btn-link';
						}
						break;
					case 'reset':
						$action = array(
							'value' => __($actionName),
							'name' => 'form-action-reset',
							'type' => 'reset'
						);
						if (empty($options['group'])) {
							$action['$++class'] = ' btn-link';
						}
						break;
					default:
						$action = array(
							'value' => __($ActionName),
							'name' => $actionName
						);
				}
				
				// apply danger
				if (substr($ActionName, 0, 2) == '!!') {
					$action['$++class'] = ' btn-danger';
					if (substr($action['value'], 0, 2) == '!!') {
						$action['value'] = substr($action['value'], 2);
					}
				// apply warning
				} elseif (substr($ActionName, 0, 1) == '!') {
					$action['$++class'] = ' btn-warning';
					if (substr($action['value'], 0, 1) == '!') {
						$action['value'] = substr($action['value'], 1);
					}
				// apply info
				} elseif (substr($ActionName, 0, 1) == '?') {
					$action['$++class'] = ' btn-info';
					if (substr($action['value'], 0, 1) == '?') {
						$action['value'] = substr($action['value'], 1);
					}
				// apply success
				} elseif (substr($ActionName, 0, 1) == '+') {
					$action['$++class'] = ' btn-success';
					if (substr($action['value'], 0, 1) == '+') {
						$action['value'] = substr($action['value'], 1);
					}
				// apply primary
				} elseif (substr($ActionName, 0, 1) === strtoupper(substr($actionName, 0, 1))) {
					$action['$++class'] = ' btn-primary';
				}
				
				// action's defaults
				$action = BB::extend(array(
					'tag'	=> 'input',
					'type'	=> 'submit',
					'class'	=> 'btn'
				), $action);
				
			}
			
			
			
			// append blank char to separate buttons if no group is applied
			$ractions.= $this->Html->tag($action) . ' ';
		}
		
		// apply defaults
		$options = BB::extend(array(
			'group' => '',
			'class' => '',
			'before' => '',
			'between' => '',
			'after' => ''
		), $options);
		
		// apply btn group if required
		// "group" options should be "true" or string with class to apply
		// to actions wrapper (useful for vertical groups)
		if (count($actions) > 1 && $options['group']) {
			if (!is_string($options['group'])) {
				$options['group'] = 'btn-group';
			}
			$ractions = $this->Html->tag(array(
				'class' => $options['group'],
				'content' => $ractions
			));
		}
		
		// extract mishellaneous code injection vars
		$before = $options['before'];
		$between = $options['between'];
		$after = $options['after'];
		
		// clean up options
		$options = BB::clear($options, array(
			'group',
			'before',
			'between',
			'after'
		), false);
		
		// setup base actions class
		$options['class'] = trim('form-actions ' . $options['class']);
		
		// apply wrapper
		$wrapper = $this->Html->tag(BB::extend(array(
			'content' => array(
				$between,
				$ractions,
				$after
			)
		), $options));
		
		return $before . $wrapper . parent::end(null);
	}
	
	
	/**
	 * Implement generic form input field following form's style.
	 */
	public function input($name = '', $options = array()) {
		
		if (!empty($options['type']) && $options['type'] == 'checkbox') {
			return $this->checkbox($name, $options);
		}
		
		if (!empty($options['type']) && $options['type'] == 'radio') {
			$options = BB::extend(array('options' => array()), $options);
			return $this->radio($name, $options['options'], BB::clear($options, 'options'));
		}
		
		$options = $this->_labelOptions($options);
		$options = $this->_helperOptions($options);
		
		// apply form's type based variations
		switch ($this->type) {
			case 'inline':
				if (empty($options['placeholder'])) {
					$options['placeholder'] = $options['label']['text'];
				}
				$options['label'] = false;
				break;
		}
		
		return parent::input($name, BB::extend($this->_inputDefaults, $options));
	}
	
	
	/**
	 * Custom Checkbox
	 */
	
	public function checkbox($fieldName, $options = array()) {
		
		// apply label defaults
		$options = $this->_labelOptions($options);
		
		// apply defaults
		$options = BB::extend(array(
			'type'		=> 'checkbox',
			'value'		=> 1,
			'options'	=> array(),
			'inline'	=> false
		), $options);
		
		
		// input wrapper options:
		$_options = BB::extend(BB::clear($options, array(
			'value',
			'options',
			'inline',
			'helper',
		), false), array(
			'type' => 'text'
		));
		
		
		// style fix
		// standard's form single checkbox display itself inline without
		// control's label!
		if ($this->type === 'standard' && empty($options['options'])) {
			$options['options'] = array($fieldName => array(
				'text' => !empty($options['label']['text'])?$options['label']['text']:$fieldName,
				'helper' => !empty($options['helper'])?$options['helper']:''
			));
			$_options['label'] = false;
		}
		
		
		// multiple checkboxes
		$items = '';
		if (!empty($options['options'])) {
			foreach ($options['options'] as $itemName=>$config) {
				
				// apply defaults for label text to display near field
				$config = BB::setDefaults($config, array(
					'text' => ''
				), 'text');
				
				// export checkbox text
				$itemText = $config['text'];
				unset($config['text']);
				if (empty($itemText)) {
					$itemText = $itemName;
				}
				
				if (is_numeric($itemName)) {
					$itemName = $itemText;
				}
				
				// extends item's config with wide control configuration
				// setup single checkout value
				$config = BB::extend(BB::clear($options, array('options', 'inline')), $config);
				$config = $this->_helperOptions($config);
				
				// find control's ID to apply "label for" attribute
				$itemControl = parent::checkbox($itemName, $config);
				$itemControlId = '';
				$matches = '';
				preg_match_all("|id=\"(.*)\"|U", $itemControl, $matches);
				if (!empty($matches[1][1])) $itemControlId = $matches[1][1];
				
				// create checkbox control with label
				$item = $this->Html->tag(array(
					'tag' => 'label',
					'for' => $itemControlId,
					'class' => 'checkbox' . ($options['inline']?' inline':''),
					'data-toggle-checkbox' => 'on',
					'content' => array(
						$itemControl,
						$itemText
					)
				));
				
				$items.= $item;
			}
		
		// single checkbox
		} else {
			$options = $this->_helperOptions($options);
			$items = parent::checkbox($fieldName, $options);
		}
		
		// generate input wrapper code and fill with checkbox core
		$field = $this->input($fieldName, $_options);
		preg_match_all("|<input(.*)/>|U", $field, $matches);
		return str_replace( $matches[0][0], $items, $field );
	}
	
	
	/**
	 * Custom Radio
	 */
	public function radio($fieldName, $options = array(), $attributes = array()) {
		
		// apply custom label as second string param (shortcut)
		if (is_string($options)) {
			$attributes = $options;
			$options = array();
		}
		
		// internal naming conventions normalization
		// method declaration must be compatible to parent class (PHP 5.4)
		$values = $options;
		$options = $attributes;
		
		// apply label defaults
		// "true" options = inline
		if ($options === true) $options = array('inline' => true);
		$options = $this->_labelOptions($options);
		
		
		// apply defaults
		$options = BB::extend(array(
			'type' => 'radio',
			'inline' => false
		), $options);
		
		
		// input wrapper options:
		$_options = BB::extend(BB::clear($options, array(
			'inline',
			'helper',
		), false), array(
			'type' => 'text'
		));
		
		// default value for single radio button
		if (empty($values)) {
			$values = array($fieldName => $fieldName);
		}
		
		// build CakePHP compliant value array from rich Twb definition
		// compatible form.
		$_values = array();
		foreach ($values as $key=>$val) {
			if (is_array($val)) {
				$val = BB::extend(array(
					'text' => $key
				), $val);
				$values[$key] = $val;
				
			} else {
				$val = array('text' => $val);
				$values[$key] = $val;
			}
			if (is_numeric($key)) {
				$_values[$val['text']] = $val['text'];
			} else {
				$_values[$key] = $val['text'];
			}
		}
		
		// build original radio fields list
		$radio = $radio = parent::radio($fieldName, $_values, array(
			'legend' 	=> false,
			'separator' => '--**--'
		));
		
		// modify markup to fit Twb requirements
		$items = '';
		$keys = array_keys($values);
		foreach (explode('--**--', $radio) as $i=>$radio) {
			preg_match_all("|<label(.*)>|U", $radio, $matches);
			$radio = str_replace( $matches[0][0], '', $radio );
			$radio = $matches[0][0] . $radio;
			
			// compose helper attributes
			$helper_attrs = '';
			if (!empty($values[$keys[$i]]['helper'])) {
				foreach ($this->_helperOptions(array('type' => 'radio', 'helper' => $values[$keys[$i]]['helper'])) as $attrName=>$attrVal) {
					$helper_attrs.= $attrName.'="'.$attrVal.'" ';
				}
			}
			
			// apply class and inline attribute
			$radio = str_replace('<label', '<label class="radio' . ($options['inline']===true?' inline':'') . '" ', $radio);
			$radio = str_replace('<input ', '<input ' . $helper_attrs, $radio);
			
			$items.= $radio;
		}
		
		// generate input wrapper code and fill with checkbox core
		$field = $this->input($fieldName, $_options);
		preg_match_all("|<input(.*)/>|U", $field, $matches);
		return str_replace( $matches[0][0], $items, $field );
	}
	
	
	
	
	protected function _labelOptions($options) {
		// string options converted to label
		$options = BB::setDefaults($options, array(
			'label' => array()
		), 'label');
		
		// string label converted to array configuration
		$options['label'] = BB::setDefaults($options['label'], null, 'text');
		
		return $options;
	}
	
	
	/**
	 * Inject popover helper attributes into configuration
	 */
	protected function _helperOptions($options) {
		if (array_key_exists('helper', $options)) {
			
			$trigger = 'focus';
			if (!empty($options['type']) && in_array($options['type'], array('checkbox', 'radio', 'select'))) {
				$trigger.= ' hover';
			}
			
			$position = 'right';
			if (!empty($options['type']) && in_array($options['type'], array('checkbox', 'radio', 'select'))) {
				$position = $this->type == 'horizontal' ? 'top' : 'right';
			}
			
			// helper defaults
			$helper = BB::setDefaults($options['helper'], array(
				'title'		=> '',
				'content'	=> '',
				'position'	=> $position,
				'trigger'	=> $trigger
			), 'content');
			
			// fetch title from content's text
			if (empty($helper['title']) && strpos($helper['content'], '>>') !== false) {
				$tmp = explode('>>', $helper['content']);
				$helper['title'] = rtrim(array_shift($tmp));
				$helper['content'] = ltrim(implode('>>', $tmp));
			}
			
			// fill popover data-attributes
			$options['data-helper'] = 'on';
			if (!empty($helper['title']))		$options['data-original-title'] = $helper['title'];
			if (!empty($helper['content']))		$options['data-content']		= $helper['content'];
			if (!empty($helper['position']))	$options['data-placement']		= $helper['position'];
			if (!empty($helper['trigger']))		$options['data-trigger']		= $helper['trigger'];
		}
		return BB::clear($options, 'helper', false);
	}
	
	
	
	
	
	public function originalInput($fieldName, $options = array()) {
		return parent::input($fieldName, $options);
	}
	
	public function originalCheckbox($fieldName, $options = array()) {
		return parent::checkbox($fieldName, $options);
	}
	
	public function originalRadio($fieldName, $options = array(), $attributes = array()) {
		return parent::radio($fieldName, $options, $attributes);
	}
	
}
