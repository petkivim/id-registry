<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
 * @author 	Petteri Kivim�ki
 * @copyright	Copyright (C) 2016 Petteri Kivim�ki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die('Restricted access');
// Has archive record?
$hasArchive = strcmp($this->item->created_by, 'WWW') == 0 ? true : false;
?>

<div class = "control-group">
    <div class = "control-label">
        <?php echo JText::_('COM_ISSNREGISTRY_PUBLICATION_FIELD_ARCHIVE_RECORD_LABEL'); ?>
    </div>
    <div class="controls">
        <?php
        if ($hasArchive) {
            $link = '<a href="' . JRoute::_('index.php?option=com_issnregistry&task=publicationarchive.getRecord&publicationId=' . $this->item->id . '&' . JSession::getFormToken() . '=1');
            $link .= '" class="modal" rel="{size: {x: 1200, y: 600}, handler:\'iframe\'}">' . JText::_('COM_ISSNREGISTRY_PUBLICATION_FIELD_ARHICE_RECORD_LINK') . '</a>';
            echo $link;
        } else {
            echo '-';
        }
        ?>
    </div>
</div>


