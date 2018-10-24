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

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

extract($displayData);

HTMLHelper::_('jquery.framework');
HTMLHelper::_('stylesheet', 'media/plg_system_fieldtypes/css/image-line.min.css', array('version' => 'auto'));
HTMLHelper::_('script', 'media/plg_system_fieldtypes/js/image-line.min.js', array('version' => 'auto'));

?>
<div id="<?php echo $id; ?>" class="<?php echo $class; ?>" data-input-image-line="<?php echo $id; ?>">
	<div class="form">
		<div class="action preview">
			<i class="icon-eye"></i>
			<div class="preview-modal">
				<img src="/<?php echo $value; ?>"/>
			</div>
		</div>
		<input type="text" name="<?php echo $name; ?>" id="<?php echo $id; ?>_value" value="<?php echo $value; ?>"
		       class="value" readonly="readonly">
		<input id="<?php echo $id; ?>_field" class="file" type="file" accept="image/*"/>
		<label for="<?php echo $id; ?>_field" class="action select btn hasTooltip"
		       title="<?php echo Text::_('JLIB_FORM_BUTTON_SELECT'); ?>">
			<?php echo Text::_('JLIB_FORM_BUTTON_SELECT'); ?>
		</label>
		<a class="action remove btn icon-remove"></a>
	</div>
</div>