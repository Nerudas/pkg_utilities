<?php
/**
 * @package    System - Field Types Plugin
 * @version    1.0.0
 * @author     Nerudas  - nerudas.ru
 * @copyright  Copyright (c) 2013 - 2018 Nerudas. All rights reserved.
 * @license    GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 * @link       https://nerudas.ru
 */

defined('_JEXEC') or die;

use Joomla\CMS\Filesystem\Folder;

class PlgSystemFieldTypesInstallerScript
{
	/**
	 * Runs right after any installation action is preformed on the component.
	 *
	 * @param  string $type      Type of PostFlight action. Possible values are:
	 *                           - * install
	 *                           - * update
	 *                           - * discover_install
	 * @param         $parent    Parent object calling object.
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	function postflight($type, $parent)
	{
		// Move layouts
		$this->moveLayouts();

		return true;
	}

	/**
	 * Method to move plugin layouts
	 *
	 * @since 1.0.0
	 */
	protected function moveLayouts()
	{
		$src  = JPATH_PLUGINS . '/system/fieldtypes/layouts';
		$dest = JPATH_ROOT . '/layouts/plugins/system';

		// System plugins layouts path check
		if (!Folder::exists($dest))
		{
			Folder::create($dest);
		}
		$dest .= '/fieldtypes';

		// Delete old layouts
		if (Folder::exists($dest))
		{
			Folder::delete($dest);
		}

		// Move layouts
		Folder::move($src, $dest);
	}

	/**
	 * Called on uninstallation
	 *
	 * @param   JAdapterInstance $adapter The object responsible for running this script
	 *
	 * @since 1.0.0
	 */
	public function uninstall(JAdapterInstance $adapter)
	{
		// Remove layouts
		Folder::delete(JPATH_ROOT . '/layouts/plugins/system/fieldtypes');
	}
}