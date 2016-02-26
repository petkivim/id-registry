<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2015 Petteri Kivimäki. All rights reserved.
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
        /*
          JHtmlSidebar::addEntry(
          JText::_('COM_ISSNREGISTRY_SUBMENU_PUBLISHERS'), 'index.php?option=com_issnregistry&view=publishers', $vName == 'publishers'
          );
          JHtmlSidebar::addEntry(
          JText::_('COM_ISSNREGISTRY_SUBMENU_PUBLICATIONS_RECEIVED'), 'index.php?option=com_issnregistry&view=publications&filter_status=1', $vName == 'publications_received'
          );
          JHtmlSidebar::addEntry(
          JText::_('COM_ISSNREGISTRY_SUBMENU_PUBLICATIONS_ON_PROCESS'), 'index.php?option=com_issnregistry&view=publications&filter_status=2', $vName == 'publications_on_process'
          );
          JHtmlSidebar::addEntry(
          JText::_('COM_ISSNREGISTRY_SUBMENU_MESSAGES'), 'index.php?option=com_issnregistry&view=messages', $vName == 'messages'
          );
          JHtmlSidebar::addEntry(
          JText::_('COM_ISSNREGISTRY_SUBMENU_GROUP_MESSAGES'), 'index.php?option=com_issnregistry&view=groupmessages', $vName == 'groupmessages'
          );
          JHtmlSidebar::addEntry(
          JText::_('COM_ISSNREGISTRY_SUBMENU_MESSAGE_TEMPLATES'), 'index.php?option=com_issnregistry&view=messagetemplates', $vName == 'messagetemplates'
          );
          JHtmlSidebar::addEntry(
          JText::_('COM_ISSNREGISTRY_SUBMENU_MESSAGE_TYPES'), 'index.php?option=com_issnregistry&view=messagetypes', $vName == 'messagetypes'
          );
         */
        JHtmlSidebar::addEntry(
                JText::_('COM_ISSNREGISTRY_SUBMENU_FORMS'), 'index.php?option=com_issnregistry&view=forms', $vName == 'forms'
        );
        JHtmlSidebar::addEntry(
                JText::_('COM_ISSNREGISTRY_SUBMENU_PUBLICATIONS'), 'index.php?option=com_issnregistry&view=publications', $vName == 'publications'
        );
        JHtmlSidebar::addEntry(
                JText::_('COM_ISSNREGISTRY_SUBMENU_PUBLISHERS'), 'index.php?option=com_issnregistry&view=publishers', $vName == 'publishers'
        );
        JHtmlSidebar::addEntry(
                JText::_('COM_ISSNREGISTRY_SUBMENU_ISSN_RANGES'), 'index.php?option=com_issnregistry&view=issnranges', $vName == 'issnranges'
        );
    }

}
