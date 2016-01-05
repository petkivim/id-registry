<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_donation
 * @author 		Petteri Kivimäki
 * @copyright	Copyright (C) 2015 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * Publishers component helper.
 *
 * @since  1.6
 */
class PublishersHelper extends JHelperContent {

    /**
     * Configure the Linkbar.
     *
     * @param   string  $vName  The name of the active view.
     *
     * @return  void
     *
     * @since   1.6
     */
    public static function addSubmenu($vName) {
        JHtmlSidebar::addEntry(
                JText::_('COM_ISBNREGISTRY_SUBMENU_PUBLISHERS'), 'index.php?option=com_isbnregistry&view=publishers', $vName == 'publishers'
        );
        JHtmlSidebar::addEntry(
                JText::_('COM_ISBNREGISTRY_SUBMENU_PUBLICATIONS'), 'index.php?option=com_isbnregistry&view=publications', $vName == 'publications'
        );
        JHtmlSidebar::addEntry(
                JText::_('COM_ISBNREGISTRY_SUBMENU_ISBN_RANGES'), 'index.php?option=com_isbnregistry&view=isbnranges', $vName == 'isbnranges'
        );		
        JHtmlSidebar::addEntry(
                JText::_('COM_ISBNREGISTRY_SUBMENU_ISMN_RANGES'), 'index.php?option=com_isbnregistry&view=ismnranges', $vName == 'ismnranges'
        );	
        JHtmlSidebar::addEntry(
                JText::_('COM_ISBNREGISTRY_SUBMENU_MESSAGE_TEMPLATES'), 'index.php?option=com_isbnregistry&view=messagetemplates', $vName == 'messagetemplates'
        );			
        JHtmlSidebar::addEntry(
                JText::_('COM_ISBNREGISTRY_SUBMENU_MESSAGE_TYPES'), 'index.php?option=com_isbnregistry&view=messagetypes', $vName == 'messagetypes'
        );			
    }

}
