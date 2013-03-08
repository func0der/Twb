<?php
/**
 * Internal Page Template
 * Twitter Bootstrap - UI Plugin
 */

/**
 * Responsive Header
 * logo + menu
 */
if ($this->elementExists('navbar')) {
	echo $this->element('navbar');
} else {
	echo $this->Html->tag(BB::extend(array(
		'xtag' => 'navbar',
		'fixed' => 'top',
		'container' => true,
		'responsive' => true,
		'logo' => BB::read('Twb.layout.navbar.logo'),
		'menu' => BB::read('Twb.layout.navbar.menu', BbMenu::tree('Twb.navbar')),
		'content' => BB::read('Twb.layout.navbar.content')
	), BB::read('Twb.layout.navbar.config', array())));
}



/**
 * Wide Container
 */
echo $this->fetch('content');




/**
 * Footer
 */
if ($this->elementExists('footbar')) {
	echo $this->element('footbar');
} else {
	echo $this->Html->tag(BB::extend(array(
		'xtag' => 'navbar',
		'fixed' => 'bottom',
		'container' => true,
		'content' => BB::read('Twb.layout.footbar.content')
	), BB::read('Twb.layout.footbar.config', array())));
}
