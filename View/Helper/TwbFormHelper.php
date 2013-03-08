<?php
/**
 * FormHelper Customization Class
 * Twitter Bootstrap - UI Plugin
 *
 * @author mpeg
 */

App::import( 'View/Helper', 'FormHelper' );

class TwbFormHelper extends FormHelper {
	
	
	/**
	 * Form Opening
	 * customizes defaults for various scenario of forms
	 */
	public function create($model = null, $options = array()) {
		
		$options = BB::setDefaultAttrs($options, array(
			'type' => 'horizontal',
			'class' => '',
			'inputDefaults' => array()
		));
		
		// -- Inline Form
		switch ($options['type']) {
			
			case 'inline':
				$options['class'] = trim('form-inline ' . $options['class']);
				break;
			
			case 'horizontal':
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
		}
		
		
		#ddebug($options);
		return parent::create($model, BB::clear($options, array(
			'type'
		)));
	}
	
	
	public function __input($name = '', $options = array()) {
		
		$options = BB::setDefaults($options, array(
			'label' => array()
		), 'label');
		
		$options['label'] = BB::setDefaults($options['label'], null, 'text');
		#ddebug($this->_inputDefaults);
		
		return parent::input($name, $options);
	}
	
}
