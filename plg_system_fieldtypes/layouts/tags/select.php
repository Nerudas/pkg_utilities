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

use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;

extract($displayData);

/**
 * Layout variables
 * -----------------
 * @var  boolean $autofocus Is autofocus enabled?
 * @var  string  $class     Classes for the input.
 * @var  boolean $disabled  Is this field disabled?
 * @var  string  $id        DOM id of the field.
 * @var  boolean $multiple  Does this field support multiple values?
 * @var  string  $name      Name of the input field.
 * @var  string  $onchange  Onchange attribute for the field.
 * @var  boolean $required  Is this field required?
 * @var  array   $tags      Tags array
 * @var  array   $children  Tags array by parent_id as key
 */

// Prepare field attributes
$attributes = '';
$attributes .= !empty($class) ? ' class="' . $class . '"' : '';
$attributes .= $multiple ? ' multiple' : '';
$attributes .= $required ? ' required aria-required="true"' : '';
$attributes .= $autofocus ? ' autofocus' : '';
if ((string) $readonly == '1' || (string) $readonly == 'true' || (string) $disabled == '1' || (string) $disabled == 'true')
{
	$attributes .= ' disabled="disabled"';
}
$attributes .= $onchange ? ' onchange="' . $onchange . '"' : '';

// Prepare field params
$params = array(
	'option.key'  => 'value',
	'option.text' => 'text',
	'list.select' => $value,
	'list.attr'   => trim($attributes)
);

// Prepare field options
$options = array();

if (!$multiple)
{
	$option           = new stdClass();
	$option->value    = '';
	$option->text     = Text::_('JGLOBAL_FIELD_TAGS_SELECT');
	$option->selected = (empty($value));

	$options[] = $option;
}

foreach ($tags as $tag)
{
	$option           = new stdClass();
	$option->text     = str_repeat('- ', ($tag->level - 1)) . $tag->title;
	$option->value    = $tag->id;
	$option->disable  = $tag->disable;
	$option->selected = $tag->active;

	$options[] = $option;
}

// Render field
echo HTMLHelper::_('select.genericlist', $options, $name, $params);