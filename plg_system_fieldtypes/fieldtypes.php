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

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Plugin\CMSPlugin;

class PlgSystemFieldTypes extends CMSPlugin
{
	/**
	 * Affects constructor behavior. If true, language files will be loaded automatically.
	 *
	 * @var boolean
	 *
	 * @since 1.0.0
	 */
	protected $autoloadLanguage = true;

	/**
	 * Adds additional fields & rules to From
	 *
	 * @param   Joomla\CMS\Form\Form $form The form to be altered.
	 * @param   mixed                $data The associated data for the form.
	 *
	 * @return  boolean
	 *
	 * @since 1.0.0
	 */
	public function onContentPrepareForm($form, $data)
	{
		if ($form)
		{
			$form->addFieldPath(__DIR__ . '/fields');
		}

		return true;
	}

	/**
	 * Run controller tasks
	 *
	 * @since 1.0.0
	 */
	public function onAjaxFieldTypes()
	{
		$app  = Factory::getApplication();
		$task = $app->input->get('task', '', 'raw');
		$app->input->set('format', '');

		if (empty($task))
		{
			throw new Exception(Text::_('PLG_SYSTEM_FIELDTYPES_ERROR_TASK_NOT_FOUND'), 404);
		}

		$task = explode('.', $task);
		$task = (!empty($task[1])) ? $task[1] : $task[0];

		$controller = BaseController::getInstance('FieldTypes', array('base_path' => __DIR__));

		if (!in_array(strtolower($task), $controller->getTasks()))
		{
			throw new Exception(Text::_('PLG_SYSTEM_FIELDTYPES_ERROR_TASK_NOT_FOUND'), 404);
		}

		$controller->execute($task);
	}
}