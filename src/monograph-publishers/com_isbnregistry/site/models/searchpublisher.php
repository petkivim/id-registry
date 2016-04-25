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
 * Search Publisher Model
 *
 * @since  1.0.0
 */
class IsbnregistryModelSearchPublisher extends JModelList {

    /**
     * Method to build an SQL query to load the list data.
     *
     * @return      string  An SQL query
     */
    protected function getListQuery() {
        // Initialize variables.
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        // Get the post variables
        $post = JFactory::getApplication()->input->get;

        // Get search string        
        $search = $post->get('searchStr', '', 'string');
        // Get has quitted value
        //$hasQuitted = $post->get('hasQuitted', 0, 'integer');
        // Get type
        $type = $post->get('type', '', 'string');

        // Create the base select statement.
        $query->select('DISTINCT a.id, a.official_name, a.active_identifier_isbn, a.active_identifier_ismn, a.created')
                ->from($db->quoteName('#__isbn_registry_publisher') . ' AS a');

        // Set has quitted
        /*if (is_numeric($hasQuitted)) {
            $query->where('a.has_quitted = ' . $hasQuitted);
        }*/

        // Set type
        if (!empty($type)) {
            if (preg_match('/^ISBN$/', $type) === 1) {
                $query->join('INNER', '#__isbn_registry_publisher_isbn_range AS i ON a.id = i.publisher_id');
            } else if (preg_match('/^ISMN$/', $type) === 1) {
                $query->join('INNER', '#__isbn_registry_publisher_ismn_range AS i ON a.id = i.publisher_id');
            }
        }

        // Build search
        if (!empty($search)) {
            // If search string contains only [0-9-Mm], search from identifier field only
            if (preg_match('/^[\d\-Mm]+$/', $search) === 1) {
                $search = $db->quote('%' . trim($search) . '%');
                // If type is not defined, search ISBN and ISMN identifiers
                if (empty($type)) {
                    $query->join('LEFT', '#__isbn_registry_publisher_isbn_range AS isbn ON a.id = isbn.publisher_id');
                    $query->join('LEFT', '#__isbn_registry_publisher_ismn_range AS ismn ON a.id = ismn.publisher_id');
                    $query->where('(isbn.publisher_identifier LIKE ' . $search . ' OR ismn.publisher_identifier LIKE ' . $search . ')');
                } else {
                    $query->where('i.publisher_identifier LIKE ' . $search);
                }
            } else {
                $search = $db->quote('%' . str_replace(' ', '%', trim($search) . '%'));
                $query->where('(a.official_name LIKE ' . $search . ' OR a.other_names LIKE ' . $search . ' OR a.previous_names LIKE ' . $search . ')');
                $query->join('LEFT', '#__isbn_registry_publisher_isbn_range AS isbn ON a.id = isbn.publisher_id');
                $query->join('LEFT', '#__isbn_registry_publisher_ismn_range AS ismn ON a.id = ismn.publisher_id');
                $query->where('(isbn.publisher_identifier != \'\' OR ismn.publisher_identifier != \'\')');
            }
        } else {
            $query->join('LEFT', '#__isbn_registry_publisher_isbn_range AS isbn ON a.id = isbn.publisher_id');
            $query->join('LEFT', '#__isbn_registry_publisher_ismn_range AS ismn ON a.id = ismn.publisher_id');
            $query->where('(isbn.publisher_identifier != \'\' OR ismn.publisher_identifier != \'\')');
        }
        // Set group by. This is needed for pagination, because pagination
        // seems to ignore DISTINCT on query and returns wrong total number
        // of rows. This causes wrong number of result pages on search results.
        $query->group('a.id');
        // Set order
        $query->order('official_name ASC');

        return $query;
    }

}
