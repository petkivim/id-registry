<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
 * @author 	Petteri Kivim�ki
 * @copyright	Copyright (C) 2016 Petteri Kivim�ki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.formvalidator');
JHtml::_('formbehavior.chosen');
JHTML::_('behavior.modal');

// Add scripts
$document = JFactory::getDocument();
$document->addScript('components/com_issnregistry/scripts/publisher_validators.js');
$document->addScript("components/com_issnregistry/scripts/publisher.js");
// Add css
$document->addStyleSheet("components/com_issnregistry/css/publisher.css");

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
// If "tmpl=component" URL parameter is present, use view only mode
$viewOnly = strcmp(htmlentities(JRequest::getVar('tmpl')), 'component') == 0 ? true : false;
?>
<form action="<?php echo JRoute::_('index.php?option=com_issnregistry&layout=edit&id=' . (int) $this->item->id); ?>"
      method="post" name="adminForm" id="adminForm" class="form-validate">
    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'basic')); ?>
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'basic', JText::_('COM_ISSNREGISTRY_PUBLISHER_TAB_BASIC', true)); ?>
        <div class="row-fluid form-horizontal-desktop">
            <div class="span6">              
                <?php echo $this->form->renderField('id'); ?>
                <?php echo $this->form->renderField('official_name'); ?>
                <?php echo $this->form->renderField('contact_person'); ?>
                <?php echo $this->form->renderField('lang_code'); ?>
                <?php echo $this->form->renderField('additional_info'); ?>
            </div>
            <div class="span6">
                <?php echo $this->form->renderField('address'); ?>
                <?php echo $this->form->renderField('zip'); ?>
                <?php echo $this->form->renderField('city'); ?>
                <?php echo $this->form->renderField('phone'); ?>
                <?php echo $this->form->renderField('email_common'); ?>

            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php if (!$viewOnly && $this->item->id > 0) : ?>
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publications', JText::_('COM_ISSNREGISTRY_PUBLISHER_TAB_PUBLICATIONS', true)); ?>
            <div class="row-fluid form-horizontal-desktop">
                <?php echo $this->loadTemplate('publications_list'); ?>
            </div>
            <?php echo JHtml::_('bootstrap.endTab'); ?> 
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'messages', JText::_('COM_ISSNREGISTRY_PUBLISHER_TAB_MESSAGES', true)); ?>
            <div class="row-fluid form-horizontal-desktop">
                <?php echo $this->loadTemplate('messages_list'); ?>
            </div>
            <?php echo JHtml::_('bootstrap.endTab'); ?>
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'history', JText::_('COM_ISSNREGISTRY_PUBLISHER_TAB_HISTORY', true)); ?>
            <div class="row-fluid form-horizontal-desktop">
                <div class="span6">
                    <?php echo $this->form->renderField('created'); ?>
                    <?php echo $this->form->renderField('created_by'); ?>
                    <?php echo $this->form->renderField('modified'); ?>
                    <?php echo $this->form->renderField('modified_by'); ?>
                </div>
                <div class="span6">
                </div>
            </div>
            <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php endif; ?>
        <?php echo JHtml::_('bootstrap.endTabSet'); ?>
    </div>
    <input type="hidden" name="task" value="publisher.edit" />
    <?php echo JHtml::_('form.token'); ?>
</form>
