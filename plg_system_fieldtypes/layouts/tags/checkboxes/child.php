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

use Joomla\CMS\Layout\LayoutHelper;

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
 * @var  object  $tag       Current tag object
 */

?>
<li class="item">
	<div class="tag">
		<label for="<?php echo $id . '_' . $tag->id; ?>" class="checkbox">
			<input type="checkbox" name="<?php echo $name; ?>" id="<?php echo $id . '_' . $tag->id; ?>"
				   value="<?php echo $tag->id; ?>"
				<?php echo ($tag->active) ? ' checked' : '';
				echo ($tag->disable) ? ' disabled' : ''; ?>>
			<?php echo $tag->title; ?>
		</label>
	</div>
	<?php if (!empty($children[$tag->id])): ?>
		<ul class="level-<?php echo($tag->level + 1); ?>">
			<?php foreach ($children[$tag->id] as $child)
			{
				$displayData['tag'] = $child;
				echo LayoutHelper::render('plugins.system.fieldtypes.tags.checkboxes.child', $displayData);
			} ?>
		</ul>
	<?php endif; ?>
</li>
