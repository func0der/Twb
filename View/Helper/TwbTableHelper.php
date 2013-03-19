<?php
/**
 * Twitter Bootstrap Table Helper
 */

App::uses('BbTableHelper', 'BB.View/Helper');

class TwbTableHelper extends BbTableHelper {
	
	public $defaults = array(
		'class'					=> 'table',
		'customClass'			=> '',
		'bordered'				=> true,
		'striped'				=> true,
		'hover'					=> true,
		'condensed'				=> false,
		'responsive'			=> 'id',
		'responsiveOnMobile'	=> true
	);
	
	
	
	
	/**
	 * apply some high level configuration options
	 */
	public function render($data, $options = array()) {
		
		if (empty($options)) {
			$options = array();
		}
		
		// "class" property will extend computed classes
		if (!empty($options['class'])) {
			$options['customClass'] = $options['class'];
			unset($options['class']);
		}
		
		// apply styled options available to configure table
		$options = BB::extend(array(
			'class'					=> $this->defaults['class'],
			'bordered'				=> $this->defaults['bordered'],
			'striped'				=> $this->defaults['striped'],
			'hover'					=> $this->defaults['hover'],
			'condensed'				=> $this->defaults['condensed'],
			'customClass'			=> '',
			'responsive'			=> $this->defaults['responsive'],
			'responsiveOnMobile'	=> $this->defaults['responsiveOnMobile']
		), $options);
		
		if ($options['bordered']) {
			$options['class'] .= ' table-bordered';
		}
		if ($options['striped']) {
			$options['class'] .= ' table-striped';
		}
		if ($options['hover']) {
			$options['class'] .= ' table-hover';
		}
		if ($options['condensed']) {
			$options['class'] .= ' table-condensed';
		}
		
		// prepare responsive options
		$this->_setupResponsive($options['responsive']);
		if ($this->_responsive !== false) $options['data-responsive'] = 'on';
		if ($options['responsiveOnMobile']) $options['data-responsive'] = 'mobile';
		
		$options['class'] = trim($options['class'] . ' ' . $options['customClass']);
		$options = BB::clear($options, array(
			'bordered',
			'striped',
			'hover',
			'condensed',
			'customClass',
			'responsive',
			'responsiveOnMobile'
		), false);
		
		return parent::render($data, $options);	
	}
	
	
	/**
	 * Apply responsive classes to each column before to render it
	 */
	public function renderThead() {
		if ($this->_responsive !== false) {
			foreach ($this->_responsive as $area=>$columns) {
				if ($area == 'persist') $area = 'essential persist';
				foreach ($columns as $columnName) {
					// add model to the column's name
					if (strpos($columnName, '.') === false && $columnName !== 'actions') {
						$columnName = $this->model . '.' . $columnName;
					}
					if (!empty($this->columns[$columnName])) {
						$columnConfig = BB::extend(array(
							'thead' => array('class' => '')
						), $this->columns[$columnName]);
						
						$columnConfig['thead']['class'] = trim($columnConfig['thead']['class'] . ' ' . $area);
						$this->columns[$columnName] = $columnConfig;
					}
				}
			}
		}
		return parent::renderThead();
	}
	
	
	
