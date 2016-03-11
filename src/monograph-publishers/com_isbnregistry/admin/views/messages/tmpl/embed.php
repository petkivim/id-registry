<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2015 Petteri Kivimäki. All rights reserved.
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<form action="index.php?option=com_isbnregistry&view=messages&publisherId=<?php echo JFactory::getApplication()->input->getInt('publisherId', 0); ?>&tmpl=component&layout=embed" method="post" id="adminForm" name="adminForm">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th width="1%"><?php echo JText::_('COM_ISBNREGISTRY_MESSAGES_NUM'); ?></th>
                <th width="25%">
                    <?php echo JText::_('COM_ISBNREGISTRY_MESSAGES_SUBJECT'); ?>
                </th>	
                <th width="25%">
                    <?php echo JText::_('COM_ISBNREGISTRY_MESSAGES_TITLE'); ?>
                </th>		
                <th width="25%">
                    <?php echo JText::_('COM_ISBNREGISTRY_MESSAGES_SENT'); ?>
                </th>											
                <th width="2%">
                    <?php echo JText::_('COM_ISBNREGISTRY_MESSAGES_ID'); ?>
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
                    $link = JRoute::_('index.php?option=com_isbnregistry&view=message&id=' . $row->id);
                    ?>
                    <tr>
                        <td><?php echo $this->pagination->getRowOffset($i); ?></td>
                        <td>
                            <a target="new" href="<?php echo $link; ?>" title="<?php echo JText::_('COM_ISBNREGISTRY_EDIT_MESSAGE'); ?>">
                                <?php echo $row->subject; ?>
                            </a>
                        </td>   
                        <td>
                            <?php echo!empty($row->title) ? $row->title : '-'; ?>
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
                        <?php echo JText::_('COM_ISBNREGISTRY_MESSAGES_NONE'); ?>
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
