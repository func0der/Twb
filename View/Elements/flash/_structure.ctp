<?php
/**
 * AteUi - Notification Box Structure
 * 
 */
echo $this->Html->tag(array(
	'class' 	=> 'alert alert-'.$type,
	'twb-role'	=> 'sessionMsg',
	'content' 	=> array(
		array(
			'tag' 			=> 'button',
			'class' 		=> 'close',
			'data-dismiss' 	=> 'alert',
			'content' 		=> '×'
		),
		array(
			'tag' 			=> 'h4',
			'content' 		=> $title
		),
		$message
	)
));