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
        // Initialize variables.
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        // Create the base select statement.
        $query->select('*')
                ->from($db->quoteName('#__isbn_registry_message'));
        // If publisher id is not null, add where clause and
        // show only publications that belong to the given publisher
        if ($publisherId != null) {
            $query->where($db->quoteName('publisher_id') . ' = ' . $db->quote($publisherId));
        }
        $query->order('sent DESC');

        return $query;
    }

}
