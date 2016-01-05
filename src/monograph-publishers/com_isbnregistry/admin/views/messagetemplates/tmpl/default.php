<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 		Petteri Kivim�ki
 * @copyright	Copyright (C) 2015 Petteri Kivim�ki. All rights reserved.
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// Load styles
$document = JFactory::getDocument();
$document->addStyleSheet("components/com_isbnregistry/css/messagetemplate.css");
?>
<form action="index.php?option=com_isbnregistry&view=messagetemplates" method="post" id="adminForm" name="adminForm">
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th width="1%"><?php echo JText::_('COM_ISBNREGISTRY_MESSAGE_TEMPLATES_NUM'); ?></th>
                    <th width="2%">
                        <?php echo JHtml::_('grid.checkall'); ?>
                    </th>
                    <th width="25%">
                        <?php echo JText::_('COM_ISBNREGISTRY_MESSAGE_TEMPLATES_NAME'); ?>
                    </th>	
                    <th width="25%">
                        <?php echo JText::_('COM_ISBNREGISTRY_MESSAGE_TEMPLATES_MESSAGE_TYPE'); ?>
                    </th>		
                    <th width="25%">
                        <?php echo JText::_('COM_ISBNREGISTRY_MESSAGE_TEMPLATES_LANGUAGE'); ?>
                    </th>											
                    <th width="2%">
                        <?php echo JText::_('COM_ISBNREGISTRY_MESSAGE_TEMPLATES_ID'); ?>
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
                        $link = JRoute::_('index.php?option=com_isbnregistry&task=messagetemplate.edit&id=' . $row->id);
                        ?>
                        <tr>
                            <td><?php echo $this->pagination->getRowOffset($i); ?></td>
                            <td>
                                <?php echo JHtml::_('grid.id', $i, $row->id); ?>
                            </td>
							<td>
                                <a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_ISBNREGISTRY_EDIT_MESSAGE_TEMPLATE'); ?>">
                                    <?php echo $row->name; ?>
                                </a>
                            </td>   
							<td>
                                <?php echo $row->message_type_id; ?>
                            </td>  
							<td>
                                <?php echo $this->types[$row->lang_code]; ?>
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

