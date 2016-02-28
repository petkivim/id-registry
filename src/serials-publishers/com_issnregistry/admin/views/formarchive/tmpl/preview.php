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
$document->addScript("components/com_issnregistry/scripts/form_print.js");
// Add css
$document->addStyleSheet("components/com_issnregistry/css/form.css");
$this->form->setFieldAttribute('publisher_id', 'readonly', 'true');
$this->form->setFieldAttribute('status', 'readonly', 'true');
?>				

<button name="print" id="print"><?php echo JText::_('COM_ISSNREGISTRY_FORM_BUTTON_PRINT'); ?></button>
<div class="form-horizontal">
    <div class="row-fluid form-horizontal-desktop">
        <div class="span6">              
            <legend><?php echo JText::_('COM_ISSNREGISTRY_FORM_TAB_PUBLISHER_SUBTITLE_1'); ?></legend>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('publisher'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->publisher; ?>
                </div>
            </div>          
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('contact_person'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->contact_person; ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('lang_code'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->lang_code; ?>
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
        </div>
        <div class="span6">
            <legend><?php echo JText::_('COM_ISSNREGISTRY_FORM_TAB_PUBLISHER_SUBTITLE_3'); ?></legend>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('publication_count'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->publication_count; ?>
                </div>
            </div>
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
    </div>
</div>

