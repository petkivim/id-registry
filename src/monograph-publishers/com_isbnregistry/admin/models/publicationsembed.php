<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * PublicationsEmbed Model
 *
 * @since  1.0.0
 */
class IsbnregistryModelPublicationsEmbed extends JModelList {

    /**
     * Method to build an SQL query to load the list data.
     *
     * @return      string  An SQL query
     */
    protected function getListQuery() {
        // Initialize variables.
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        // Get publisher id URL parameter
        $publisherId = JFactory::getApplication()->input->getInt('publisherId');

        // Create the base select statement.
        $query->select('*')
                ->from($db->quoteName('#__isbn_registry_publication'));

        // If publisher id is not null, the request comes from embed
        // layout and no other filtering is needed
        if ($publisherId != null) {
            $query->where($db->quoteName('publisher_id') . ' = ' . $db->quote($publisherId));
        }
        // Sort by created
        $query->order('created DESC');

        return $query;
    }

}
