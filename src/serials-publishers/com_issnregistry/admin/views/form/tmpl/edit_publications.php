<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
 * @author      Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die('Restricted access');
?>
<table id="publications">
    <tr>
        <th class="publications_col_1"><?php echo JText::_('COM_ISSNREGISTRY_FORM_TAB_PUBLICATIONS_COL_1'); ?></th>
        <th class="publications_col_2"><?php echo JText::_('COM_ISSNREGISTRY_FORM_TAB_PUBLICATIONS_COL_2'); ?></th>
        <th class="publications_col_3"><?php echo JText::_('COM_ISSNREGISTRY_FORM_TAB_PUBLICATIONS_COL_3'); ?></th>
        <th class="publications_col_4"><?php echo JText::_('COM_ISSNREGISTRY_FORM_TAB_PUBLICATIONS_COL_4'); ?></th>
        <th class="publications_col_5"><?php echo JText::_('COM_ISSNREGISTRY_FORM_TAB_PUBLICATIONS_COL_5'); ?></th>
    </tr>
    <?php
    if (sizeof($this->publications) == 0) {
        echo '<tr><td class="info_row">' . JText::_('COM_ISSNREGISTRY_FORM_TAB_PUBLICATIONS_NO_RESULTS') . '<td></tr>';
    } else {
        foreach ($this->publications as $publication) {
            echo '<tr>';
            echo '<td class="publications_col_1">' . $publication->title . '</td>';
            echo '<td class="publications_col_2">' . $publication->issn . '</td>';
            echo '<td class="publications_col_3">' . (empty($publication->language) ? '' : JText::_('COM_ISSNREGISTRY_PUBLICATION_LANGUAGE_' . $publication->language)) . '</td>';
            echo '<td class="publications_col_4">' . (empty($publication->medium) ? '' : JText::_('COM_ISSNREGISTRY_PUBLICATION_MEDIUM_' . $publication->medium)) . '</td>';
            echo '<td class="publications_col_5">' . JText::_('COM_ISSNREGISTRY_FORM_TAB_PUBLICATIONS_SHOW') . '</td>';
            echo '</tr>';
        }
    }
    echo '<tr><td  class="info_row">' . JText::_('COM_ISSNREGISTRY_FORM_TAB_PUBLICATIONS_ADD_NEW') . ' <span class="icon-new"></span><td></tr>';
    ?>
</table>