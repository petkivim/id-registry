<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.formvalidator');
JHtml::_('formbehavior.chosen');

// Add scripts
$document = JFactory::getDocument();
$document->addScript('components/com_issnregistry/scripts/messagetemplate_validators.js');
$document->addScript("components/com_issnregistry/scripts/messagetemplate.js");
// Add css
$document->addStyleSheet("components/com_issnregistry/css/messagetemplate.css");

// This fix is needed for JToolBar
JFactory::getDocument()->addScriptDeclaration('
    Joomla.submitbutton = function(task)
    {
        if (task == "messagetemplate.cancel" || document.formvalidator.isValid(document.getElementById("adminForm")))
        {
            Joomla.submitform(task, document.getElementById("adminForm"));
        }
    };
');
?>
<form action="<?php echo JRoute::_('index.php?option=com_issnregistry&layout=edit&id=' . (int) $this->item->id); ?>"
      method="post" name="adminForm" id="adminForm" class="form-validate">
    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'message')); ?>
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'message', JText::_('COM_ISSNREGISTRY_MESSAGE_TEMPLATE_TAB_MESSAGE', true)); ?>
        <div class="row-fluid form-horizontal-desktop">
            <?php echo $this->form->renderField('name'); ?>
            <?php echo $this->form->renderField('lang_code'); ?> 
            <?php echo $this->form->renderField('message_type_id'); ?> 
            <?php echo $this->form->renderField('subject'); ?> 
            <?php echo $this->form->renderField('message'); ?> 
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'info', JText::_('COM_ISSNREGISTRY_MESSAGE_TEMPLATE_TAB_INFO', true)); ?>
        <div class="row-fluid form-horizontal-desktop">
            <?php echo $this->form->renderField('created'); ?>
            <?php echo $this->form->renderField('created_by'); ?>
            <?php echo $this->form->renderField('modified'); ?>
            <?php echo $this->form->renderField('modified_by'); ?>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php echo JHtml::_('bootstrap.endTabSet'); ?>		
    </div>
    <input type="hidden" name="task" value="messagetemplate.edit" />
    <?php echo JHtml::_('form.token'); ?>
</form>
