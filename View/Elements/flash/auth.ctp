<?php
/**
 * Authentication Error Flash Message
 * 
 */
echo $this->element('Twb.flash/_structure',array(
	'type' 		=> 'warning',
	'title'		=> !empty($title)	? $title	: '',
	'message' 	=> !empty($message)	? $message	: ''
));