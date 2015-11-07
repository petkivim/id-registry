<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<form action="index.php?option=com_isbnregistry&view=publishers" method="post" id="adminForm" name="adminForm">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th width="1%"><?php echo JText::_('COM_ISBNREGISTRY_PUBLISHERS_NUM'); ?></th>
                <th width="2%">
                    <?php echo JHtml::_('grid.checkall'); ?>
                </th>
                <th width="15%">
                    <?php echo JText::_('COM_ISBNREGISTRY_PUBLISHERS_FIRST_NAME'); ?>
                </th>
                <th width="15%">
                    <?php echo JText::_('COM_ISBNREGISTRY_PUBLISHERS_LAST_NAME'); ?>
                </th>
                <th width="15%">
                    <?php echo JText::_('COM_ISBNREGISTRY_PUBLISHERS_ORGANIZATION'); ?>
                </th>
                <th width="5%">
                    <?php echo JText::_('COM_ISBNREGISTRY_PUBLISHERS_SOURCE'); ?>
                </th>
                <th width="15%">
                    <?php echo JText::_('COM_ISBNREGISTRY_PUBLISHERS_RECEIVED'); ?>
                </th>	
                <th width="5%">
                    <?php echo JText::_('COM_ISBNREGISTRY_PUBLISHERS_CONFIRMED'); ?>
                </th>	
                <th width="5%">
                    <?php echo JText::_('COM_ISBNREGISTRY_PUBLISHERS_CONFIRMATION_STATUS'); ?>
                </th>
                <th width="10%">
                    <?php echo JText::_('COM_ISBNREGISTRY_PUBLISHERS_AMOUNT'); ?>
                </th>	
                <th width="5%">
                    <?php echo JText::_('COM_ISBNREGISTRY_PUBLISHERS_PUBLIC'); ?>
                </th>				
                <th width="5%">
                    <?php echo JText::_('COM_ISBNREGISTRY_PUBLISHERS_PUBLISHED'); ?>
                </th>
                <th width="2%">
                    <?php echo JText::_('COM_ISBNREGISTRY_PUBLISHERS_ID'); ?>
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
                    $link = JRoute::_('index.php?option=com_isbnregistry&task=publisher.edit&id=' . $row->id);
                    ?>
                    <tr>
                        <td><?php echo $this->pagination->getRowOffset($i); ?></td>
                        <td>
                            <?php echo JHtml::_('grid.id', $i, $row->id); ?>
                        </td>
                        <td>
                            <a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_ISBNREGISTRY_EDIT_PUBLISHER'); ?>">
                                <?php echo $row->first_name; ?>
                            </a>
                        </td>
                        <td>
                            <a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_ISBNREGISTRY_EDIT_PUBLISHER'); ?>">
                                <?php echo $row->last_name; ?>
                            </a>
                        </td>
                        <td>
                            <a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_ISBNREGISTRY_EDIT_PUBLISHER'); ?>">
                                <?php echo $row->organization; ?>
                            </a>
                        </td>						
                        <td>
                            <?php echo $row->data_source; ?>
                        </td>	
                        <td>
                            <?php echo $row->donation_timestamp; ?>
                        </td>
                        <td>
                            <?php echo ($row->confirmation ? JText::_('COM_ISBNREGISTRY_PUBLISHER_FIELD_YES') : JText::_('COM_ISBNREGISTRY_PUBLISHER_FIELD_NO')); ?>
                        </td>	
                        <td>
                            <?php echo $row->confirmation_status; ?>
                        </td>						
                        <td>
                            <?php echo $row->amount; ?> &euro;
                        </td>
                        <td>
                            <?php echo ($row->permission ? JText::_('COM_ISBNREGISTRY_PUBLISHER_FIELD_YES') : JText::_('COM_ISBNREGISTRY_PUBLISHER_FIELD_NO')); ?>
                        </td>						
                        <td align="center">
                            <?php echo JHtml::_('jgrid.published', $row->published, $i, 'publishers.', true, 'cb'); ?>
                        </td>
                        <td align="center">
                            <?php echo $row->id; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <?php echo JHtml::_('form.token'); ?>
</form>

