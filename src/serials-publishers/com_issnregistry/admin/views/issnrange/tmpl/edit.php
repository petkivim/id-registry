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

// Add scripts
$document = JFactory::getDocument();
$document->addScript('components/com_issnregistry/scripts/issnrange_validators.js');
$document->addScript("components/com_issnregistry/scripts/issnrange.js");
// Add css
$document->addStyleSheet("components/com_issnregistry/css/issnrange.css");

// This fix is needed for JToolBar
JFactory::getDocument()->addScriptDeclaration('
    Joomla.submitbutton = function(task)
    {
        if (task == "issnrange.cancel" || document.formvalidator.isValid(document.getElementById("adminForm")))
        {
            Joomla.submitform(task, document.getElementById("adminForm"));
        }
    };
');
?>
<form action="<?php echo JRoute::_('index.php?option=com_issnregistry&layout=edit&id=' . (int) $this->item->id); ?>"
      method="post" name="adminForm" id="adminForm" class="form-validate">
    <div class="form-horizontal">
        <div class="row-fluid form-horizontal-desktop">
            <div class="span6">  
                <?php
                // If this is not a new item, it can't be modified
                if ($this->item->id != 0) {
                    $this->form->setFieldAttribute('block', 'readonly', 'true');
                    $this->form->setFieldAttribute('range_begin', 'readonly', 'true');
                    $this->form->setFieldAttribute('range_begin', 'class', 'inputbox validate-rangebeginedit');
                    $this->form->setFieldAttribute('range_end', 'readonly', 'true');
                    $this->form->setFieldAttribute('range_end', 'class', 'inputbox validate-rangeendedit');
                }
                if ($this->item->is_closed) {
                    $this->form->setFieldAttribute('is_active', 'readonly', 'true');
                }
                ?>        
                <?php echo $this->form->renderField('id'); ?>
                <?php echo $this->form->renderField('block'); ?>
                <?php echo $this->form->renderField('range_begin'); ?>
                <?php echo $this->form->renderField('range_end'); ?>
                <?php echo $this->form->renderField('is_active'); ?>
                <?php echo $this->form->renderField('free'); ?>
                <?php echo $this->form->renderField('taken'); ?>
                <?php echo $this->form->renderField('next'); ?>
            </div>
            <div class="span6">
                <?php echo $this->form->renderField('created'); ?>
                <?php echo $this->form->renderField('created_by'); ?>
                <?php echo $this->form->renderField('modified'); ?>
                <?php echo $this->form->renderField('modified_by'); ?>
            </div>
        </div>
    </div>
    <input type="hidden" name="task" value="issnrange.edit" />
    <?php echo JHtml::_('form.token'); ?>
</form>
