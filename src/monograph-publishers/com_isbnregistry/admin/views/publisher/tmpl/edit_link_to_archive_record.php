<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 		Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die('Restricted access');
// Has archive record?
$hasArchive = strcmp($this->item->created_by, 'WWW') == 0 ? true : false;
?>

<div class = "control-group">
    <div class = "control-label">
        <?php echo JText::_('COM_ISBNREGISTRY_PUBLISHER_FIELD_ARCHIVE_RECORD_LABEL'); ?>
    </div>
    <div class="controls">
        <?php
        if ($hasArchive) {
            $link = '<a href="' . JRoute::_('index.php?option=com_isbnregistry&task=publisherarchive.getRecord&publisherId=' . $this->item->id);
            $link .= '" class="modal" rel="{size: {x: 1200, y: 600}, handler:\'iframe\'}">' . JText::_('COM_ISBNREGISTRY_PUBLISHER_FIELD_ARHICE_RECORD_LINK') . '</a>';
            echo $link;
        } else {
            echo '-';
        }
        ?>
    </div>
</div>


