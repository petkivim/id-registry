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


$link = '<a href="' . JRoute::_('index.php?option=com_isbnregistry&view=identifierbatches&tmpl=component&layout=embed&type=isbn&publisherId=' . ($this->item->id > 0 ? $this->item->id : -1));
$link .= '" class="modal" rel="{size: {x: 1200, y: 600}, handler:\'iframe\'}">' . JText::_('COM_ISBNREGISTRY_PUBLISHER_FIELD_SHOW_IDENTIFIER_HISTORY') . '</a>';
echo $link;
?>



