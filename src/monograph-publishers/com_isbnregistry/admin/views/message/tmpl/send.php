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

// Add scripts
$document = JFactory::getDocument();
$document->addScript('components/com_isbnregistry/scripts/message_validators.js');
$document->addScript("components/com_isbnregistry/scripts/message.js");
// Add css
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
?>
<form action="<?php echo JRoute::_('index.php?option=com_isbnregistry&layout=send&id=' . (int) $this->item->id); ?>"
      method="post" name="adminForm" id="adminForm" class="form-validate">
    <div class="form-horizontal">
        <div class="control-group">
            <div class="control-label">
            </div>
            <div class="controls">
                <button id="jform_send" class="btn btn-small"><span class="icon-save"></span><?php echo JText::_('COM_ISBNREGISTRY_MESSAGE_BUTTON_SEND'); ?></button>
                <button id="jform_close" class="btn btn-small"><span class="icon-cancel"></span><?php echo JText::_('COM_ISBNREGISTRY_MESSAGE_BUTTON_CLOSE'); ?></button>
            </div>
        </div>
        <?php echo $this->form->renderField('recipient'); ?>			
        <?php echo $this->form->renderField('subject'); ?> 
        <?php echo $this->form->renderField('message'); ?>        
        <?php echo $this->form->renderField('lang_code'); ?>
        <?php echo $this->form->renderField('message_template_id'); ?>
        <?php echo $this->form->renderField('message_type_id'); ?>
        <?php echo $this->form->renderField('publisher_id'); ?>
        <?php echo $this->form->renderField('publication_id'); ?>
        <?php echo $this->form->renderField('batch_id'); ?>
        <?php echo $this->form->renderField('has_attachment'); ?>
    </div>
    <input type="hidden" name="task" value="message.send" />
    <?php echo JHtml::_('form.token'); ?>
</form>
