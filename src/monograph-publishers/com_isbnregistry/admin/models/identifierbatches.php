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
 * Identifier batches Model
 *
 * @since  1.0.0
 */
class IsbnregistryModelIdentifierbatches extends JModelList {

    /**
     * Method to build an SQL query to load the list data.
     *
     * @return      string  An SQL query
     */
    protected function getListQuery() {
        // Get publisher id URL parameter
        $publisherId = JFactory::getApplication()->input->getInt('publisherId');
        // Get type URL parameter
        $type = JFactory::getApplication()->input->get('type', null, 'string');
        // Initialize variables.
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        // Create the base select statement.
        $query->select('*')
                ->from($db->quoteName('#__isbn_registry_identifier_batch'));
        // If publisher id is not null, add where clause and
        // show only batches that belong to the given publisher. In addition,
        // also type must be defined
        if ($publisherId != null && $type != null) {
            $conditions = array(
                $this->_db->quoteName('publisher_id') . ' = ' . $this->_db->quote($publisherId),
                $this->_db->quoteName('identifier_type') . ' = ' . $this->_db->quote(strtoupper($type))
            );
            $query->where($conditions);
        }
        $query->order('created DESC');

        return $query;
    }

}
