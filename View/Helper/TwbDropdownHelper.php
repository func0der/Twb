<?php
/**
 * Dropdown Component Helper
 * Twitter Bootstrap - UI Plugin
 */
class TwbDropdownHelper extends AppHelper {
	
	public $helpers = array(
		'Html'
	);
	
	
	
	/**
	 * static callback who composes a dropdown menu item
	 * -> only use for first-leve items
	 */
	public static function itemCallback($_View, $i, $item) {
		
		$link = BB::extend(array(
			'xtag' => 'link',
			'href' => $item['BbMenu']['url'],
		), BB::clear($item['BbMenu'], array('url', 'active')));

		// submenu presence
		if (!empty($item['children'])) {

			$link = BB::extend($link, array(
				'$++class' => ' dropdown-toggle',
				'$++show' => ' <b class="caret"></b>',
				'data-toggle' => 'dropdown'
			));
			
			$li = array(
				'class' => ' dropdown',
				'content' => array(
					$link,
					array(
						'xtag' => 'list',
						'class' => 'dropdown-menu',
						'items' => $item['children'],
						'content' => 'TwbDropdownHelper::subItemCallback'
					)
				)
			);

		// no sub menus
		} else {
			$li = array(
				'content' => $_View->Html->tag($link)
			);
		}

		// active item
		if ($item['BbMenu']['active']) {
			$li = BB::extend($li, array('$++class' => ' active'));
		}

		return $li;
	}
	
	// dropdown sub-item callback
	public static function subItemCallback($_View, $i, $item) {
		
		$link = BB::extend(array(
			'xtag' => 'link',
			'href' => $item['BbMenu']['url'],
		), BB::clear($item['BbMenu'], array('url', 'active')));

		// submenu presence
		if (!empty($item['children'])) {
			
			$li = array(
				'$++class' => ' dropdown-submenu',
				'content' => array(
					$link,
					array(
						'xtag' => 'list',
						'class' => 'dropdown-menu',
						'items' => $item['children'],
						'content' => 'TwbDropdownHelper::itemCallback'
					)
				)
			);

		// no sub menus
		} else {
			$li = array(
				'content' => $_View->Html->tag($link)
			);
		}

		// active item
		if ($item['BbMenu']['active']) {
			$li = BB::extend($li, array('$++class' => ' active'));
		}

		return $li;
	}
	
	
}
