<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.formvalidator');
JHtml::_('jquery.framework');

// Add scripts
$document = JFactory::getDocument();
$document->addScript('components/com_isbnregistry/scripts/publisher_validators.js');
$document->addScript("components/com_isbnregistry/scripts/chosen/chosen.jquery.js");
$document->addScript("components/com_isbnregistry/scripts/publisher.js");
// Add css
$document->addStyleSheet("components/com_isbnregistry/css/publisher.css");
$document->addStyleSheet("components/com_isbnregistry/scripts/chosen/chosen.css");

// This fix is needed for JToolBar
JFactory::getDocument()->addScriptDeclaration('
    Joomla.submitbutton = function(task)
    {
        if (task == "publisher.cancel" || document.formvalidator.isValid(document.getElementById("adminForm")))
        {
            Joomla.submitform(task, document.getElementById("adminForm"));
        }
    };
');
?>
<form action="<?php echo JRoute::_('index.php?option=com_isbnregistry&layout=edit&id=' . (int) $this->item->id); ?>"
      method="post" name="adminForm" id="adminForm" class="form-validate">
    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_ISBNREGISTRY_PUBLISHER_TAB_BASIC', true)); ?>
        <div class="row-fluid form-horizontal-desktop">
            <div class="span6">              
                <?php echo $this->form->renderField('official_name'); ?>
                <?php echo $this->form->renderField('other_names'); ?>
                <?php echo $this->form->renderField('contact_person'); ?>
                <?php echo $this->form->renderField('lang_code'); ?>
                <?php echo $this->form->renderField('state'); ?>
                <?php echo $this->form->renderField('year_quitted'); ?>
                <?php echo $this->form->renderField('additional_info'); ?>
                <?php echo $this->form->renderField('created'); ?>
                <?php echo $this->form->renderField('created_by'); ?>
                <?php echo $this->form->renderField('modified'); ?>
                <?php echo $this->form->renderField('modified_by'); ?>
            </div>
            <div class="span6">
                <?php echo $this->form->renderField('address'); ?>
                <?php echo $this->form->renderField('zip'); ?>
                <?php echo $this->form->renderField('city'); ?>
                <?php echo $this->form->renderField('phone'); ?>
                <?php echo $this->form->renderField('email'); ?>
                <?php echo $this->form->renderField('www'); ?>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'additional', JText::_('COM_ISBNREGISTRY_PUBLISHER_TAB_ADDITIONAL', true)); ?>
        <div class="row-fluid form-horizontal-desktop">
            <div class="span6">
                <?php echo $this->form->renderField('question_1'); ?>
                <?php echo $this->form->renderField('question_2'); ?>
                <?php echo $this->form->renderField('question_3'); ?>
                <?php echo $this->form->renderField('question_4'); ?>
            </div>
            <div class="span6">
                <?php echo $this->form->renderField('question_5'); ?>
                <?php echo $this->form->renderField('question_6'); ?>
                <?php echo $this->form->renderField('question_7'); ?>
                <?php echo $this->form->renderField('question_8'); ?>
                <?php echo $this->form->renderField('confirmation'); ?>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'isbn', JText::_('COM_ISBNREGISTRY_PUBLISHER_TAB_ISBN', true)); ?>
        <div class="row-fluid form-horizontal-desktop">
            <div class="span6">
            </div>
            <div class="span6">
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php echo JHtml::_('bootstrap.endTabSet'); ?>
    </div>
    <input type="hidden" name="task" value="publisher.edit" />
    <?php echo JHtml::_('form.token'); ?>
</form>
