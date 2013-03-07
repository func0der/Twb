<?php
/**
 * Internal Page Template
 * Twitter Bootstrap - UI Plugin
 */
$this->extend('Twb.Template/container');

/**
 * One Column Content
 */
echo $this->Html->tag(array(
	'xtag' => 'row',
	'fluid' => BB::read('Twb.layout.container.fluid'),
	'content' => array(
		array(
			'content' => $this->fetch('content')
		)
	)
));