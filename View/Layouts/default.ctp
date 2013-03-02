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
		echo $this->Html->meta('description', BB::read('Twb.meta.description', BB::read('Twb.meta.title')));
		echo $this->Html->meta('keywords', BB::read('Twb.meta.keywords'));
		
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
			'Twb.compiled/bootstrap-responsive.min'
		), false);
		
		/**
		 * LayoutJS
		 */
		$this->Html->script(array(
			'Twb.compiled/jquery-1.9.0.min',
			'Twb.compiled/bootstrap.min'
		), false);
		
		echo $this->fetch('css');
		
		/**
		 * Analitycs
		 */
		if ($this->elementExists('google_analitycs')) {
			echo $this->element('google_analitycs');
		} else {
			echo BB::read('Twb.analytics');
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