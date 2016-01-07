<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 		Petteri Kivimäki
 * @copyright	Copyright (C) 2015 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.formvalidator');
JHtml::_('formbehavior.chosen');
JHTML::_('behavior.modal');

// Add scripts
$document = JFactory::getDocument();
$document->addScript('components/com_isbnregistry/scripts/publisher_validators.js');
$document->addScript("components/com_isbnregistry/scripts/publisher.js");
// Add css
$document->addStyleSheet("components/com_isbnregistry/css/publisher.css");

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
<form action="<?php echo JRoute::_('index.php?option=com_isbnregistry&layout=edit&id=' . (int) $this->item->id); ?>"
      method="post" name="adminForm" id="adminForm" class="form-validate">
    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_ISBNREGISTRY_PUBLISHER_TAB_BASIC', true)); ?>
        <div class="row-fluid form-horizontal-desktop">
            <div class="span6">              
                <?php echo $this->form->renderField('id'); ?>
                <?php echo $this->form->renderField('official_name'); ?>
                <?php echo $this->form->renderField('other_names'); ?>
                <?php echo $this->form->renderField('previous_names'); ?>
                <?php echo $this->form->renderField('contact_person'); ?>
                <?php echo $this->form->renderField('lang_code'); ?>
                <?php echo $this->form->renderField('has_quitted'); ?>
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
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'identifiers', JText::_('COM_ISBNREGISTRY_PUBLISHER_TAB_PUBLISHER_IDENTIFIERS', true)); ?>
        <div class="row-fluid form-horizontal-desktop">
            <div class="span6" id="publisherIsbnRanges">
                <?php if (!$viewOnly) : ?>
                    <legend><?php echo JText::_('COM_ISBNREGISTRY_PUBLISHER_TAB_PUBLISHER_IDENTIFIERS_SUBTITLE_1'); ?></legend>
                    <?php echo $this->form->renderField('isbn_range'); ?>
                    <?php echo $this->form->renderField('get_publisher_identifier_isbn'); ?>
                <?php endif; ?>
                <legend><?php echo JText::_('COM_ISBNREGISTRY_PUBLISHER_TAB_PUBLISHER_IDENTIFIERS_SUBTITLE_2'); ?></legend>							
                <?php echo $this->loadTemplate('isbn_ranges'); ?>				
            </div>
            <div class="span6" id="publisherIsmnRanges">
                <?php if (!$viewOnly) : ?>
                    <legend><?php echo JText::_('COM_ISBNREGISTRY_PUBLISHER_TAB_PUBLISHER_IDENTIFIERS_SUBTITLE_3'); ?></legend>
                    <?php echo $this->form->renderField('ismn_range'); ?>
                    <?php echo $this->form->renderField('get_publisher_identifier_ismn'); ?>	
                <?php endif; ?>
                <legend><?php echo JText::_('COM_ISBNREGISTRY_PUBLISHER_TAB_PUBLISHER_IDENTIFIERS_SUBTITLE_4'); ?></legend>							
                <?php echo $this->loadTemplate('ismn_ranges'); ?>	
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php if (!$viewOnly) : ?>
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'isbn', JText::_('COM_ISBNREGISTRY_PUBLISHER_TAB_PUBLICATION_ISBN', true)); ?>
            <div class="row-fluid form-horizontal-desktop">
                <div class="span6">
                    <legend><?php echo JText::_('COM_ISBNREGISTRY_PUBLISHER_TAB_PUBLICATION_ISBN_SUBTITLE_1'); ?></legend>
                    <?php echo $this->form->renderField('isbn_count'); ?>
                    <?php echo $this->form->renderField('get_isbns'); ?>	
                    <?php echo $this->form->renderField('created_isbns'); ?>
                    <?php echo $this->form->renderField('notify_isbns'); ?>
                </div>
                <div class="span6">		
                    <legend><?php echo JText::_('COM_ISBNREGISTRY_PUBLISHER_TAB_PUBLICATION_ISBN_SUBTITLE_2'); ?></legend>
                    <?php echo $this->form->renderField('publications_without_isbn'); ?>
                    <?php echo $this->form->renderField('get_isbn'); ?>		
                    <?php echo $this->form->renderField('link_to_publication_isbn'); ?>		
                    <?php echo $this->form->renderField('notify_isbn'); ?>	
                </div>
            </div>
            <?php echo JHtml::_('bootstrap.endTab'); ?>
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'ismn', JText::_('COM_ISBNREGISTRY_PUBLISHER_TAB_PUBLICATION_ISMN', true)); ?>
            <div class="row-fluid form-horizontal-desktop">
                <div class="span6">
                    <legend><?php echo JText::_('COM_ISBNREGISTRY_PUBLISHER_TAB_PUBLICATION_ISMN_SUBTITLE_1'); ?></legend>
                    <?php echo $this->form->renderField('ismn_count'); ?>
                    <?php echo $this->form->renderField('get_ismns'); ?>	
                    <?php echo $this->form->renderField('created_ismns'); ?>
                    <?php echo $this->form->renderField('notify_ismns'); ?>
                </div>
                <div class="span6">		
                    <legend><?php echo JText::_('COM_ISBNREGISTRY_PUBLISHER_TAB_PUBLICATION_ISMN_SUBTITLE_2'); ?></legend>	
                    <?php echo $this->form->renderField('publications_without_ismn'); ?>
                    <?php echo $this->form->renderField('get_ismn'); ?>		
                    <?php echo $this->form->renderField('link_to_publication_ismn'); ?>	
                    <?php echo $this->form->renderField('notify_ismn'); ?>
                </div>
            </div>
            <?php echo JHtml::_('bootstrap.endTab'); ?>
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publications', JText::_('COM_ISBNREGISTRY_PUBLISHER_TAB_PUBLICATIONS', true)); ?>
            <div class="row-fluid form-horizontal-desktop">
                <?php echo $this->loadTemplate('publications_list'); ?>
            </div>
            <?php echo JHtml::_('bootstrap.endTab'); ?> 
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'messages', JText::_('COM_ISBNREGISTRY_PUBLISHER_TAB_MESSAGES', true)); ?>
            <div class="row-fluid form-horizontal-desktop">
                <?php echo $this->loadTemplate('messages_list'); ?>
            </div>
            <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php endif; ?>
        <?php echo JHtml::_('bootstrap.endTabSet'); ?>
    </div>
    <?php echo $this->form->renderField('batch_id_isbns'); ?>
    <?php echo $this->form->renderField('batch_id_ismns'); ?>
    <?php echo $this->form->renderField('batch_id_isbn'); ?>
    <?php echo $this->form->renderField('batch_id_ismn'); ?>
    <?php echo $this->form->renderField('publication_id_isbn'); ?>
    <?php echo $this->form->renderField('publication_id_ismn'); ?>
    <input type="hidden" name="task" value="publisher.edit" />
    <?php echo JHtml::_('form.token'); ?>
</form>
