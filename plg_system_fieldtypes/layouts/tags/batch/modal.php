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

use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Text;

$task = $displayData['task'];

$form = new Form('batch', array('control' => 'batch'));
$form->load('<form>	<field name="tag" type="tags" class="span12" sublayout="checkboxes"/></form>');
?>
<div id="batchTagsModal" tabindex="-1" class="modal hide fade">
	<div class="modal-header">
		<button type="button" class="close novalidate" data-dismiss="modal">×</button>
		<h3><?php echo Text::_('JGLOBAL_FIELD_TAGS_BATCH'); ?></h3>
	</div>
	<div class="modal-body" style=" overflow-y: scroll !important;">
		<?php echo $form->getInput('tag'); ?>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn"
				onclick="document.getElementById('batch-tag').value=''"
				data-dismiss="modal">
			<?php echo Text::_('JCANCEL'); ?>
		</button>
		<button type="submit" class="btn btn-success" onclick="Joomla.submitbutton('<?php echo $task; ?>');">
			<?php echo Text::_('JGLOBAL_BATCH_PROCESS'); ?>
		</button>
	</div>
</div>
