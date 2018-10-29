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

HTMLHelper::_('behavior.core');

$title = $displayData['title'];
Text::script('JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST');
$message = "alert(Joomla.JText._('JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST'));";
?>
<button onclick="if (document.adminForm.boxchecked.value > 0){jQuery('#batchTagsModal').modal('show'); return true;} else {<?php echo $message; ?>}"
		data-toggle="modal" class="btn btn-small">
	<span class="icon-tags" aria-hidden="true"></span><?php echo Text::_('JGLOBAL_FIELD_TAGS_BATCH_TOOLBAR'); ?>
</button>