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
JHTML::_('behavior.modal');

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
// If "tmpl=component" URL parameter is present, use view only mode
$viewOnly = strcmp(htmlentities(JFactory::getApplication()->input->get('tmpl')), 'component') == 0 ? true : false;
?>
<form action="<?php echo JRoute::_('index.php?option=com_issnregistry&layout=edit&id=' . (int) $this->item->id); ?>"
      method="post" name="adminForm" id="adminForm" class="form-validate">
    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'publisher')); ?>
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publisher', JText::_('COM_ISSNREGISTRY_FORM_TAB_PUBLISHER', true)); ?>
        <div class="row-fluid form-horizontal-desktop">
            <div class="span6">        
                <legend><?php echo JText::_('COM_ISSNREGISTRY_FORM_TAB_PUBLISHER_SUBTITLE_3'); ?></legend>
                <?php echo $this->form->renderField('publisher_id'); ?>
                <?php
                if (!$viewOnly) {
                    echo $this->form->renderField('link_to_publisher');
                }
                ?>
                <?php echo $this->form->renderField('status'); ?>
                <?php echo $this->form->renderField('publication_count'); ?>
                <?php echo $this->form->renderField('publication_count_issn'); ?>        
            </div>
            <div class="span6">
                <legend><?php echo JText::_('COM_ISSNREGISTRY_FORM_TAB_PUBLISHER_SUBTITLE_1'); ?></legend>
                <?php echo $this->form->renderField('id'); ?>
                <?php echo $this->form->renderField('publisher'); ?>
                <?php echo $this->form->renderField('contact_person'); ?>
                <?php echo $this->form->renderField('lang_code'); ?>
                <?php echo $this->form->renderField('email'); ?>
                <?php echo $this->form->renderField('phone'); ?>            
                <?php echo $this->form->renderField('address'); ?>
                <?php echo $this->form->renderField('zip'); ?>
                <?php echo $this->form->renderField('city'); ?>   
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php if (!$viewOnly && $this->item->id > 0) : ?>
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publications', JText::_('COM_ISSNREGISTRY_FORM_TAB_PUBLICATIONS', true)); ?>
            <div class="row-fluid form-horizontal-desktop">       
                <div class="add_publication">
                    <a id="add_publication_link" href="index.php?option=com_issnregistry&task=form.createPublication&formId=<?php echo $this->item->id; ?>&<?php echo JSession::getFormToken() ?>=1">
                        <?php echo JText::_('COM_ISSNREGISTRY_FORM_TAB_PUBLICATIONS_ADD_NEW') ?>
                        <span class="icon-new"></span>
                    </a>
                </div>
                <?php echo $this->loadTemplate('publications_list'); ?>	               
            </div>
            <?php echo JHtml::_('bootstrap.endTab'); ?>
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'messages', JText::_('COM_ISSNREGISTRY_FORM_TAB_MESSAGES', true)); ?>
            <div class="row-fluid form-horizontal-desktop">
                <?php echo $this->loadTemplate('messages_list'); ?>
            </div>
            <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php endif; ?>
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'history', JText::_('COM_ISSNREGISTRY_FORM_TAB_HISTORY', true)); ?>
        <div class="row-fluid form-horizontal-desktop">
            <div class="span6">
                <?php
                if (!$viewOnly) {
                    echo $this->loadTemplate('link_to_archive_record');
                }
                ?>
                <?php echo $this->form->renderField('created'); ?>
                <?php echo $this->form->renderField('created_by'); ?>
                <?php echo $this->form->renderField('modified'); ?>
                <?php echo $this->form->renderField('modified_by'); ?>
            </div>
            <div class="span6">
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php echo JHtml::_('bootstrap.endTabSet'); ?>
    </div>
    <input type="hidden" name="task" value="form.edit" />
    <span id="label_confirm_add_publication" style=" visibility:hidden"><?php echo JText::_('COM_ISSNREGISTRY_FORM_CONFIRM_ADD_PUBLICATION'); ?></span>
    <?php echo JHtml::_('form.token'); ?>
</form>
