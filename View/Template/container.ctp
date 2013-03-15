<?php
/**
 * Internal Page Template
 * Twitter Bootstrap - UI Plugin
 */
$this->extend('Twb.Template/empty');

/**
 * One Column Content
 */
echo $this->Html->tag(array(
	'xtag' => 'container',
	'fluid' => BB::read('Twb.layout.container.fluid', BB::read('Twb.layout.fluid')),
	'style' => 'margin-top:50px;margin-bottom:50px',
	'content' => $this->fetch('content')
));
