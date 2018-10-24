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

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Factory;
use Joomla\CMS\Response\JsonResponse;

JLoader::register('FieldTypesHelperImages', JPATH_PLUGINS . '/system/fieldtypes/helpers/images.php');

class FieldTypesControllerImages extends BaseController
{
	/**
	 * Method to get single image
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function getImage()
	{
		$app      = Factory::getApplication();
		$filename = $app->input->get('filename', '', 'raw');
		$folder   = $app->input->get('folder', '', 'raw');
		$noimage  = $app->input->get('noimage', 'media/plg_system_fieldtypes/images/noimage.jpg', 'raw');
		$helper   = new FieldTypesHelperImages();

		return $this->setResponse($helper->getImage($filename, $folder, $noimage));
	}

	/**
	 * Method to single image
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function deleteImage()
	{
		$app      = Factory::getApplication();
		$filename = $app->input->get('filename', '', 'raw');
		$folder   = $app->input->get('folder', '', 'raw');
		$noimage  = $app->input->get('noimage', 'media/plg_system_fieldtypes/images/noimage.jpg', 'raw');
		$helper   = new FieldTypesHelperImages();

		return $this->setResponse($helper->deleteImage($filename, $folder, $noimage));
	}

	/**
	 * Method to upload simple image
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function uploadImage()
	{
		$app      = Factory::getApplication();
		$filename = $app->input->get('filename', '', 'raw');
		$folder   = $app->input->get('folder', '', 'raw');
		$files    = $app->input->files->get('files', array(), 'array');
		$noimage  = $app->input->get('noimage', 'media/plg_system_fieldtypes/images/noimage.jpg', 'raw');
		$helper   = new FieldTypesHelperImages();

		return $this->setResponse($helper->uploadImage($filename, $folder, $files, $noimage));
	}

	/**
	 * Method to set response
	 *
	 * @param $response
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	protected function setResponse($response)
	{
		echo new JsonResponse($response, '', !($response));

		Factory::getApplication()->close();

		return true;
	}
}