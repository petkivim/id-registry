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
JHtml::_('formbehavior.chosen');

// Add scripts
$document = JFactory::getDocument();
$document->addScript("components/com_issnregistry/scripts/publisher_print.js");
// Add css
$document->addStyleSheet("components/com_issnregistry/css/publisher.css");
$this->form->setFieldAttribute( 'lang_code', 'readonly', 'true' );
?>

<button name="print" id="print"><?php echo JText::_('COM_ISSNREGISTRY_PUBLISHER_BUTTON_PRINT'); ?></button>
<div class="form-horizontal">
    <div class="row-fluid form-horizontal-desktop">
        <div class="span6">              
            <legend><?php echo JText::_('COM_ISSNREGISTRY_PUBLISHER_TAB_BASIC'); ?></legend>	
            <?php echo $this->form->renderField('id'); ?>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('official_name'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->official_name; ?>
                </div>
            </div>
            <?php echo $this->form->renderField('contact_person'); ?>
            <?php echo $this->form->renderField('lang_code'); ?>           
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
        </div>
        <div class="span6">
            <legend><?php echo JText::_('COM_ISSNREGISTRY_PUBLISHER_LABEL_CONTACT_INFO'); ?></legend>	
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
                    <?php echo $this->form->getLabel('email_common'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->email; ?>
                </div>
            </div>           
        </div>
    </div>
</div>
