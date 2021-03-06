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
$document->addScript('components/com_issnregistry/scripts/publication_validators.js');
$document->addScript("components/com_issnregistry/scripts/publication.js");
// Add css
$document->addStyleSheet("components/com_issnregistry/css/publication.css");

// This fix is needed for JToolBar
JFactory::getDocument()->addScriptDeclaration('
    Joomla.submitbutton = function(task)
    {       
        if(task == "publication.deleteIssn") {
            var response = confirm("' . JText::_('COM_ISSNREGISTRY_CONFIRM_DELETE_ISSN') . '");
            if (response == true) {
                Joomla.submitform(task);
            }
        } else if (task == "publication.cancel" || document.formvalidator.isValid(document.getElementById("adminForm")))
        {
            Joomla.submitform(task, document.getElementById("adminForm"));
        }
    };
');
?>				
<form action="<?php echo JRoute::_('index.php?option=com_issnregistry&layout=edit&id=' . (int) $this->item->id); ?>"
      method="post" name="adminForm" id="adminForm" class="form-validate">
    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'basic')); ?>
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'basic', JText::_('COM_ISSNREGISTRY_PUBLICATION_TAB_BASIC', true)); ?>
        <div class="row-fluid form-horizontal-desktop">
            <div class="span6">              
                <?php echo $this->form->renderField('id'); ?>
                <?php echo $this->form->renderField('form_id'); ?>
                <?php echo $this->form->renderField('title'); ?>
                <?php echo $this->form->renderField('subtitle'); ?>
                <?php echo $this->form->renderField('issn'); ?>
                <?php echo $this->form->renderField('publisher_id'); ?> 
                <?php echo $this->form->renderField('link_to_publisher'); ?>
                <?php echo $this->form->renderField('status'); ?>
                <?php echo $this->form->renderField('place_of_publication'); ?>
                <?php echo $this->form->renderField('printer'); ?>               
                <?php echo $this->form->renderField('issued_from_year'); ?>
                <?php echo $this->form->renderField('issued_from_number'); ?>                             
            </div>
            <div class="span6">
                <?php echo $this->form->renderField('frequency'); ?> 
                <?php echo $this->form->renderField('frequency_other'); ?> 
                <?php echo $this->form->renderField('language'); ?>
                <?php echo $this->form->renderField('publication_type'); ?>
                <?php echo $this->form->renderField('publication_type_other'); ?>
                <?php echo $this->form->renderField('medium'); ?>
                <?php echo $this->form->renderField('medium_other'); ?>
                <?php echo $this->form->renderField('url'); ?>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'additional', JText::_('COM_ISSNREGISTRY_PUBLICATION_TAB_ADDITIONAL', true)); ?>
        <div class="row-fluid form-horizontal-desktop">
            <div class="span6">
                <legend><?php echo JText::_('COM_ISSNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_4'); ?></legend>
                <?php echo $this->form->renderField('previous'); ?>

                <legend><?php echo JText::_('COM_ISSNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_5'); ?></legend>
                <?php echo $this->form->renderField('main_series'); ?>

                <legend><?php echo JText::_('COM_ISSNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_8'); ?></legend>
                <?php echo $this->form->renderField('additional_info'); ?> 
            </div>
            <div class="span6">
                <legend><?php echo JText::_('COM_ISSNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_6'); ?></legend>
                <?php echo $this->form->renderField('subseries'); ?>

                <legend><?php echo JText::_('COM_ISSNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_7'); ?></legend>
                <?php echo $this->form->renderField('another_medium'); ?> 
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'history', JText::_('COM_ISSNREGISTRY_PUBLICATION_TAB_HISTORY', true)); ?>
        <div class="row-fluid form-horizontal-desktop">
            <div class="span6">
                <?php echo $this->loadTemplate('link_to_archive_record'); ?>
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
    <input type="hidden" name="task" value="publication.edit" />
    <?php echo JHtml::_('form.token'); ?>
</form>
