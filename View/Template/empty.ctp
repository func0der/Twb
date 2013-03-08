<?php
/**
 * Internal Page Template
 * Twitter Bootstrap - UI Plugin
 */

/**
 * Responsive Header
 * logo + menu
 */

// 100% custom header
if ($this->elementExists('navbar')) {
	echo $this->element('navbar');

// configuration based header
} else {
	
	if ($this->elementExists('navbar-logo')) {
		$logo = $this->element('navbar-logo');
	} else {
		$logo = BB::read('Twb.layout.navbar.logo');
	}
	
	if ($this->elementExists('navbar-menu')) {
		$menu = $this->element('navbar-menu');
	} else {
		$menu = BB::read('Twb.layout.navbar.menu', BbMenu::tree('Twb.navbar'));
	}
	
	if ($this->elementExists('navbar-content')) {
		$content = $this->element('navbar-content');
	} else {
		$content = BB::read('Twb.layout.navbar.content');
	}
	
	echo $this->Html->tag(BB::extend(array(
		'xtag' => 'navbar',
		'fixed' => 'top',
		'container' => true,
		'responsive' => true,
		'logo' => $logo,
		'menu' => $menu,
		'content' => $content
	), BB::read('Twb.layout.navbar.config', array(
		'container' => (BB::read('Twb.layout.fluid')===true)?'fluid':''
	))));
}



/**
 * Wide Container
 */
echo $this->fetch('content');




/**
 * Footer
 */

// 100% custom footer
if ($this->elementExists('footbar')) {
	echo $this->element('footbar');

// configuration based footer
} else {
	echo $this->Html->tag(BB::extend(array(
		'xtag' => 'navbar',
		'fixed' => 'bottom',
		'container' => true,
		'content' => BB::read('Twb.layout.footbar.content')
	), BB::read('Twb.layout.footbar.config', array())));
}
