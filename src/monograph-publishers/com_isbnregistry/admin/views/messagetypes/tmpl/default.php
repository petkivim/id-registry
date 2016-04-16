<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 		Petteri Kivimäki
 * @copyright	Copyright (C) 2015 Petteri Kivimäki. All rights reserved.
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// Load styles
$document = JFactory::getDocument();
$document->addStyleSheet("components/com_isbnregistry/css/messagetype.css");

$document->addScriptDeclaration('
    Joomla.submitbutton = function(task) {
        if(task == "messagetypes.delete") {
            var response = confirm("' . JText::_('COM_ISBNREGISTRY_CONFIRM_DELETE') . '");
            if (response == true) {
                Joomla.submitform(task);
            }
        } else {
            Joomla.submitform(task);
        }
    };
');
?>
<form action="index.php?option=com_isbnregistry&view=messagetypes" method="post" id="adminForm" name="adminForm">
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th width="1%"><?php echo JText::_('COM_ISBNREGISTRY_MESSAGE_TYPES_NUM'); ?></th>
                    <th width="2%">
                        <?php echo JHtml::_('grid.checkall'); ?>
                    </th>
                    <th width="25%">
                        <?php echo JText::_('COM_ISBNREGISTRY_MESSAGE_TYPES_NAME'); ?>
                    </th>	
                    <th width="70%">
                        <?php echo JText::_('COM_ISBNREGISTRY_MESSAGE_TYPES_DESCRIPTION'); ?>
                    </th>						
                    <th width="2%">
                        <?php echo JText::_('COM_ISBNREGISTRY_MESSAGE_TYPES_ID'); ?>
                    </th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="5">
                        <?php echo $this->pagination->getListFooter(); ?>
                    </td>
                </tr>
            </tfoot>
            <tbody>
                <?php if (!empty($this->items)) : ?>
                    <?php
                    foreach ($this->items as $i => $row) :
                        $link = JRoute::_('index.php?option=com_isbnregistry&task=messagetype.edit&id=' . $row->id);
                        ?>
                        <tr>
                            <td><?php echo $this->pagination->getRowOffset($i); ?></td>
                            <td>
                                <?php echo JHtml::_('grid.id', $i, $row->id); ?>
                            </td>
                            <td>
                                <a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_ISBNREGISTRY_EDIT_MESSAGE_TYPE'); ?>">
                                    <?php echo $row->name; ?>
                                </a>
                            </td>   
                            <td>
                                <a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_ISBNREGISTRY_EDIT_MESSAGE_TYPE'); ?>">
                                    <?php echo (strlen($row->description) > 130 ? substr($row->description, 0, 130) . '...' : $row->description); ?>
                                </a>
                            </td> 							
                            <td align="center">
                                <?php echo $row->id; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <?php echo JHtml::_('form.token'); ?>
</form>

