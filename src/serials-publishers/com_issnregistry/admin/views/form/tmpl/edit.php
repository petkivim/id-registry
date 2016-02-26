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
$document->addScript('components/com_issnregistry/scripts/form_validators.js');
$document->addScript("components/com_issnregistry/scripts/form.js");
// Add css
$document->addStyleSheet("components/com_issnregistry/css/form.css");

// This fix is needed for JToolBar
JFactory::getDocument()->addScriptDeclaration('
    Joomla.submitbutton = function(task)
    {
        if (task == "form.cancel" || document.formvalidator.isValid(document.getElementById("adminForm")))
        {
            Joomla.submitform(task, document.getElementById("adminForm"));
        }
    };
');
?>
<form action="<?php echo JRoute::_('index.php?option=com_issnregistry&layout=edit&id=' . (int) $this->item->id); ?>"
      method="post" name="adminForm" id="adminForm" class="form-validate">
    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'publisher')); ?>
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publisher', JText::_('COM_ISSNREGISTRY_FORM_TAB_PUBLISHER', true)); ?>
        <div class="row-fluid form-horizontal-desktop">
            <div class="span6">        
                <legend><?php echo JText::_('COM_ISSNREGISTRY_FORM_TAB_PUBLISHER_SUBTITLE_1'); ?></legend>
                <?php echo $this->form->renderField('publisher'); ?>
                <?php echo $this->form->renderField('contact_person'); ?>
                <?php echo $this->form->renderField('lang_code'); ?>
                <?php echo $this->form->renderField('email'); ?>
                <?php echo $this->form->renderField('phone'); ?>            
                <?php echo $this->form->renderField('address'); ?>
                <?php echo $this->form->renderField('zip'); ?>
                <?php echo $this->form->renderField('city'); ?>            
            </div>
            <div class="span6">
                <legend><?php echo JText::_('COM_ISSNREGISTRY_FORM_TAB_PUBLISHER_SUBTITLE_2'); ?></legend>
                <?php echo $this->form->renderField('status'); ?>
                <?php echo $this->form->renderField('publication_count'); ?>
                <?php echo $this->form->renderField('created'); ?>
                <?php echo $this->form->renderField('created_by'); ?>
                <?php echo $this->form->renderField('modified'); ?>
                <?php echo $this->form->renderField('modified_by'); ?>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php if ($this->item->id != 0) : ?>
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publications', JText::_('COM_ISSNREGISTRY_FORM_TAB_PUBLICATIONS', true)); ?>
            <div class="row-fluid form-horizontal-desktop">       
                <?php echo '<div class="add_publication">' . JText::_('COM_ISSNREGISTRY_FORM_TAB_PUBLICATIONS_ADD_NEW') . ' <span class="icon-new"></span></div>'; ?>
                <?php echo $this->loadTemplate('publications_list'); ?>	               
            </div>
            <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php endif; ?>
        <?php echo JHtml::_('bootstrap.endTabSet'); ?>
    </div>
    <input type="hidden" name="task" value="form.edit" />
    <?php echo JHtml::_('form.token'); ?>
</form>
