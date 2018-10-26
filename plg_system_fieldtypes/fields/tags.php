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

use Joomla\CMS\Form\FormField;
use Joomla\CMS\Factory;
use Joomla\Utilities\ArrayHelper;

class JFormFieldTags extends FormField
{
	/**
	 * The form field type.
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 */
	protected $type = 'tags';

	/**
	 * Name of the layout being used to render the field
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 */
	protected $layout = 'plugins.system.fieldtypes.tags';

	/**
	 * Name of the sublayout being used to render the field
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 */
	protected $sublayout = false;

	/**
	 * Include tags id
	 *
	 * @var array
	 *
	 * @since 1.0.0
	 */
	protected $includes = array();

	/**
	 * Tags array
	 *
	 * @var array
	 *
	 * @since 1.0.0
	 */
	protected $_tags = null;

	/**
	 * Tags array by parent_id as key
	 *
	 * @var array
	 *
	 * @since 1.0.0
	 */
	protected $_children = null;

	/**
	 * Method to attach a JForm object to the field.
	 *
	 * @param   SimpleXMLElement $element   The SimpleXMLElement object representing the `<field>` tag for the form field object.
	 * @param   mixed            $value     The form field value to validate.
	 * @param   string           $group     The field name group control value. This acts as an array container for the field.
	 *                                      For example if the field has name="foo" and the group value is set to "bar" then the
	 *                                      full field name would end up being "bar[foo]".
	 *
	 * @return  boolean  True on success.
	 *
	 * @see     FormField::setup()
	 *
	 * @since 1.0.0
	 */
	public function setup(SimpleXMLElement $element, $value, $group = null)
	{
		// Set multiple if checkboxes sublayout
		if ($element['sublayout'] == 'checkboxes')
		{
			$element['multiple'] = true;
		}

		if ($return = parent::setup($element, $value, $group))
		{
			$this->sublayout = (!empty($this->element['sublayout'])) ? (string) $this->element['sublayout'] : 'select';
			$this->includes  = (!empty($this->element['includes'])) ?
				explode(',', (string) $this->element['includes']) : array();
		}

		// Set sublayout
		if ($this->sublayout)
		{
			$this->layout = $this->layout . '.' . $this->sublayout;
		}

		if (!is_array($this->value))
		{
			$this->value = (array) $this->value;
		}

		return $return;
	}

	/**
	 * Method to get the data to be passed to the layout for rendering.
	 *
	 * @return  array
	 *
	 * @since 1.0.0
	 */
	protected function getLayoutData()
	{
		$data             = parent::getLayoutData();
		$data['tags']     = $this->getTags();
		$data['children'] = $this->getChildren();

		return $data;
	}

	/**
	 * Method to get tags array
	 *
	 * @return  array
	 *
	 * @since 1.0.0
	 */
	protected function getTags()
	{
		if (!is_array($this->_tags))
		{

			try
			{
				$app       = Factory::getApplication();
				$user      = Factory::getUser();
				$published = ($app->isSite() && !$user->authorise('core.manage', 'com_tags')) ? 1 : array(0, 1);
				$access    = ($app->isSite() && !$user->authorise('core.admin')) ? $user->getAuthorisedViewLevels() : false;

				// Get tags form db
				$db    = Factory::getDbo();
				$query = $db->getQuery(true)
					->select(array('id', 'parent_id', 'lft', 'level', 'title', 'published', 'access'))
					->from('#__tags')
					->where($db->quoteName('alias') . ' <> ' . $db->quote('root'))
					->order($db->escape('lft') . ' ' . $db->escape('asc'));

				// Filter by published state
				if (is_numeric($published))
				{
					$query->where('published = ' . (int) $published);
				}
				elseif (is_array($published))
				{
					$published = ArrayHelper::toInteger($published);
					$published = implode(',', $published);

					$query->where('published IN (' . $published . ')');
				}

				$db->setQuery($query);
				$rows = $db->loadObjectList('id');

				$tags = array();
				foreach ($rows as $row)
				{
					$row->disable = ($access && !in_array($row->access, $access));
					$row->active  = (in_array($row->id, $this->value));

					// Add tag to array in empty includes
					if (empty($this->includes))
					{
						$tags[$row->id] = $row;
						continue;
					}

					// Check includes
					if (in_array($row->id, $this->includes))
					{
						// Add parent to tags in parent_id not in includes
						$parent_id = $row->parent_id;
						while ($parent_id > 1 && !in_array($parent_id, $this->includes) && !empty($rows[$parent_id]))
						{
							$parent            = $rows[$parent_id];
							$parent->disable   = true;
							$parent->active    = (in_array($parent->id, $this->value));
							$tags[$parent->id] = $parent;
							$parent_id         = $parent->parent_id;
						}

						$tags[$row->id] = $row;
					}
				}

				$this->_tags = ArrayHelper::sortObjects($tags, 'lft');
			}
			catch (Exception $e)
			{
				throw new Exception(Text::_($e->getMessage()), $e->getCode());
			}
		}

		return $this->_tags;
	}

	/**
	 * Method to get tags array by parent_id as key
	 *
	 * @return  array
	 *
	 * @since 1.0.0
	 */
	protected function getChildren()
	{
		if (!is_array($this->_children))
		{
			$children = array();
			foreach ($this->getTags() as $tag)
			{
				if (!isset($children[$tag->parent_id]))
				{
					$children[$tag->parent_id] = array();
				}

				$children[$tag->parent_id][] = $tag;
			}

			$this->_children = $children;
		}

		return $this->_children;
	}
}