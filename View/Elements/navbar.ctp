<?php
/**
 * Navbar Element
 * Twitter Bootstrap - UI Plugin
 * ==============================
 *
 * Help to easily compose a standard navbar with options
 */


/**
 * href - target
 * show - text to show
 * title - link's title attribute
 * image - image src (CakePHP format or absolute), replace "show"
 * alt - image alt attribute
 * content - array - generic content to brand link
 * linkOptions - array - options to link
 * imageOptions - array - options to image
 */
if (!isset($logo)) {
	$logo = null;
}

// @TODO
if (!isset($menu)) {$menu = null;}

// [true|top|bottom] - to create a fixed toolbar. true = top
if (!isset($fixed)) {
	$fixed = null;
}

// apply a container to navbar contents and align it center
if (!isset($container)) {
	$container = null;
}

// default black navbar
if (!isset($inverse)) {
	$inverse = null;
}

// apply responsive behavior to toggle navbar contents
if (!isset($responsive)) {
	$responsive = null;
}


// ----------------------- //
// ---[[   L O G O   ]]--- //
// ----------------------- //

$logo = BB::extend(array(
	'href' => '/',
	'title' => '',
	'show' => '',
	'image' => '',
	'alt' => '',
	'content' => array(),
	'linkOptions' => array(),
	'imageOptions' => array()
), BB::set($logo, 'show'));

// Logo as Link
if (!empty($logo['href'])) {
	
	if (!empty($logo['image'])) {
		$logo['content'] = BB::extend(array(
			'xtag' => 'image',
			'src' => $logo['image'],
			'alt' => $logo['alt']
		), BB::setDefaultAttrs($logo['imageOptions']));
	} else {
		$logo['content'] = $logo['show'];
	}
	
	$logo = BB::extend(array(
		'xtag' => 'link',
		'class' => 'brand',
		'href' => $logo['href'],
		'title' => $logo['title'],
		'content' => $logo['content']
	), BB::setDefaultAttrsId($logo['linkOptions']));

// Logo as Simple Text
} else {
	$logo = $logo['show'];
}








// --------------------------------- //
// ---[[   M A I N   M E N U   ]]--- //
// --------------------------------- //

$menu = null;






// ----------------------------------------- //
// ---[[   B U I L D   C O N T E N T   ]]--- //
// ----------------------------------------- //

// content is empty, build it with known pieces
if (empty($content)) {
	$content = array(
		$menu
	);

// content is given, fill it with known pieces
} else {
	if (!is_array($content) || !BB::isVector($content)) {
		$content = array($content);
	}
	if (!empty($menu)) {
		array_unshift($content, $menu);
	}
}





// ----------------------------------------------- //
// ---[[   N A V B A R   S T R U C T U R E   ]]--- //
// ----------------------------------------------- //

// apply responsive wrappers
if ($responsive) {
	$content = array(
		'<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></a>',
		$logo,
		array(
			'class' => 'nav-collapse collapse',
			'content' => $content
		)
	);
} else {
	array_unshift($content, $logo);
}

// container wrapper
if ($container) {
	if (is_bool($container)) $container = array();
	
	// intercept "fluid" request for the navbar
	if ($container === 'fluid') {
		$container = array('fluid' => true);
	} else {
		$container = BB::setDefaultAttrsId($container);
	}
	
	// overrides default "row" child for container!
	$container = BB::extend(array('defaults' => array()), $container);
	#$container['defaults']['$__xtag'] = null; // -tbr?20130307
	
	// creates a container with plain text contents.
	if (is_array($content)) $content = $this->Html->tag($content);
	$content = $this->Twb->container($content, $container);
}


// standard navbar config
$navbar = BB::extend(array(
	'class' => 'navbar',
	'content' => BB::extend(array(
		'class' => 'navbar-inner',
		'content' => $content
	), BB::setDefaultAttrs($innerOptions))
), BB::setDefaultAttrsId($outerOptions));

// inverse option
if ($inverse) {
	$navbar['class'] .= ' navbar-inverse';
}

// fixed option
if ($fixed !== false) {
	if (!is_string($fixed)) $fixed = 'top';
	$navbar['class'] .= ' navbar-fixed-' . $fixed;
}

echo $this->Html->tag($navbar);
