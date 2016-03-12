<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('formbehavior.chosen');

// Add scripts
$document = JFactory::getDocument();
$document->addScript("components/com_isbnregistry/scripts/publication_print.js");
// Add css
$document->addStyleSheet("components/com_isbnregistry/css/publication.css");
$this->form->setFieldAttribute('lang_code', 'readonly', 'true');
$this->form->setFieldAttribute('no_identifier_granted', 'readonly', 'true');
$this->form->setFieldAttribute('publisher_id', 'readonly', 'true');
$this->form->setFieldAttribute('no_identifier_granted', 'disabled', 'true');
$this->form->setFieldAttribute('published_before', 'readonly', 'true');
$this->form->setFieldAttribute('publications_public', 'readonly', 'true');
$this->form->setFieldAttribute('publications_intra', 'readonly', 'true');
$this->form->setFieldAttribute('publishing_activity', 'readonly', 'true');
$this->form->setFieldAttribute('language', 'readonly', 'true');
$this->form->setFieldAttribute('publication_type', 'readonly', 'true');
$this->form->setFieldAttribute('publication_format', 'readonly', 'true');
$this->form->setFieldAttribute('role_1', 'readonly', 'true');
$this->form->setFieldAttribute('role_2', 'readonly', 'true');
$this->form->setFieldAttribute('role_3', 'readonly', 'true');
$this->form->setFieldAttribute('role_4', 'readonly', 'true');
$this->form->setFieldAttribute('type', 'readonly', 'true');
$this->form->setFieldAttribute('fileformat', 'readonly', 'true');
?>				

<button name="print" id="print"><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_BUTTON_PRINT'); ?></button>
<div class="form-horizontal">
    <div class="row-fluid form-horizontal-desktop">
        <div class="span6">              
            <legend><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_1'); ?></legend>
            <?php echo $this->form->renderField('id'); ?>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('official_name'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->official_name; ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('publisher_identifier_str'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->publisher_identifier_str; ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('locality'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->locality; ?>
                </div>
            </div>
            <?php echo $this->form->renderField('publisher_id'); ?>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('contact_person'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->contact_person; ?>
                </div>
            </div>
            <?php echo $this->form->renderField('lang_code'); ?>
            <?php echo $this->form->renderField('no_identifier_granted'); ?>
            <?php echo $this->form->renderField('created'); ?>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('created_by'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->created_by; ?>
                </div>
            </div>
            <?php echo $this->form->renderField('modified'); ?>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('modified_by'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->modified_by; ?>
                </div>
            </div>
            <legend><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_2'); ?></legend>
            <?php echo $this->form->renderField('published_before'); ?>
            <?php echo $this->form->renderField('publications_public'); ?>
            <?php echo $this->form->renderField('publications_intra'); ?>
            <?php echo $this->form->renderField('publishing_activity'); ?>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('publishing_activity_amount'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->publishing_activity_amount; ?>
                </div>
            </div>
            <legend><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_6'); ?></legend>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('title'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->title; ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('subtitle'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->subtitle; ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('map_scale'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->map_scale; ?>
                </div>
            </div>
            <?php echo $this->form->renderField('language'); ?>	
            <div class="control-group">
                <div class="control-label">
                    <?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_FIELD_PUBLICATION_IDENTIFIER_PRINT_LABEL'); ?>
                </div>
                <div class="controls">
                    <?php
                    $json = json_decode($this->item->publication_identifier_print);
                    if (!empty($json)) {
                        foreach ($json as $identifier => $type) {
                            echo $identifier . ' (' . JText::_('COM_ISBNREGISTRY_PUBLICATION_JSON_TYPE_' . $type) . ')<br />';
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_FIELD_PUBLICATION_IDENTIFIER_ELECTRONICAL_LABEL'); ?>
                </div>
                <div class="controls">
                    <?php
                    $json = json_decode($this->item->publication_identifier_electronical);
                    if (!empty($json)) {
                        foreach ($json as $identifier => $type) {
                            echo $identifier . ' (' . JText::_('COM_ISBNREGISTRY_PUBLICATION_JSON_TYPE_' . $type) . ')<br />';
                        }
                    }
                    ?>
                </div>
            </div>
            <h4><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_FIELD_PUBLISHED'); ?></h4>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('year'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->year; ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('month'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->month; ?>
                </div>
            </div>
            <legend><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_7'); ?></legend>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('series'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->series; ?>
                </div>
            </div>	
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('issn'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->issn; ?>
                </div>
            </div>	
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('volume'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->volume; ?>
                </div>
            </div>
        </div>
        <div class="span6">
            <legend><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_3'); ?></legend>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('address'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->address; ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('zip'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->zip; ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('city'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->city; ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('phone'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->phone; ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('email'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->email; ?>
                </div>
            </div>
            <legend><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_4'); ?></legend>
            <?php echo $this->form->renderField('publication_type'); ?>
            <?php echo $this->form->renderField('publication_format'); ?>
            <legend><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_5'); ?></legend>
            <h4><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_FIELD_PUBLICATION_AUTHOR_MAIN'); ?></h4>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('first_name_1'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->first_name_1; ?>
                </div>
            </div>	
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('last_name_1'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->last_name_1; ?>
                </div>
            </div>
            <?php echo $this->form->renderField('role_1'); ?>			
            <h4><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_FIELD_PUBLICATION_AUTHOR_OTHERS'); ?></h4>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('first_name_2'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->first_name_2; ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('last_name_2'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->last_name_2; ?>
                </div>
            </div>
            <?php echo $this->form->renderField('role_2'); ?>	
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('first_name_3'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->first_name_3; ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('last_name_3'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->last_name_3; ?>
                </div>
            </div>
            <?php echo $this->form->renderField('role_3'); ?>	
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('first_name_4'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->first_name_4; ?>
                </div>
            </div>	
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('last_name_4'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->last_name_4; ?>
                </div>
            </div>
            <?php echo $this->form->renderField('role_4'); ?>
            <legend><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_8'); ?></legend>		
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('printing_house'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->printing_house; ?>
                </div>
            </div>	
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('printing_house_city'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->printing_house_city; ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('copies'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->copies; ?>
                </div>
            </div>	
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('edition'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->edition; ?>
                </div>
            </div>
            <?php echo $this->form->renderField('type'); ?>
            <legend><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_9'); ?></legend>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('comments'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->comments; ?>
                </div>
            </div>
            <legend><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_10'); ?></legend>
            <?php echo $this->form->renderField('fileformat'); ?>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('fileformat_other'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->fileformat_other; ?>
                </div>
            </div>
        </div>
    </div>
</div>

