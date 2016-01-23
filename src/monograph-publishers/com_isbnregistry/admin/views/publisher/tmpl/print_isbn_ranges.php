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
    <tr id="isbn_ranges_header">
        <th class="isbn_range_col_1"><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_ISBN_RANGES_COL_1'); ?></th>
        <th class="isbn_range_col_2"><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_ISBN_RANGES_COL_2'); ?></th>
        <th class="isbn_range_col_3"><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_ISBN_RANGES_COL_3'); ?></th>
        <th class="isbn_range_col_4"><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_ISBN_RANGES_COL_4'); ?></th>
        <th class="isbn_range_col_5"><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_ISBN_RANGES_COL_5'); ?></th>
    </tr>
    <?php if (!empty($this->isbns)) : ?>
        <?php foreach ($this->isbns as $isbn) { ?>
            <?php
            $state = '';
            if ($isbn->is_active) {
                $state = JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_ISBN_RANGES_ACTIVE');
            } else if ($isbn->is_closed) {
                $state = JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_ISBN_RANGES_CLOSED');
            }
            ?>
            <tr class="isbn_range_row">
                <td class="isbn_range_col_1"><?php echo $isbn->publisher_identifier; ?></td>
                <td class="isbn_range_col_2"><?php echo JHtml::date($isbn->created, 'd.m.Y'); ?></td>
                <td class="isbn_range_col_3"><?php echo $isbn->free; ?></td>
                <td class="isbn_range_col_4"><?php echo $isbn->next; ?></td>
                <td class="isbn_range_col_5"><?php echo $state; ?></td>
            </tr>
        <?php } ?>
    <?php endif; ?>
</table>