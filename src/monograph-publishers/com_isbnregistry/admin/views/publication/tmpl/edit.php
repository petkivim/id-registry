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
if (!empty($this->item->publication_identifier_print) || !empty($this->item->publication_identifier_electronical)) {
    $this->form->setFieldAttribute('publisher_id', 'readonly', 'true');
    $this->form->setFieldAttribute('publication_type', 'readonly', 'true');
    $this->form->setFieldAttribute('publication_format', 'readonly', 'true');
    $this->form->setFieldAttribute('type', 'readonly', 'true');
    $this->form->setFieldAttribute('fileformat', 'readonly', 'true');
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
                <?php echo $this->form->renderField('on_process'); ?>
                <?php echo $this->form->renderField('official_name'); ?>
                <?php echo $this->form->renderField('publisher_identifier_str'); ?>
                <?php echo $this->form->renderField('locality'); ?>
                <?php echo $this->form->renderField('publisher_id'); ?>
                <?php echo $this->form->renderField('link_to_publisher'); ?>
                <?php echo $this->form->renderField('contact_person'); ?>
                <?php echo $this->form->renderField('lang_code'); ?>			
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
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHING', true)); ?>
        <div class="row-fluid form-horizontal-desktop">
            <div class="span6">              
                <?php echo $this->form->renderField('published_before'); ?>
                <?php echo $this->form->renderField('publications_public'); ?>
                <?php echo $this->form->renderField('publications_intra'); ?>
                <?php echo $this->form->renderField('publishing_activity'); ?>
                <?php echo $this->form->renderField('publishing_activity_amount'); ?>				
            </div>
            <div class="span6">
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publication', JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLICATION', true)); ?>
        <div class="row-fluid form-horizontal-desktop">
            <div class="span6">
                <legend><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_4'); ?></legend>
                <?php echo $this->form->renderField('publication_type'); ?>
                <?php echo $this->form->renderField('publication_format'); ?>
                <!-- Below line is needed because when saving a form with a checkbox 
                that is unchecked, there is no variable for it in the POST data -->
                <input type="hidden" name="jform[no_identifier_granted]" value="0">
                <?php echo $this->form->renderField('no_identifier_granted'); ?>    
                <legend><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_7'); ?></legend>
                <?php echo $this->form->renderField('series'); ?>
                <?php echo $this->form->renderField('issn'); ?>	
                <?php echo $this->form->renderField('volume'); ?>
            </div>
            <div class="span6">     
                <legend><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_6'); ?></legend>
                <?php echo $this->form->renderField('title'); ?>
                <?php echo $this->form->renderField('subtitle'); ?>	
                <?php echo $this->form->renderField('map_scale'); ?>
                <?php echo $this->form->renderField('language'); ?>	
                <?php echo $this->form->renderField('publication_identifier_print'); ?>
                <div class="control-group">
                    <div class="control-label">                 
                        <?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_FIELD_PUBLICATION_IDENTIFIER_PRINT_LABEL'); ?>
                    </div>
                    <div class="controls">
                        <?php
                        $json = json_decode($this->item->publication_identifier_print);
                        if (!empty($json)) {
                            $titleCancel = JText::_('COM_ISBNREGISTRY_PUBLICATION_LABEL_CANCEL_IDENTIFIER');
                            $titleDelete = JText::_('COM_ISBNREGISTRY_PUBLICATION_LABEL_DELETE_IDENTIFIER');
                            foreach ($json as $identifier => $type) {
                                echo '<div class="identifier" id="' . $identifier . '">' . $identifier . ' (' . JText::_('COM_ISBNREGISTRY_PUBLICATION_JSON_TYPE_' . $type) . ') <span class="icon-delete" title="' . $titleCancel . '"></span> <span class="icon-trash" title="' . $titleDelete . '"></span></div>';
                            }
                        } else {
                            echo '-';
                        }
                        ?>
                    </div>
                </div>
                <?php echo $this->form->renderField('publication_identifier_electronical'); ?>
                <div class="control-group">
                    <div class="control-label">                       
                        <?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_FIELD_PUBLICATION_IDENTIFIER_ELECTRONICAL_LABEL'); ?>
                    </div>
                    <div class="controls">
                        <?php
                        $json = json_decode($this->item->publication_identifier_electronical);
                        if (!empty($json)) {
                            $titleCancel = JText::_('COM_ISBNREGISTRY_PUBLICATION_LABEL_CANCEL_IDENTIFIER');
                            $titleDelete = JText::_('COM_ISBNREGISTRY_PUBLICATION_LABEL_DELETE_IDENTIFIER');
                            foreach ($json as $identifier => $type) {
                                echo '<div class="identifier" id="' . $identifier . '">' . $identifier . ' (' . JText::_('COM_ISBNREGISTRY_PUBLICATION_JSON_TYPE_' . $type) . ') <span class="icon-delete" title="' . $titleCancel . '"></span> <span class="icon-trash" title="' . $titleDelete . '"></span></div>';
                            }
                        } else {
                            echo '-';
                        }
                        ?>
                    </div>
                </div>
                <h4><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_FIELD_PUBLISHED'); ?></h4>
                <?php echo $this->form->renderField('year'); ?>	
                <?php echo $this->form->renderField('month'); ?>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'print_electronical', JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PRINT_ELECTRONICAL', true)); ?>
        <div class="row-fluid form-horizontal-desktop">
            <div class="span6">              
                <legend><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_8'); ?></legend>		
                <?php echo $this->form->renderField('printing_house'); ?>
                <?php echo $this->form->renderField('printing_house_city'); ?>	
                <?php echo $this->form->renderField('copies'); ?>	
                <?php echo $this->form->renderField('edition'); ?>		
                <?php echo $this->form->renderField('type'); ?>			
            </div>
            <div class="span6">
                <legend><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_10'); ?></legend>
                <?php echo $this->form->renderField('fileformat'); ?>
                <?php echo $this->form->renderField('fileformat_other'); ?>	
                <legend><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_9'); ?></legend>
                <?php echo $this->form->renderField('comments'); ?>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'authors', JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_AUTHORS', true)); ?>
        <div class="row-fluid form-horizontal-desktop">
            <div class="span6">              
                <h4><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_FIELD_PUBLICATION_AUTHOR_MAIN'); ?></h4>
                <?php echo $this->form->renderField('first_name_1'); ?>
                <?php echo $this->form->renderField('last_name_1'); ?>	
                <?php echo $this->form->renderField('role_1'); ?>						
            </div>
            <div class="span6">
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
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'history', JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_HISTORY', true)); ?>
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
        <?php echo JHtml::_('bootstrap.endTabSet'); ?>
    </div>
    <span id="label_confirm_cancel" style=" visibility:hidden"><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_CONFIRM_CANCEL_IDENTIFIER'); ?></span>
    <span id="label_confirm_delete" style=" visibility:hidden"><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_CONFIRM_DELETE_IDENTIFIER'); ?></span>
    <input type="hidden" name="task" value="publication.edit" />
    <?php echo JHtml::_('form.token'); ?>
</form>
