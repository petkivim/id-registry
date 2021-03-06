<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
 * @author 	Petteri Kivim�ki
 * @copyright	Copyright (C) 2015 Petteri Kivim�ki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * Publishers component helper.
 *
 * @since  1.6
 */
class MenuHelper extends JHelperContent {

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
                JText::_('COM_ISSNREGISTRY_SUBMENU_FORMS_NOT_HANDLED'), 'index.php?option=com_issnregistry&view=forms&filter_status=1', $vName == 'forms_not_handled'
        );
        JHtmlSidebar::addEntry(
                JText::_('COM_ISSNREGISTRY_SUBMENU_FORMS_NOT_NOTIFIED'), 'index.php?option=com_issnregistry&view=forms&filter_status=2', $vName == 'forms_not_notified'
        );
        JHtmlSidebar::addEntry(
                JText::_('COM_ISSNREGISTRY_SUBMENU_FORMS_COMPLETED'), 'index.php?option=com_issnregistry&view=forms&filter_status=3', $vName == 'forms_completed'
        );
        JHtmlSidebar::addEntry(
                JText::_('COM_ISSNREGISTRY_SUBMENU_FORMS_REJECTED'), 'index.php?option=com_issnregistry&view=forms&filter_status=4', $vName == 'forms_rejected'
        );
        JHtmlSidebar::addEntry(
                JText::_('COM_ISSNREGISTRY_SUBMENU_PUBLICATIONS'), 'index.php?option=com_issnregistry&view=publications&filter_status=3', $vName == 'publications'
        );
        JHtmlSidebar::addEntry(
                JText::_('COM_ISSNREGISTRY_SUBMENU_PUBLISHERS'), 'index.php?option=com_issnregistry&view=publishers', $vName == 'publishers'
        );
        JHtmlSidebar::addEntry(
                JText::_('COM_ISSNREGISTRY_SUBMENU_MESSAGES'), 'index.php?option=com_issnregistry&view=messages', $vName == 'messages'
        );
        /*
          JHtmlSidebar::addEntry(
          JText::_('COM_ISSNREGISTRY_SUBMENU_GROUP_MESSAGES'), 'index.php?option=com_issnregistry&view=groupmessages', $vName == 'groupmessages'
          );
         */
        JHtmlSidebar::addEntry(
                JText::_('COM_ISSNREGISTRY_SUBMENU_MESSAGE_TEMPLATES'), 'index.php?option=com_issnregistry&view=messagetemplates', $vName == 'messagetemplates'
        );
        JHtmlSidebar::addEntry(
                JText::_('COM_ISSNREGISTRY_SUBMENU_MESSAGE_TYPES'), 'index.php?option=com_issnregistry&view=messagetypes', $vName == 'messagetypes'
        );
        JHtmlSidebar::addEntry(
                JText::_('COM_ISSNREGISTRY_SUBMENU_ISSN_RANGES'), 'index.php?option=com_issnregistry&view=issnranges', $vName == 'issnranges'
        );
    }

}
