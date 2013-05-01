<?php
/**
 * @package 	J4Schema
 * @copyright 	Copyright (c)2011 Davide Tampellini
 * @license 	GNU General Public License version 3, or later
 * @since 		1.0
 */

defined('_JEXEC') or die();
JHTML::_('behavior.keepalive');
//ini_set('display_errors', 1);

if(!file_exists(JPATH_ROOT.'/libraries/fof/include.php'))
{
	echo 'FrameworkOnFramework Library not found. <br/>';
	echo 'Please re-install the package and contact us if you still have this problem';
	return;
}
include_once JPATH_ROOT.'/libraries/fof/include.php' ;

FOFTemplateUtils::addCSS('com_j4schema/css/main.css');
FOFTemplateUtils::addCSS('com_j4schema/css/classes.css');
FOFTemplateUtils::addCSS('com_j4schema/css/tree.css');

if(!version_compare(JVERSION, '1.6.0', 'ge')){
	FOFTemplateUtils::addCSS('com_j4schema/css/compat1.5.css');
}

if(file_exists(JPATH_ROOT.'/media/com_j4schema/js/pro.js'))	define('J4SCHEMA_PRO', 1);
else														define('J4SCHEMA_PRO', 0);

// Dispatch
FOFDispatcher::getAnInstance('com_j4schema')->dispatch();