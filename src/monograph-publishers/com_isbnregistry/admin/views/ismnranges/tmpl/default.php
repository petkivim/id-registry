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
$document->addStyleSheet("components/com_isbnregistry/css/ismnrange.css");

$document->addScriptDeclaration('
    Joomla.submitbutton = function(task) {
        if(task == "ismnranges.delete") {
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
<form action="index.php?option=com_isbnregistry&view=ismnranges" method="post" id="adminForm" name="adminForm">
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th width="1%"><?php echo JText::_('COM_ISBNREGISTRY_ISMN_RANGES_NUM'); ?></th>
                    <th width="2%">
                        <?php echo JHtml::_('grid.checkall'); ?>
                    </th>
                    <th width="15%">
                        <?php echo JText::_('COM_ISBNREGISTRY_ISMN_RANGES_PREFIX'); ?>
                    </th>
                    <th width="15%">
                        <?php echo JText::_('COM_ISBNREGISTRY_ISMN_RANGES_RANGE_BEGIN'); ?>
                    </th>
                    <th width="15%">
                        <?php echo JText::_('COM_ISBNREGISTRY_ISMN_RANGES_RANGE_END'); ?>
                    </th>
                    <th width="15%">
                        <?php echo JText::_('COM_ISBNREGISTRY_ISMN_RANGES_RANGE_FREE'); ?>
                    </th>	
                    <th width="15%">
                        <?php echo JText::_('COM_ISBNREGISTRY_ISMN_RANGES_RANGE_IS_ACTIVE'); ?>
                    </th>						
                    <th width="2%">
                        <?php echo JText::_('COM_ISBNREGISTRY_ISMN_RANGES_ID'); ?>
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
                        $link = JRoute::_('index.php?option=com_isbnregistry&task=ismnrange.edit&id=' . $row->id);
                        ?>
                        <tr>
                            <td><?php echo $this->pagination->getRowOffset($i); ?></td>
                            <td>
                                <?php echo JHtml::_('grid.id', $i, $row->id); ?>
                            </td>
                            <td>
                                <a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_ISBNREGISTRY_EDIT_ISMN_RANGE'); ?>">
                                    <?php echo $row->prefix; ?>
                                </a>
                            </td>   	
                            <td>
                                <a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_ISBNREGISTRY_EDIT_ISMN_RANGE'); ?>">
                                    <?php echo $row->range_begin; ?>
                                </a>
                            </td> 
                            <td>
                                <a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_ISBNREGISTRY_EDIT_ISMN_RANGE'); ?>">
                                    <?php echo $row->range_end; ?>
                                </a>
                            </td> 
                            <td>
                                <a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_ISBNREGISTRY_EDIT_ISMN_RANGE'); ?>">
                                    <?php echo ($row->free + $row->canceled); ?>
                                </a>
                            </td> 		
                            <td>
                                <a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_ISBNREGISTRY_EDIT_ISMN_RANGE'); ?>">
                                    <?php echo ($row->is_active ? JText::_('JYES') : JText::_('JNO')); ?>
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

