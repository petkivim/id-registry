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
</table>
<span id="label_active" style=" visibility:hidden"><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_ISBN_RANGES_ACTIVE'); ?></span>
<span id="label_activate" style=" visibility:hidden"><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_ISBN_RANGES_ACTIVATE'); ?></span>
<span id="label_closed" style=" visibility:hidden"><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATION_TAB_PUBLISHER_ISBN_RANGES_CLOSED'); ?></span>
<span id="label_confirm_delete" style=" visibility:hidden"><?php echo JText::_('COM_ISBNREGISTRY_PUBLISHER_CONFIRM_DELETE_IDENTIFIER'); ?></span>
<span id="label_confirm_cancel" style=" visibility:hidden"><?php echo JText::_('COM_ISBNREGISTRY_PUBLISHER_CONFIRM_CANCEL_IDENTIFIER'); ?></span>
