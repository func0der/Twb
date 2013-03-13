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
			'inputDefaults' => array(),
		), array(
			'boolean' => 'validate',
			'else' => 'type'
		));
		
		// apply "novalidate" options to prevent HTML5 validation
		if ($options['validate'] === false) {
			$options['novalidate'] = true;
		}
		unset($options['validate']);
		
		
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
			
			default:
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
	 */
	public function end($options = null) {
		
		$actions = array();
		
		// fetch options and translates into options and actions array
		if (func_num_args() == 1) {
			if (is_string($options)) {
				$actions = BB::extend($actions, explode(',', $options));
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
	
	
	public function input($name = '', $options = array()) {
		
		// string options converted to label
		$options = BB::setDefaults($options, array(
			'label' => array()
		), 'label');
		
		// string label converted to array configuration
		$options['label'] = BB::setDefaults($options['label'], null, 'text');
		
		// apply helper options
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
	 * Inject popover helper attributes into configuration
	 */
	public function _helperOptions($options) {
		if (array_key_exists('helper', $options)) {
			// helper defaults
			$helper = BB::setDefaults($options['helper'], array(
				'title'		=> '',
				'content'	=> '',
				'position'	=> 'right',
				'trigger'	=> 'focus'
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
	
}
