<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
JHTML::_('behavior.modal');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
?>

<form action="<?php echo JRoute::_('index.php?option=com_issnregistry&task=statistic.getStatistics'); ?>"
      method="post" name="adminForm" id="adminForm" class="form-validate">
    <div class="form-horizontal">
        <legend><?php echo JText::_('COM_ISSNREGISTRY_STATISTIC_TITLE'); ?></legend>
        <?php echo $this->form->renderField('type'); ?>
        <?php echo $this->form->renderField('begin'); ?>
        <?php echo $this->form->renderField('end'); ?>
        <div class="control-group">
            <div class="control-label">
            </div>
            <div class="controls">
                <input type="submit" value="<?php echo JText::_('COM_ISSNREGISTRY_STATISTICS_BUTTON'); ?>" />
            </div>
        </div>
    </div>
    <?php echo JHtml::_('form.token'); ?>
</form>
