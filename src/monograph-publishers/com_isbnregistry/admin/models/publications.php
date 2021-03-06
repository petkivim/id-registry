<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 		Petteri Kivim�ki
 * @copyright	Copyright (C) 2015 Petteri Kivim�ki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Publications Model
 *
 * @since  1.0.0
 */
class IsbnregistryModelPublications extends JModelList {

    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'status', 'a.status'
            );
        }

        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * This method should only be called once per instantiation and is designed
     * to be called on the first call to the getState() method unless the model
     * configuration flag to ignore the request is set.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @param   string  $ordering   An optional ordering field.
     * @param   string  $direction  An optional direction (asc|desc).
     *
     * @return  void
     *
     * @since   1.6
     */
    protected function populateState($ordering = null, $direction = null) {
        // Load the filter state.
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $status = $this->getUserStateFromRequest($this->context . '.filter.status', 'filter_status', '');
        $this->setState('filter.status', $status);

        // List state information.
        parent::populateState('a.created', 'desc');
    }

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

        // If publisher id is not null, the request comes from embed
        // layout and no other filtering is needed
        if ($publisherId != null) {
            // Create the base select statement.
            $query->select('*')
                    ->from($db->quoteName('#__isbn_registry_publication'));
            $query->where($db->quoteName('publisher_id') . ' = ' . $db->quote($publisherId));
            $query->order('created DESC');

            return $query;
        }
        // Get search string        
        $search = $this->getState('filter.search');
        // Get has quitted value
        $status = $this->getState('filter.status');

        // Create the base select statement.
        $query->select('DISTINCT a.id, a.title, a.official_name, a.comments, a.created')
                ->from($db->quoteName('#__isbn_registry_publication') . ' AS a');

        // Check status value
        if (is_numeric($status)) {
            switch ($status) {
                case 1:
                    $query->where('(a.publication_identifier_print = "" AND a.publication_identifier_electronical = "")');
                    $query->where('a.on_process = false');
                    $query->where('a.no_identifier_granted = false');
                    break;
                case 2:
                    $query->where('(a.publication_identifier_print = "" AND a.publication_identifier_electronical = "")');
                    $query->where('a.on_process = true');
                    $query->where('a.no_identifier_granted = false');
                    break;
                case 3:
                    $query->where('(a.publication_identifier_print != "" OR a.publication_identifier_electronical != "")');
                    $query->where('a.no_identifier_granted = false');
                    break;
                case 4:
                    $query->where('a.no_identifier_granted = true');
                    break;
            }
        }

        // Build search
        if (!empty($search)) {
            $search = $db->quote('%' . str_replace(' ', '%', trim($search) . '%'));
            $query->where('(a.title LIKE ' . $search . ' OR a.comments LIKE ' . $search . ' OR a.official_name LIKE ' . $search . ' OR a.contact_person LIKE ' . $search . ')');
        }
        // Set sort order
        $query->order('a.created DESC');
        
        return $query;
    }

}
