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

use Joomla\CMS\Filesystem\File;
use Joomla\Filesystem\Folder;

JLoader::register('FieldTypesHelperFolder', JPATH_PLUGINS . '/system/fieldtypes/helpers/folder.php');

class FieldTypesHelperImages
{
	/**
	 * Images mime types
	 *
	 * @var array
	 *
	 * @since 1.0.0
	 */
	public $mime_types = array('image/png', 'image/jpeg', 'image/gif', 'image/bmp', 'image/svg', 'image/svg+xml');

	/**
	 * Get single image
	 *
	 * @param string $filename Filename
	 * @param string $folder   Simple path to file (etc images/others)
	 * @param string $noimage  Simple path to file (etc images/others)
	 * @param bool   $noCache  Disable image cache
	 *
	 * @return bool|string
	 *
	 * @since 1.0.0
	 */
	public function getImage($filename = '', $folder = '', $noimage = null, $noCache = true)
	{
		if (empty($filename) || empty($folder))
		{
			return $noimage;
		}

		$this->checkFolder($folder);
		$path = JPATH_ROOT . '/' . $folder;

		$files = Folder::files($path, $filename, false, false);
		if (!empty($files))
		{
			foreach ($files as $file)
			{
				if ($this->checkImage($path . '/' . $file))
				{
					$src = $folder . '/' . $file;
					if ($noCache)
					{
						$src .= '?v=' . rand();
					}

					return $src;
				}
			}
		}

		return $noimage;
	}

	/**
	 * Delete single image
	 *
	 * @param string $filename Filename
	 * @param string $folder   Simple path to file (etc images/others)
	 * @param string $noimage  Simple path to file (etc images/others)
	 *
	 * @return bool|string
	 *
	 * @since 1.0.0
	 */
	public function deleteImage($filename = '', $folder = '', $noimage = null)
	{
		if (empty($filename) || empty($folder))
		{
			return $this->getImage($filename, $folder, $noimage);
		}

		$path  = JPATH_ROOT . '/' . $folder;
		$files = Folder::files($path, $filename, false, false);
		if (!empty($files))
		{
			foreach ($files as $file)
			{
				if ($this->checkImage($path . '/' . $file))
				{
					File::delete($path . '/' . $file);
				}
			}
		}

		return $this->getImage($filename, $folder, $noimage);
	}

	/**
	 * Upload single image
	 *
	 * @param string $filename Filename
	 * @param string $folder   Simple path to file (etc images/others)
	 * @param array  $files    Files array
	 * @param string $noimage  Simple path to file (etc images/others)
	 *
	 * @return bool|string
	 *
	 * @since 1.0.0
	 */
	public function uploadImage($filename = '', $folder = '', $files = array(), $noimage = null)
	{
		if (empty($filename) || empty($folder))
		{
			return $this->getImage($filename, $folder, $noimage);
		}

		$file = array_shift($files);
		if (empty($file) || !$this->checkImage($file['tmp_name']))
		{
			return $this->getImage($filename, $folder, $noimage);
		}

		$this->checkFolder($folder);

		$this->deleteImage($filename, $folder, $noimage);

		$dest = JPATH_ROOT . '/' . $folder . '/' . $filename . '.' . File::getExt($file['name']);
		File::upload($file['tmp_name'], $dest, false, true);

		return $this->getImage($filename, $folder, $noimage);
	}

	/**
	 * Check if file is image
	 *
	 * @param string $image Full path to image
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function checkImage($image = '')
	{
		return in_array(@mime_content_type($image), $this->mime_types);
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
		$folderHelper = new FieldTypesHelperFolder();

		return $folderHelper->checkFolder($path);
	}
}