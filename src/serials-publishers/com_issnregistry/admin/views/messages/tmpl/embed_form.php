<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
 * @author 	Petteri Kivim�ki
 * @copyright	Copyright (C) 2016 Petteri Kivim�ki. All rights reserved.
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<form action="index.php?option=com_issnregistry&view=messages&formId=<?php echo JFactory::getApplication()->input->getInt('formId', 0); ?>&tmpl=component&layout=embed_form" method="post" id="adminForm" name="adminForm">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th width="1%"><?php echo JText::_('COM_ISSNREGISTRY_MESSAGES_NUM'); ?></th>
                <th width="25%">
                    <?php echo JText::_('COM_ISSNREGISTRY_MESSAGES_RECIPIENT'); ?>
                </th>	
                <th width="25%">
                    <?php echo JText::_('COM_ISSNREGISTRY_MESSAGES_SUBJECT'); ?>
                </th>		
                <th width="25%">
                    <?php echo JText::_('COM_ISSNREGISTRY_MESSAGES_SENT'); ?>
                </th>											
                <th width="2%">
                    <?php echo JText::_('COM_ISSNREGISTRY_MESSAGES_ID'); ?>
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
                    $link = JRoute::_('index.php?option=com_issnregistry&view=message&id=' . $row->id);
                    ?>
                    <tr>
                        <td><?php echo $this->pagination->getRowOffset($i); ?></td>
                        <td>
                            <a target="new" href="<?php echo $link; ?>" title="<?php echo JText::_('COM_ISSNREGISTRY_EDIT_MESSAGE'); ?>">
                                <?php echo $row->recipient; ?>
                            </a>
                        </td>   
                        <td>
                            <?php echo $row->subject; ?>
                        </td>  
                        <td>
                            <?php echo JHtml::date($row->sent, 'd.m.Y'); ?>
                        </td>  							
                        <td align="center">
                            <?php echo $row->id; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td></td>
                    <td>
                        <?php echo JText::_('COM_ISSNREGISTRY_MESSAGES_NONE'); ?>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <?php echo JHtml::_('form.token'); ?>
</form>
