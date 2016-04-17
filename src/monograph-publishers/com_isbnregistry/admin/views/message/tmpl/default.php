<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_messages
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
JHtml::_('behavior.formvalidator');
JHTML::_('behavior.modal');

// Load styles
$document = JFactory::getDocument();
$document->addScript('components/com_isbnregistry/scripts/message_validators.js');
$document->addStyleSheet("components/com_isbnregistry/css/message.css");

// This fix is needed for JToolBar
JFactory::getDocument()->addScriptDeclaration('
    Joomla.submitbutton = function(task)
    {
        if (task == "message.cancel" || document.formvalidator.isValid(document.getElementById("adminForm")))
        {
            Joomla.submitform(task, document.getElementById("adminForm"));
        }
    };
');

// Create link
$link_begin = '<a href="index.php?option=com_isbnregistry&layout=edit&view=';
$link_end = '&tmpl=component" class="modal" rel="{size: {x: 1200, y: 600}, handler:\'iframe\'}">' . JText::_('COM_ISBNREGISTRY_MESSAGE_FIELD_SHOW_LABEL') . '</a>';
?>
<form action="<?php echo JRoute::_('index.php?option=com_isbnregistry&view=message&id' . (int) $this->item->id); ?>" 
      method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">
    <fieldset>
        <?php echo $this->form->renderField('recipient'); ?>
        <div class="control-group">
            <div class="control-label">
                <?php echo JText::_('COM_ISBNREGISTRY_MESSAGE_FIELD_PUBLISHER_LABEL'); ?>
            </div>
            <div class="controls">
                <?php
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
                <?php echo JHtml::date($this->item->sent, 'd.m.Y H:i:s') . ' (' . $this->item->sent_by . ')'; ?> 
            </div>
        </div>
        <?php echo $this->form->renderField('subject'); ?> 
        <div class="control-group">
            <div class="control-label">
                <?php echo JText::_('COM_ISBNREGISTRY_MESSAGE_FIELD_ATTACHMENT_LABEL'); ?>
            </div>
            <div class="controls">
                <?php
                if ($this->item->has_attachment) {
                    $link = '<a href="' . JRoute::_('components/com_isbnregistry/email/' . $this->item->attachment_name);
                    $link .= '" class="modal" rel="{size: {x: 400, y: 600}, handler:\'iframe\'}">' . JText::_('COM_ISBNREGISTRY_MESSAGE_FIELD_SHOW_LABEL') . '</a>';
                    echo $link;
                } else {
                    echo '-';
                }
                ?>
            </div>
        </div>
        <?php echo $this->form->renderField('message'); ?>
        <?php echo $this->form->renderField('lang_code'); ?>
        <?php echo $this->form->renderField('message_template_id'); ?>
        <?php echo $this->form->renderField('message_type_id'); ?>
        <?php echo $this->form->renderField('publisher_id'); ?>
        <?php echo $this->form->renderField('publication_id'); ?>
        <?php echo $this->form->renderField('batch_id'); ?>
        <input type="hidden" name="task" value="message.resend" />
        <?php echo JHtml::_('form.token'); ?>
    </fieldset>
</form>
