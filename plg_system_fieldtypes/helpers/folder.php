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
use Joomla\CMS\Filesystem\File;

class FieldTypesHelperFolder
{
	/**
	 * Create Temporary image folder
	 *
	 * @param int    $pk   Item id
	 * @param string $root Simple path to folder (etc images/others)
	 *
	 * @return string|bool
	 *
	 * @since 1.0.0
	 */
	public function getItemFolder($pk = null, $root = '')
	{
		if (empty($root))
		{
			return $root;
		}

		return (!empty($pk)) ? $this->checkFolder($root . '/' . $pk) : $this->createTemporaryFolder($root);
	}

	/**
	 * Delete item folder
	 *
	 * @param int    $pk   Item id
	 * @param string $root Simple path to folder (etc images/others)
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function deleteItemFolder($pk = null, $root = '')
	{
		$folder = JPATH_ROOT . '/' . $root . '/' . $pk;

		if (empty($pk) || empty($root) || !Folder::exists($folder))
		{
			return false;
		}

		return Folder::delete($folder);
	}

	/**
	 * Check if folder exist if not exist create
	 *
	 * @param string $path Simple path to folder (etc images/others)
	 *
	 * @return string
	 *
	 * @since 1.0.0
	 */
	public function checkFolder($path = '')
	{
		if (!empty($path))
		{
			$folder = JPATH_ROOT . '/' . $path;
			if (!Folder::exists($folder))
			{
				Folder::create($folder);
				File::write($folder . '/index.html', '<!DOCTYPE html><title></title>');
			}
		}

		return $path;
	}

	/**
	 * Create temporary folder
	 *
	 * @param string $root Simple path to folder (etc images/others)
	 *
	 * @return string
	 *
	 * @since 1.0.0
	 */
	public function createTemporaryFolder($root = '')
	{
		if (empty($root))
		{
			return $root;
		}

		$result = $root . '/tmp_' . uniqid();
		$folder = JPATH_ROOT . '/' . $result;
		while (Folder::exists($folder))
		{
			$result = $root . '/tmp_' . uniqid();
			$folder = JPATH_ROOT . '/' . $root;
		}

		Folder::create($folder);
		File::write($folder . '/index.html', '<!DOCTYPE html><title></title>');

		return $result;
	}

	/**
	 * Move temporary folder
	 *
	 * @param string $temporary Temporary Folder
	 * @param int    $pk        Item id
	 * @param string $root      Simple path to folder (etc images/others)
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function moveTemporaryFolder($temporary = null, $pk = null, $root = null)
	{
		if (empty($temporary) || empty($root) || empty($pk))
		{
			return false;
		}

		$old = JPATH_ROOT . '/' . $temporary;
		$new = JPATH_ROOT . '/' . $root . '/' . $pk;

		if (!Folder::exists($old))
		{
			return false;
		}

		return Folder::move($old, $new);
	}
}