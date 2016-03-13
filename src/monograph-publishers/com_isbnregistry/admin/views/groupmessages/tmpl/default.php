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
$document->addStyleSheet("components/com_isbnregistry/css/groupmessage.css");

$document->addScriptDeclaration('
    Joomla.submitbutton = function(task) {
        if(task == "groupmessages.delete") {
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
<form action="index.php?option=com_isbnregistry&view=groupmessages" method="post" id="adminForm" name="adminForm">
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th width="1%"><?php echo JText::_('COM_ISBNREGISTRY_GROUP_MESSAGES_NUM'); ?></th>
                    <th width="2%">
                        <?php echo JHtml::_('grid.checkall'); ?>
                    </th>
                    <th width="25%">
                        <?php echo JText::_('COM_ISBNREGISTRY_GROUP_MESSAGES_MESSAGE_TYPE_NAME'); ?>
                    </th>	
                    <th width="15%">
                        <?php echo JText::_('COM_ISBNREGISTRY_GROUP_MESSAGES_ISBN_CATEGORIES'); ?>
                    </th>						
                    <th width="15%">
                        <?php echo JText::_('COM_ISBNREGISTRY_GROUP_MESSAGES_ISMN_CATEGORIES'); ?>
                    </th>
                    <th width="15%">
                        <?php echo JText::_('COM_ISBNREGISTRY_GROUP_MESSAGES_PUBLISHER_COUNT'); ?>
                    </th>
                    <th width="20%">
                        <?php echo JText::_('COM_ISBNREGISTRY_GROUP_MESSAGES_CREATED'); ?>
                    </th>
                    <th width="2%">
                        <?php echo JText::_('COM_ISBNREGISTRY_GROUP_MESSAGES_ID'); ?>
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
                        $link = JRoute::_('index.php?option=com_isbnregistry&task=groupmessage.edit&id=' . $row->id);
                        ?>
                        <tr>
                            <td><?php echo $this->pagination->getRowOffset($i); ?></td>
                            <td>
                                <?php echo JHtml::_('grid.id', $i, $row->id); ?>
                            </td>
                            <td>
                                <a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_ISBNREGISTRY_EDIT_GROUP_MESSAGE'); ?>">
                                    <?php echo $row->name; ?>
                                </a>
                            </td> 
                            <td>
                                <?php echo $row->isbn_categories . ' (' . $row->isbn_publishers_count . ')'; ?>
                            </td> 
                            <td>
                                <?php echo $row->ismn_categories . ' (' . $row->ismn_publishers_count . ')'; ?>
                            </td> 	
                            <td>
                                <?php echo $row->publishers_count; ?>
                            </td> 
                            <td>
                                <?php echo JHtml::date($row->created, 'd.m.Y'); ?>
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

