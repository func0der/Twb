<?php
/**
 * Default Layout
 * Twitter Bootstrap - UI Plugin
 */
?>

<!DOCTYPE HTML>
<html lang="en-US">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		
		
		<?php
		/*
		<link rel="shortcut icon" type="image/x-icon" href="<?=$this->Html->assetUrl('Ui.images/favicon.png')?>" />
		*/
		
		/**
		 * SEO Meta Tags
		 */
		echo $this->Html->tag('title', BB::read('Twb.meta.title'));
		if (BB::check('Twb.meta.description')) {
			echo $this->Html->meta('description', BB::read('Twb.meta.description', BB::read('Twb.meta.title')));
		}
		if (BB::check('Twb.meta.keywords')) {
			echo $this->Html->meta('keywords', BB::read('Twb.meta.keywords'));
		}
		
		/**
		 * Favicon
		 */
		echo $this->Html->meta('favicon.ico', Router::url(BB::read('Twb.favicon', '/favicon.ico')), array('type' => 'icon'));
		
		/**
		 * Canonical Url
		 * best served as full static url!
		 */
		if (BB::check('Twb.canonical')) {
			echo $this->Html->meta(null, BB::read('Twb.canonical'), array('rel' => 'canonical', 'type' => null));
		}
		
		/**
		 * LayoutCSS
		 */
		$this->Html->css(array(
			'Twb.compiled/bootstrap.min',
			'Twb.compiled/bootstrap-responsive.min',
			'Twb.twb-core'
		), false);
		
		/**
		 * LayoutJS
		 */
		$this->Html->script(array(
			'Twb.compiled/jquery-1.9.0',
			'Twb.compiled/bootstrap',
			'Twb.twb-core'
		), false);
		
		echo $this->fetch('css');
		
		/**
		 * Analytics
		 */
		// custom element
		if ($this->elementExists('analytics')) {
			echo $this->element('analytics');
		// Twb element with GA ID
		} elseif (BB::check('Twb.analytics')) {
			// to implement standard traking code by GA-ID
			$this->append('script', $this->element('Twb.analytics', array(
				'ga' => BB::read('Twb.analytics')
			)));
		}
		?>
		
	</head>
	<body>
		<?php
		// Page content
		echo $this->fetch('content');
		
		// Page Javascript
		echo $this->fetch('script');
		?>
	</body>
</html>