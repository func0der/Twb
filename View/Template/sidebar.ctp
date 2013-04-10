<?php
/**
 * Internal Page Template with right sidebar
 * Twitter Bootstrap - UI Plugin
 */
$this->extend('Twb.Template/container');

/**
 * Fetch sidebar content
 */
$sidebar = $this->fetch('sidebar');
if (empty($sidebar)) {
	$sidebar = BB::read('Twb.layout.sidebar.content', '--sidebar--');
}

/**
 * Setup columns width & offset
 */
$offset =  BB::read('Twb.layout.sidebar.offset', 0);
$sidebarSpan = BB::read('Twb.layout.sidebar.span', 3);
$contentSpan = 12 - $sidebarSpan - $offset;

/**
 * Compose content declarative block
 */
$content = array(
	'span' => $contentSpan,
	'class' => 'twb-span-content',
	'content' => $this->fetch('content')
);

/**
 * Compose sidebar declarative block
 */
$sidebar = array(
	'span' => $sidebarSpan,
	'class' => 'twb-span-sidebar',
	'content' => $sidebar
);


/**
 * Apply Offset
 */
if (BB::read('Twb.layout.sidebar.inverse')) {
	$content = BB::extend($content, array(
		'offset' => $offset
	));
} else {
	$sidebar = BB::extend($sidebar, array(
		'offset' => $offset
	));
}

/**
 * Apply Custom Config
 */
$content = BB::extend($content, BB::read('Twb.layout.content.config', array()));
$content = BB::extend($content, BB::read('Twb.layout.content.configContent', array()));
$sidebar = BB::extend($sidebar, BB::read('Twb.layout.sidebar.config', array()));


/**
 * Sidebar Content
 * sidbear il placed on the right by default
 */

echo $this->Html->tag(array(
	'xtag' => 'row',
	'class' => 'twb-tpl-sidebar',
	'fluid' => BB::read('Twb.layout.container.fluid'),
	'if' => BB::read('Twb.layout.sidebar.inverse'),
	'content' => array($sidebar, $content),
	'else' => array($content, $sidebar)
));