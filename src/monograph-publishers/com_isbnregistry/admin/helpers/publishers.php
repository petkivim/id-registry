<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
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
                JText::_('COM_ISBNREGISTRY_SUBMENU_PUBLICATIONS_RECEIVED'), 'index.php?option=com_isbnregistry&view=publications&filter_status=1', $vName == 'publications_received'
        );
        JHtmlSidebar::addEntry(
                JText::_('COM_ISBNREGISTRY_SUBMENU_PUBLICATIONS_ON_PROCESS'), 'index.php?option=com_isbnregistry&view=publications&filter_status=2', $vName == 'publications_on_process'
        );		
        JHtmlSidebar::addEntry(
                JText::_('COM_ISBNREGISTRY_SUBMENU_MESSAGES'), 'index.php?option=com_isbnregistry&view=messages', $vName == 'messages'
        );
        JHtmlSidebar::addEntry(
                JText::_('COM_ISBNREGISTRY_SUBMENU_GROUP_MESSAGES'), 'index.php?option=com_isbnregistry&view=groupmessages', $vName == 'groupmessages'
        );		
        JHtmlSidebar::addEntry(
                JText::_('COM_ISBNREGISTRY_SUBMENU_MESSAGE_TEMPLATES'), 'index.php?option=com_isbnregistry&view=messagetemplates', $vName == 'messagetemplates'
        );
        JHtmlSidebar::addEntry(
                JText::_('COM_ISBNREGISTRY_SUBMENU_MESSAGE_TYPES'), 'index.php?option=com_isbnregistry&view=messagetypes', $vName == 'messagetypes'
        );
        JHtmlSidebar::addEntry(
                JText::_('COM_ISBNREGISTRY_SUBMENU_ISBN_RANGES'), 'index.php?option=com_isbnregistry&view=isbnranges', $vName == 'isbnranges'
        );
        JHtmlSidebar::addEntry(
                JText::_('COM_ISBNREGISTRY_SUBMENU_ISMN_RANGES'), 'index.php?option=com_isbnregistry&view=ismnranges', $vName == 'ismnranges'
        );		
    }

    /**
     * Creates an for generating a CSV file. Adds required headers and
     * publishers data.
     * @param array $publishers publishers to be added to the CSV file
     * @return array headers and publishes array
     */
    public static function toCSVArray($publishers) {
        // Get component parameters
        $params = JComponentHelper::getParams('com_isbnregistry');
        // Get the id of the publisher that represents author publishers
        $authorPublisherId = $params->get('author_publisher_id_isbn', 0);
        // Array for results
        $list = array();
        // CSV headers
        $headers = self::getPIIDHeaders();
        // Add headers
        array_push($list, $headers);
        // Loop through the publishers
        foreach ($publishers as $publisher) {
            array_push($list, self::publisherToArray($publisher, $authorPublisherId));
        }
        // Return results
        return $list;
    }

    /**
     * Returns an array that contains the headers needed for publishers
     * and publications file for the International ISBN Directory (PIID).
     * @return array PIID headers array
     */
    public static function getPIIDHeaders() {
        return array(
            'Registrant_Status_Code',
            'Registrant_Prefix_Type',
            'Registrant_Prefix_Or_ISBN',
            'Registrant_Name',
            'ISO_ Country_Code',
            'Address_Line_1',
            'Address_Line_2',
            'Address_Line_3',
            'Address_Line_4',
            'Admin_Contact_Name',
            'Admin_Phone',
            'Admin_Fax',
            'Admin_Email',
            'Alternate_Contact_Type',
            'Alternate_Contact_Name',
            'Alternate_Phone',
            'Alternate_Fax',
            'Alternate_Email',
            'SAN',
            'GLN',
            'Website_URL',
            'Registrant_ID',
            'ISNI'
        );
    }

    private static function publisherToArray($publisher, $authorPublisherId) {
        $publisherArr = array(
            $publisher->has_quitted ? 'I' : 'A',
            $publisher->publisher_id == $authorPublisherId ? 'A' : 'P',
            $publisher->publisher_identifier,
            $publisher->official_name,
            'FI',
            $publisher->address,
            $publisher->zip . ' ' . $publisher->city,
            '',
            '',
            $publisher->contact_person,
            // Add on space after phone that Excel considers it as a string
            $publisher->phone . ' ',
            '',
            $publisher->email,
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            $publisher->www,
            '',
            ''
        );
        return $publisherArr;
    }

}
