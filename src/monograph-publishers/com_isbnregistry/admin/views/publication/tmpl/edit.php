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
$document->addScript('components/com_isbnregistry/scripts/publication_validators.js');
$document->addScript("components/com_isbnregistry/scripts/publication.js");
// Add css
$document->addStyleSheet("components/com_isbnregistry/css/publication.css");

// This fix is needed for JToolBar
JFactory::getDocument()->addScriptDeclaration('
    Joomla.submitbutton = function(task)
    {
        if (task == "publication.cancel" || document.formvalidator.isValid(document.getElementById("adminForm")))
        {
            Joomla.submitform(task, document.getElementById("adminForm"));
        }
    };
');
// If even one ISBN has been used, this item can't be modified
if (strlen($this->item->publication_identifier) != 0) {
    $this->form->setFieldAttribute('publisher_id', 'readonly', 'true');
}
?>				
<form action="<?php echo JRoute::_('index.php?option=com_isbnregistry&layout=edit&id=' . (int) $this->item->id); ?>"
      method="post" name="adminForm" id="adminForm" class="form-validate">
    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'publisher')); ?>
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publisher', JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER', true)); ?>
        <div class="row-fluid form-horizontal-desktop">
            <div class="span6">              
                <legend><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_1'); ?></legend>
                <?php echo $this->form->renderField('id'); ?>
                <?php echo $this->form->renderField('official_name'); ?>
                <?php echo $this->form->renderField('publisher_identifier_str'); ?>
                <?php echo $this->form->renderField('locality'); ?>
                <?php echo $this->form->renderField('publisher_id'); ?>
                <?php echo $this->form->renderField('link_to_publisher'); ?>
                <?php echo $this->form->renderField('contact_person'); ?>
                <?php echo $this->form->renderField('lang_code'); ?>
                <?php echo $this->form->renderField('no_identifier_granted'); ?>
                <?php echo $this->form->renderField('created'); ?>
                <?php echo $this->form->renderField('created_by'); ?>
                <?php echo $this->form->renderField('modified'); ?>
                <?php echo $this->form->renderField('modified_by'); ?>

                <legend><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_2'); ?></legend>
                <?php echo $this->form->renderField('published_before'); ?>
                <?php echo $this->form->renderField('publications_public'); ?>
                <?php echo $this->form->renderField('publications_intra'); ?>
                <?php echo $this->form->renderField('publishing_activity'); ?>
                <?php echo $this->form->renderField('publishing_activity_amount'); ?>				
            </div>
            <div class="span6">
                <legend><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_3'); ?></legend>
                <?php echo $this->form->renderField('address'); ?>
                <?php echo $this->form->renderField('zip'); ?>
                <?php echo $this->form->renderField('city'); ?>
                <?php echo $this->form->renderField('phone'); ?>
                <?php echo $this->form->renderField('email'); ?>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publication', JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLICATION', true)); ?>
        <div class="row-fluid form-horizontal-desktop">
            <div class="span6">
                <legend><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_4'); ?></legend>
                <?php echo $this->form->renderField('publication_type'); ?>
                <?php echo $this->form->renderField('publication_format'); ?>
                <legend><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_5'); ?></legend>
                <h4><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_FIELD_PUBLICATION_AUTHOR_MAIN'); ?></h4>
                <?php echo $this->form->renderField('first_name_1'); ?>
                <?php echo $this->form->renderField('last_name_1'); ?>	
                <?php echo $this->form->renderField('role_1'); ?>			
                <h4><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_FIELD_PUBLICATION_AUTHOR_OTHERS'); ?></h4>
                <?php echo $this->form->renderField('first_name_2'); ?>
                <?php echo $this->form->renderField('last_name_2'); ?>	
                <?php echo $this->form->renderField('role_2'); ?>	
                <?php echo $this->form->renderField('first_name_3'); ?>
                <?php echo $this->form->renderField('last_name_3'); ?>	
                <?php echo $this->form->renderField('role_3'); ?>	
                <?php echo $this->form->renderField('first_name_4'); ?>
                <?php echo $this->form->renderField('last_name_4'); ?>	
                <?php echo $this->form->renderField('role_4'); ?>	
            </div>
            <div class="span6">
                <legend><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_6'); ?></legend>
                <?php echo $this->form->renderField('title'); ?>
                <?php echo $this->form->renderField('subtitle'); ?>	
                <?php echo $this->form->renderField('map_scale'); ?>
                <?php echo $this->form->renderField('language'); ?>	
                <?php echo $this->form->renderField('publication_identifier'); ?>					
                <h4><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_FIELD_PUBLISHED'); ?></h4>
                <?php echo $this->form->renderField('year'); ?>	
                <?php echo $this->form->renderField('month'); ?>
                <legend><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_7'); ?></legend>
                <?php echo $this->form->renderField('series'); ?>
                <?php echo $this->form->renderField('issn'); ?>	
                <?php echo $this->form->renderField('volume'); ?>	
                <legend><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_8'); ?></legend>		
                <?php echo $this->form->renderField('printing_house'); ?>
                <?php echo $this->form->renderField('printing_house_city'); ?>	
                <?php echo $this->form->renderField('copies'); ?>	
                <?php echo $this->form->renderField('edition'); ?>		
                <?php echo $this->form->renderField('type'); ?>
                <legend><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_9'); ?></legend>
                <?php echo $this->form->renderField('comments'); ?>
                <legend><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_10'); ?></legend>
                <?php echo $this->form->renderField('fileformat'); ?>				
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php echo JHtml::_('bootstrap.endTabSet'); ?>
    </div>
    <input type="hidden" name="task" value="publisher.edit" />
    <?php echo JHtml::_('form.token'); ?>
</form>
