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

// Add scripts
$document = JFactory::getDocument();
$document->addScript('components/com_isbnregistry/scripts/isbnrange_validators.js');
$document->addScript("components/com_isbnregistry/scripts/isbnrange.js");
// Add css
$document->addStyleSheet("components/com_isbnregistry/css/isbnrange.css");

// This fix is needed for JToolBar
JFactory::getDocument()->addScriptDeclaration('
    Joomla.submitbutton = function(task)
    {
        if (task == "isbnrange.cancel" || document.formvalidator.isValid(document.getElementById("adminForm")))
        {
            Joomla.submitform(task, document.getElementById("adminForm"));
        }
    };
');
?>
<form action="<?php echo JRoute::_('index.php?option=com_isbnregistry&layout=edit&id=' . (int) $this->item->id); ?>"
      method="post" name="adminForm" id="adminForm" class="form-validate">
    <div class="form-horizontal">
        <div class="row-fluid form-horizontal-desktop">
            <div class="span6">  
				<?php
				// If even one ISBN has been used, this item can't be modified
				if(strcmp($this->item->range_begin, $this->item->next) != 0) {
					$this->form->setFieldAttribute( 'prefix', 'readonly', 'true' );
					$this->form->setFieldAttribute( 'lang_group', 'readonly', 'true' );
					$this->form->setFieldAttribute( 'range_begin', 'readonly', 'true' );
					$this->form->setFieldAttribute( 'range_end', 'readonly', 'true' );
				}
				?>            
                <?php echo $this->form->renderField('prefix'); ?>
                <?php echo $this->form->renderField('lang_group'); ?>
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
    <input type="hidden" name="task" value="isbnrange.edit" />
    <?php echo JHtml::_('form.token'); ?>
</form>
