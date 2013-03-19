<?php
/**
 * Container Page Template
 * Twitter Bootstrap - UI Plugin
 */
$this->extend('Twb.Template/empty');


/**
 *  Flash Messages
 */
echo $this->Html->tag(array(
	'xtag' => 'container',
	'id' => 'twb-flash-messages',
	'fluid' => BB::read('Twb.layout.container.fluid', BB::read('Twb.layout.fluid')),
	'style' => 'margin-top:60px;margin-bottom:-60px',
	'content' => $this->Session->flash()
));

/**
 * Container Wrapper
 */
echo $this->Html->tag(array(
	'xtag' => 'container',
	'fluid' => BB::read('Twb.layout.container.fluid', BB::read('Twb.layout.fluid')),
	'style' => 'margin-top:50px;margin-bottom:50px',
	'content' => $this->fetch('content')
));
