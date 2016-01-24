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
$document->addScript("components/com_isbnregistry/scripts/publisher_print.js");
// Add css
$document->addStyleSheet("components/com_isbnregistry/css/publisher.css");
?>

<button name="print" id="print"><?php echo JText::_('COM_ISBNREGISTRY_PUBLISHER_BUTTON_PRINT'); ?></button>
<div class="form-horizontal">
    <div class="row-fluid form-horizontal-desktop">
        <div class="span6">              
            <legend><?php echo JText::_('COM_ISBNREGISTRY_PUBLISHER_TAB_BASIC'); ?></legend>	
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
                    <?php echo $this->form->getLabel('other_names'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->other_names; ?>
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
            <legend><?php echo JText::_('COM_ISBNREGISTRY_PUBLISHER_TAB_ADDITIONAL'); ?></legend>	
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('question_1'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->question_1; ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('question_2'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->question_2; ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('question_3'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->question_3; ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('question_4'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->question_4; ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('question_5'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->question_5; ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('question_6'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->question_6; ?>
                </div>
            </div>
            <?php echo $this->form->renderField('question_7'); ?>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('question_8'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->question_8; ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('confirmation'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->confirmation; ?>
                </div>
            </div>
        </div>
        <div class="span6">
            <legend><?php echo JText::_('COM_ISBNREGISTRY_PUBLISHER_LABEL_CONTACT_INFO'); ?></legend>	
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
            <div class="control-group">
                <div class="control-label">
                    <?php echo $this->form->getLabel('www'); ?>
                </div>
                <div class="controls">
                    <?php echo $this->item->www; ?>
                </div>
            </div>           
        </div>
    </div>
</div>
