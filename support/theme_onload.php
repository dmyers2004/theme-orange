<?php
/**
* Orange Framework Extension
*
* This content is released under the MIT License (MIT)
*
* @package	CodeIgniter / Orange
* @author	Don Myers
* @license	http://opensource.org/licenses/MIT	MIT License
* @link	https://github.com/dmyers2004
*/
function theme_onload(&$page) {
	/* https://cdnjs.com/ */
	$page
		->library(['theme','bootstrap_menu','Plugin_flash_msg','Plugin_select3','Plugin_o_dialog','Plugin_o_validate_form'])
		->title('Orange Framework')
		->css('//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css',25)
		->css('//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css',50)
		->css('//fonts.googleapis.com/css?family=Roboto:400,700,700italic,400italic',50)
		->css('/themes/orange/assets/css/orange.min.css',100)
		->js('//cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js',1)
		->js('//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js',25)
		->js('//cdnjs.cloudflare.com/ajax/libs/jStorage/0.4.12/jstorage.min.js',50)
		->js('/themes/orange/assets/js/orange.min.js',100);
}