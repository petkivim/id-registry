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

// Add scripts
$document = JFactory::getDocument();
$document->addScript("components/com_isbnregistry/scripts/message.js");

?>
<button id="jform_close" class="btn btn-small"><span class="icon-cancel"></span><?php echo JText::_('COM_ISBNREGISTRY_MESSAGE_BUTTON_CLOSE'); ?></button>