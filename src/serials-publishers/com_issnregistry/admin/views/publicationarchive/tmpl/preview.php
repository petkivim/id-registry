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
JHtml::_('formbehavior.chosen');

// Add scripts
$document = JFactory::getDocument();
$document->addScript("components/com_issnregistry/scripts/publication_print.js");
// Add css
$document->addStyleSheet("components/com_issnregistry/css/publication.css");
?>				

<button name="print" id="print"><?php echo JText::_('COM_ISSNREGISTRY_PUBLICATION_BUTTON_PRINT'); ?></button>
<div class="form-horizontal">
    <div class="row-fluid form-horizontal-desktop">
        <div class="span6">              
            <legend><?php echo JText::_('COM_ISSNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_1'); ?></legend>
            <?php echo $this->form->renderField('id'); ?>
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
                    <?php echo $this->form->getLabel('place_of_publication'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->place_of_publication; ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('printer'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->printer; ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('issued_from_year'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->issued_from_year; ?>
                </div>
            </div>  
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('issued_from_number'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->issued_from_number; ?>
                </div>
            </div>    
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('frequency'); ?>
                </div>
                <div class="controls">
                    <?php echo JText::_('COM_ISSNREGISTRY_PUBLICATION_FIELD_FREQUENCY_' . strtoupper($this->item->frequency)); ?>
                </div>
            </div> 
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('frequency_other'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->frequency_other; ?>
                </div>
            </div> 
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('language'); ?>
                </div>
                <div class="controls">
                    <?php echo JText::_('COM_ISSNREGISTRY_PUBLICATION_FIELD_LANGUAGE_' . $this->item->language); ?>
                </div>
            </div>             
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('publication_type'); ?>
                </div>
                <div class="controls">
                    <?php echo JText::_('COM_ISSNREGISTRY_PUBLICATION_FIELD_PUBLICATION_TYPE_' . $this->item->publication_type); ?>
                </div>
            </div>            
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('publication_type_other'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->publication_type_other; ?>
                </div>
            </div>           
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('medium'); ?>
                </div>
                <div class="controls">
                    <?php echo JText::_('COM_ISSNREGISTRY_PUBLICATION_FIELD_MEDIUM_' . $this->item->medium); ?>
                </div>
            </div>    
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('medium_other'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->medium_other; ?>
                </div>
            </div>                
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('url'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->url; ?>
                </div>
            </div>

            <legend><?php echo JText::_('COM_ISSNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_3'); ?></legend>
            <?php echo $this->form->renderField('created'); ?>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('created_by'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->created_by; ?>
                </div>
            </div>
        </div>
        <div class="span6">
            <legend><?php echo JText::_('COM_ISSNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_4'); ?></legend>
            <?php echo $this->form->renderField('previous'); ?>

            <legend><?php echo JText::_('COM_ISSNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_5'); ?></legend>
            <?php echo $this->form->renderField('main_series'); ?>

            <legend><?php echo JText::_('COM_ISSNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_6'); ?></legend>
            <?php echo $this->form->renderField('subseries'); ?>

            <legend><?php echo JText::_('COM_ISSNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_7'); ?></legend>
            <?php echo $this->form->renderField('another_medium'); ?> 

            <legend><?php echo JText::_('COM_ISSNREGISTRY_PUBLICATION_TAB_PUBLISHER_SUBTITLE_8'); ?></legend>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('additional_info'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->additional_info; ?>
                </div>
            </div>
        </div>
    </div>
</div>

