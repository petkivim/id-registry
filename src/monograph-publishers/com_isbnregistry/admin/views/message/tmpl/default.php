<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_messages
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
JHTML::_('behavior.modal');
// Load styles
$document = JFactory::getDocument();
$document->addStyleSheet("components/com_isbnregistry/css/message.css");
$link_begin = '<a href="index.php?option=com_isbnregistry&layout=edit&view=';
$link_end = '&tmpl=component" class="modal" rel="{size: {x: 1200, y: 600}, handler:\'iframe\'}">' . JText::_('COM_ISBNREGISTRY_MESSAGE_FIELD_SHOW_LABEL') . '</a>';
?>
<form action="<?php echo JRoute::_('index.php?option=com_isbnregistry&view=messages'); ?>" method="post" name="adminForm" id="adminForm" class="form-horizontal">
    <fieldset>
        <div class="control-group">
            <div class="control-label">
                <?php echo JText::_('COM_ISBNREGISTRY_MESSAGE_FIELD_RECIPIENT_LABEL'); ?>
            </div>
            <div class="controls">
                <?php echo $this->item->recipient; ?>
            </div>
        </div>
        <div class="control-group">
            <div class="control-label">
                <?php echo JText::_('COM_ISBNREGISTRY_MESSAGE_FIELD_PUBLISHER_LABEL'); ?>
            </div>
            <div class="controls">
                <?
                if ($this->item->publisher_id != 0) {
                    echo $link_begin . 'publisher&id=' . $this->item->publisher_id . $link_end;
                } else {
                    echo JText::_('COM_ISBNREGISTRY_MESSAGE_FIELD_UNDEFINED_LABEL');
                }
                ?>
            </div>
        </div>	
        <div class="control-group">
            <div class="control-label">
                <?php echo JText::_('COM_ISBNREGISTRY_MESSAGE_FIELD_PUBLICATION_LABEL'); ?>
            </div>
            <div class="controls">
                <?php
                if ($this->item->publication_id != 0) {
                    echo $link_begin . 'publication&id=' . $this->item->publication_id . $link_end;
                } else {
                    echo JText::_('COM_ISBNREGISTRY_MESSAGE_FIELD_UNDEFINED_LABEL');
                }
                ?>
            </div>
        </div>		
        <div class="control-group">
            <div class="control-label">
                <?php echo JText::_('COM_ISBNREGISTRY_MESSAGE_FIELD_SENT_LABEL'); ?>
            </div>
            <div class="controls">
                <?php echo JHtml::date($this->item->sent, 'd.m.Y H:m:s') . ' (' . $this->item->sent_by . ')'; ?> 
            </div>
        </div>
        <div class="control-group">
            <div class="control-label">
                <?php echo JText::_('COM_ISBNREGISTRY_MESSAGE_FIELD_SUBJECT_LABEL'); ?>
            </div>
            <div class="controls">
                <?php echo $this->item->subject; ?>
            </div>
        </div>
        <?php echo $this->form->renderField('message'); ?>
        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>
    </fieldset>
</form>
