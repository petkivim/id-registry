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
?>
<table>
    <tr id="ismn_ranges_header">
        <th class="ismn_range_col_1"><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_ISBN_RANGES_COL_1'); ?></th>
        <th class="ismn_range_col_2"><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_ISBN_RANGES_COL_2'); ?></th>
        <th class="ismn_range_col_3"><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_ISBN_RANGES_COL_3'); ?></th>
        <th class="ismn_range_col_4"><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_ISBN_RANGES_COL_4'); ?></th>
        <th class="ismn_range_col_5"><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_ISBN_RANGES_COL_5'); ?></th>
    </tr>
    <?php if (!empty($this->ismns)) : ?>
        <?php foreach ($this->ismns as $ismn) { ?>
            <?php
            $state = '';
            if ($ismn->is_active) {
                $state = JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_ISBN_RANGES_ACTIVE');
            } else if ($ismn->is_closed) {
                $state = JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_ISBN_RANGES_CLOSED');
            }
            ?>
            <tr class="ismn_range_row">
                <td class="isbn_range_col_1"><?php echo $ismn->publisher_identifier; ?></td>
                <td class="isbn_range_col_2"><?php echo JHtml::date($ismn->created, 'd.m.Y'); ?></td>
                <td class="isbn_range_col_3"><?php echo $ismn->free; ?></td>
                <td class="isbn_range_col_4"><?php echo $ismn->next; ?></td>
                <td class="isbn_range_col_5"><?php echo $state; ?></td> 
            </tr>
        <?php } ?>
    <?php endif; ?>
</table>