	/**
	 * Force text align to right for actions column!
	 */
	public function configActions($config) {
		$config = parent::configActions($config);
		$config = BB::extend(array(), $config, array(
			'thead' => array(
				'$++style' => ';text-align:right;'
			),
			'tbody' => array(
				'$++style' => ';text-align:right;'
			)
		));
		return $config;
	}
	
	
	/**
	 * It is able to interpret various kind of action names
	 */
	public function tbodyActions($val, $data) {
		
		$actions = array();
		foreach ($this->actions as $name=>$config) {
			
			// "!!" = danger
			if (substr($name, 0, 2) === '!!') {
				$name = substr($name, 2);
				$config = BB::extend(array(
					'type' => 'danger',
					'icon-white' => true
				), $config);
				
			// "!" = warning
			} elseif (substr($name, 0, 1) === '!') {
				$name = substr($name, 1);
				$config = BB::extend(array(
					'type' => 'warning',
					'icon-white' => true
				), $config);
				
			// "?" = info
			} elseif (substr($name, 0, 1) === '?') {
				$name = substr($name, 1);
				$config = BB::extend(array(
					'type' => 'info',
					'icon-white' => true
				), $config);
				
			// "+" = success
			} elseif (substr($name, 0, 1) === '+') {
				$name = substr($name, 1);
				$config = BB::extend(array(
					'type' => 'success',
					'icon-white' => true
				), $config);
				
			// "-" = inverse
			} elseif (substr($name, 0, 1) === '-') {
				$name = substr($name, 1);
				$config = BB::extend(array(
					'type' => 'inverse',
					'icon-white' => true
				), $config);
				
			// ">" = link
			} elseif (substr($name, 0, 1) === '>') {
				$name = substr($name, 1);
				$config = BB::extend(array(
					'type' => 'link',
					'icon-white' => false
				), $config);
				
			// first letter is uppercased: primary
			} elseif (substr($name, 0, 1) === strtoupper(substr($name, 0, 1))) {
				$config = BB::extend(array(
					'type' => 'primary',
					'icon-white' => true
				), $config);
			}
			
			switch (strtolower($name)) {
				case 'read':
					$actions[] = $this->actionRead($config, $data['dataRow'], $data['dataIdx']);
					break;
				case 'edit':
					$actions[] = $this->actionEdit($config, $data['dataRow'], $data['dataIdx']);
					break;
				case 'delete':
					$actions[] = $this->actionDelete($config, $data['dataRow'], $data['dataIdx']);
					break;
			}
		}
		
		return $this->Html->tag(array(
			'class' => 'btn-group',
			'content' => $actions
		));
	}
	
	public function actionRead($options, $row, $idx) {
		$options = BB::extend(array(
			'xtag'	=> 'linkbtn',
			'icon'	=> 'file',
			'icon-only' => true,
			'size'	=> 'small',
			'show'	=> __('read'),
			'title' => __('read item'),
			'href'	=> array(
				'action' => 'read',
				$row[$this->model]['id']
			)
		), BB::setStyle($options, 'show'));
		
		$options['href'] = $this->actionUrl($options['href'], $row, $idx);
		return $this->Html->tag($options);
	}
	
	public function actionEdit($options, $row, $idx) {
		$options = BB::extend(array(
			'xtag'	=> 'linkbtn',
			'icon'	=> 'pencil',
			'icon-only' => true,
			'size'	=> 'small',
			'show'	=> __('edit'),
			'title' => __('edit item'),
			'href'	=> array(
				'action' => 'edit',
				$row[$this->model]['id']
			)
		), BB::setStyle($options, 'show'));
		
		$options['href'] = $this->actionUrl($options['href'], $row, $idx);
		return $this->Html->tag($options);
	}
	
	public function actionDelete($options, $row, $idx) {
		$options = BB::extend(array(
			'xtag'	=> 'linkbtn',
			'type' => 'danger',
			'icon' => 'trash',
			'icon-only' => true,
			'icon-white' => true,
			'size'	=> 'small',
			'show'	=> __('delete'),
			'title' => __('delete item'),
			'confirm' => 'confirm?',
			'href'	=> array(
				'action' => 'delete',
				$row[$this->model]['id']
			)
		), BB::setStyle($options, 'show'));
		
		$options['href'] = $this->actionUrl($options['href'], $row, $idx);
		return $this->Html->tag($options);
	}
	
	
	
	
	
	/**
	 * Fill a correct "responsive" configuration
	 */
	protected function _setupResponsive($options) {
		
		// string2array for main key definition
		if (is_string($options)) {
			$tmp = explode(',', $options);
			$options = array('essential' => array());
			foreach ($tmp as $columnName) {
				$options['essential'][] = trim($columnName);
			}
		}
		
		$options = BB::extend(array(
			'persist'	=> array(),
			'essential' => array(),
			'optional'	=> array()
		), $options);
		
		// string2array for every area
		foreach ($options as $key=>$val) {
			if (is_string($val)) {
				$tmp = explode(',', $val);
				$val = array();
				foreach ($tmp as $columnName) {
					$val[] = trim($columnName);
				}
				$options[$key] = $val;
			}
		}
		
		// store into internal property to be used at rendering time
		$tmp = BB::clear($options);
		if (!empty($tmp)) {
			// actions column is always required for the table!
			$options['persist'][] = 'actions';
			$this->_responsive = $options;
		} else {
			$this->_responsive = false;
		}
		
	}
	
}


