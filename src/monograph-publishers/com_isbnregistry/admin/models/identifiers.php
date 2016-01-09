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
 * Identifiers Model
 *
 * @since  1.0.0
 */
class IsbnregistryModelIdentifiers extends JModelList {

    /**
     * Method to build an SQL query to load the list data.
     *
     * @return      string  An SQL query
     */
    protected function getListQuery() {
        // Get identifier batch id URL parameter
        $batchId = JFactory::getApplication()->input->getInt('batchId');
        // Initialize variables.
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        // Create the base select statement.
        $query->select('*')
                ->from($db->quoteName('#__isbn_registry_identifier'));
        // If batch id is not null, add where clause and
        // show only identifiers that belong to the given batch
        if ($batchId != null) {
            $query->where($db->quoteName('identifier_batch_id') . ' = ' . $db->quote($batchId));
        }
        $query->order('identifier ASC');

        return $query;
    }

}
