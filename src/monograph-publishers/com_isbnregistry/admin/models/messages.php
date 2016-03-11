<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 		Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Messages Model
 *
 * @since  1.0.0
 */
class IsbnregistryModelMessages extends JModelList {

    /**
     * Method to build an SQL query to load the list data.
     *
     * @return      string  An SQL query
     */
    protected function getListQuery() {
        // Get publisher id URL parameter
        $publisherId = JFactory::getApplication()->input->getInt('publisherId');
        // Get group message id URL parameter
        $groupMessageId = JFactory::getApplication()->input->getInt('groupMessageId');		
        // Initialize variables.
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        // Create the base select statement.
        $query->select('msg.*, pub.title')
                ->from($db->quoteName('#__isbn_registry_message') . ' AS msg');
        // Left join to publication
        $query->join('LEFT', '#__isbn_registry_publication AS pub ON pub.id = msg.publication_id');
        
        // If publisher id is not null, add where clause and
        // show only publications that belong to the given publisher
        if ($publisherId != null) {
            $query->where($db->quoteName('msg.publisher_id') . ' = ' . $db->quote($publisherId));
        } else if ($groupMessageId != null) {
            $query->where($db->quoteName('msg.group_message_id') . ' = ' . $db->quote($groupMessageId ));
        }
        $query->order('sent DESC');

        return $query;
    }

}
