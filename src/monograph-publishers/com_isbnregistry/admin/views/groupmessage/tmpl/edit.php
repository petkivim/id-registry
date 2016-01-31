<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 		Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.formvalidator');
JHtml::_('formbehavior.chosen');
JHTML::_('behavior.modal');

// Add scripts
$document = JFactory::getDocument();
$document->addScript('components/com_isbnregistry/scripts/groupmessage_validators.js');
$document->addScript("components/com_isbnregistry/scripts/groupmessage.js");
// Add css
$document->addStyleSheet("components/com_isbnregistry/css/groupmessage.css");

// This fix is needed for JToolBar
JFactory::getDocument()->addScriptDeclaration('
    Joomla.submitbutton = function(task)
    {
        if (task == "groupmessage.cancel" || document.formvalidator.isValid(document.getElementById("adminForm")))
        {
            Joomla.submitform(task, document.getElementById("adminForm"));
        }
    };
');
if ($this->item->id > 0) {
    $this->form->setFieldAttribute('message_type_id', 'readonly', 'true');
    $this->form->setFieldAttribute('isbn_categories', 'readonly', 'true');
    $this->form->setFieldAttribute('ismn_categories', 'readonly', 'true');
    $this->form->setFieldAttribute('has_quitted', 'disabled', 'true');
}
?>
<form action="<?php echo JRoute::_('index.php?option=com_isbnregistry&layout=edit&id=' . (int) $this->item->id); ?>"
      method="post" name="adminForm" id="adminForm" class="form-validate">
    <div class="form-horizontal">
        <div class="row-fluid form-horizontal-desktop">           
            <?php echo $this->form->renderField('message_type_id'); ?>
            <?php echo $this->form->renderField('isbn_categories'); ?>
            <?php if ($this->item->id > 0) : ?>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo JText::_('COM_ISBNREGISTRY_GROUP_MESSAGES_FIELD_ISBN_PUBLISHER_COUNT_LABEL'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->item->isbn_publishers_count; ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php echo $this->form->renderField('ismn_categories'); ?>	
            <?php if ($this->item->id > 0) : ?>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo JText::_('COM_ISBNREGISTRY_GROUP_MESSAGES_FIELD_ISMN_PUBLISHER_COUNT_LABEL'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->item->ismn_publishers_count; ?>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo JText::_('COM_ISBNREGISTRY_GROUP_MESSAGES_FIELD_PUBLISHER_COUNT_LABEL'); ?>
                    </div>
                    <div class="controls">
                        <?php
                        echo $this->item->publishers_count;
                        $link = ' (<a href="index.php?option=com_isbnregistry&layout=group_messages&view=messages&groupMessageId=' . $this->item->id;
                        $link .= '&tmpl=component" class="modal" rel="{size: {x: 1200, y: 600}, handler:\'iframe\'}">' . JText::_('COM_ISBNREGISTRY_GROUP_MESSAGE_FIELD_SHOW_LABEL') . '</a>)';
                        echo $link;
                        ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php echo $this->form->renderField('has_quitted'); ?>
        </div>
    </div>
    <input type="hidden" name="task" value="groupmessage.edit" />
    <?php echo JHtml::_('form.token'); ?>
</form>
