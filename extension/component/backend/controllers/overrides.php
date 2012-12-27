<?php
/**
 * @package 	J4Schema
 * @category	J4SchemaPro
 * @copyright 	Copyright (c)2011 Davide Tampellini
 * @license 	GNU General Public License version 3, or later
 * @since 		1.0
 */
// Protect from unauthorized access
defined('_JEXEC') or die();

class J4schemaControllerOverrides extends FOFController
{
	public function copyOverrides()
	{
		jimport('joomla.filesystem.folder');
		require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/html.php';

		if(version_compare(JVERSION, '1.6.0', 'ge')) $version = '2.5';
		else										 $version = '1.5';

		$keys   = JFolder::folders(JPATH_COMPONENT_ADMINISTRATOR.'/overrides/'.$version, '.', false, false);
		$values = JFolder::folders(JPATH_COMPONENT_ADMINISTRATOR.'/overrides/'.$version, '.', false, true);

		$j4s = array_combine($keys, $values);

		$tmpl_path = JPATH_ROOT.'/templates/'.J4schemaHelperHtml::getFrontendTemplate().'/html/';
		$folders = FOFInput::getArray('folders', array(), $this->input);

		//let's copy the custom overrides
		foreach($j4s as $folder => $path)
		{
			if(!in_array($folder, $folders)) continue;

			// special case for Virtuemart under Joomla 1.5
			if($folder == 'com_virtuemart' && version_compare(JVERSION, '1.6', 'l'))
			{
				// Virtuemart not installed, continue
				if(!JFolder::exists(JPATH_ROOT.'/components/com_virtuemart')) continue;
				$orig_path = $tmpl_path;
				$tmpl_path = JPATH_ROOT.'/components/com_virtuemart/themes/';
				$folder    = 'j4schema';
			}
			// K2 has no template overrides, but his own template system
			elseif($folder == 'com_k2')
			{
				$orig_path = $tmpl_path;
				$tmpl_path = JPATH_ROOT.'/components/com_k2/templates/';
				$folder    = 'j4schema';
			}

			//uh oh, override folder alredy exists, let's backup it
			if(JFolder::exists($tmpl_path.$folder))
			{
				//backup folder already exists, delete it (keep only the last override)
				if(JFolder::exists($tmpl_path.$folder.'_bck'))
				{
					if(!JFolder::delete($tmpl_path.$folder.'_bck'))
					{
						$msg = JText::_('COM_J4SCHEMA_ERR_BCK_DELETE_OVERRIDES');
						break;
					}
				}

				//copy current override folder as backup
				if(!JFolder::copy($tmpl_path.$folder, $tmpl_path.$folder.'_bck'))
				{
					$msg = JText::_('COM_J4SCHEMA_ERR_BCK_COPY_OVERRIDE');
					break;
				}

				if(!JFolder::delete($tmpl_path.$folder))
				{
					$msg = JText::_('COM_J4SCHEMA_ERR_DELETE_FOLDER');
					break;
				}
			}

			if(!JFolder::copy($path, $tmpl_path.$folder))
			{
				$msg = JText::_('COM_J4SCHEMA_ERR_COPY_OVERRIDE');
				break;
			}

			if($orig_path)
			{
				$tmpl_path = $orig_path;
				unset($orig_path);
			}
		}

		if(!$msg)
		{
			$msg  = JText::_('COM_J4SCHEMA_OVERRIDE_COPY_OK');
			if(JFolder::exists(JPATH_ROOT.'/components/com_virtuemart') && version_compare(JVERSION, '1.6', 'l')){
				$msg .= '. '.JText::_('COM_J4SCHEMA_OVERRIDE_VIRTUEMART_15');
			}
		}
		else	  $type = 'error';

		$this->setRedirect('index.php?option=com_j4schema&view=overrides', $msg, $type);
	}
}