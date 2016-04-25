<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 		Petteri Kivimäki
 * @copyright	Copyright (C) 2015 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Publishers Model
 *
 * @since  1.0.0
 */
class IsbnregistryModelPublishers extends JModelList {

    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'has_quitted', 'a.has_quitted',
                'type', 'a.type',
                'lang_code', 'a.lang_code',
                'no_identifier', 'a.no_identifier',
                'target_field', 'a.target_field'
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

        $state = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string');
        $this->setState('filter.state', $state);

        $hasQuitted = $this->getUserStateFromRequest($this->context . '.filter.has_quitted', 'filter_has_quitted', '');
        $this->setState('filter.has_quitted', $hasQuitted);

        $type = $this->getUserStateFromRequest($this->context . '.filter.type', 'filter_type', '');
        $this->setState('filter.type', $type);

        $langCode = $this->getUserStateFromRequest($this->context . '.filter.lang_code', 'filter_lang_code', '');
        $this->setState('filter.lang_code', $langCode);

        $noIdentifier = $this->getUserStateFromRequest($this->context . '.filter.no_identifier', 'filter_no_identifier', '');
        $this->setState('filter.no_identifier', $noIdentifier);

        $targetField = $this->getUserStateFromRequest($this->context . '.filter.target_field', 'filter_target_field', '');
        $this->setState('filter.target_field', $targetField);

        // List state information.
        parent::populateState('a.official_name', 'asc');
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

        // Get search string        
        $search = $this->getState('filter.search');
        // Get has quitted value
        $hasQuitted = $this->getState('filter.has_quitted');
        // Get type
        $type = $this->getState('filter.type');
        // Get language code
        $langCode = $this->getState('filter.lang_code');
        // Get identifier filter
        $noIdentifier = $this->getState('filter.no_identifier');
        // Get target field
        $targetField = $this->getState('filter.target_field');

        // Create the base select statement.
        $query->select('DISTINCT a.id, a.official_name, a.active_identifier_isbn, a.active_identifier_ismn, a.created')
                ->from($db->quoteName('#__isbn_registry_publisher') . ' AS a');

        // Set has quitted
        if (is_numeric($hasQuitted)) {
            $query->where('a.has_quitted = ' . $hasQuitted);
        }

        // Set lang code
        if (!empty($langCode)) {
            $query->where('a.lang_code = ' . $db->quote($langCode));
        }

        // Set type
        if (!empty($type)) {
            if (preg_match('/^ISBN$/', $type) === 1) {
                $query->join('INNER', '#__isbn_registry_publisher_isbn_range AS i ON a.id = i.publisher_id');
            } else if (preg_match('/^ISMN$/', $type) === 1) {
                $query->join('INNER', '#__isbn_registry_publisher_ismn_range AS i ON a.id = i.publisher_id');
            }
        } else {
            $query->join('LEFT', '#__isbn_registry_publisher_isbn_range AS isbn ON a.id = isbn.publisher_id');
            $query->join('LEFT', '#__isbn_registry_publisher_ismn_range AS ismn ON a.id = ismn.publisher_id');
        }

        // Set identifier filter
        if (is_numeric($noIdentifier) && empty($type)) {
            switch ($noIdentifier) {
                case 1:
                    $query->where('(isbn.publisher_identifier is null AND ismn.publisher_identifier is null)');
                    break;
                case 2:
                    $query->where('a.active_identifier_isbn = ""');
                    break;
                case 3:
                    $query->where('a.active_identifier_ismn = ""');
                    break;
                case 4:
                    $query->where('(a.active_identifier_isbn != "" AND a.active_identifier_ismn != "")');
                    break;
                case 5:
                    $query->where('(isbn.publisher_identifier != "" OR ismn.publisher_identifier != "")');
                    break;
                case 6:
                    $query->where('a.active_identifier_isbn != ""');
                    break;
                case 7:
                    $query->where('a.active_identifier_ismn != ""');
                    break;
            }
        }

        // Build search
        if (!empty($search)) {
            // If search string contains only [0-9-Mm], search from identifier field only
            if (preg_match('/^[\d\-Mm]+$/', $search) === 1) {
                $search = $db->quote('%' . trim($search) . '%');
                // If type is not defined, search ISBN and ISMN identifiers
                if (empty($type)) {
                    $query->where('(isbn.publisher_identifier LIKE ' . $search . ' OR ismn.publisher_identifier LIKE ' . $search . ')');
                } else {
                    $query->where('i.publisher_identifier LIKE ' . $search);
                }
            } else {
                $search = $db->quote('%' . str_replace(' ', '%', trim($search) . '%'));
                // Search from additional info field or name fields?
                if (strcmp($targetField, 'additional_info') === 0) {
                    $query->where('a.additional_info LIKE ' . $search);
                } else if (strcmp($targetField, 'contact_person') === 0) {
                    $query->where('a.contact_person LIKE ' . $search);
                } else {
                    $query->where('(a.official_name LIKE ' . $search . ' OR a.other_names LIKE ' . $search . ' OR a.previous_names LIKE ' . $search . ')');
                }
            }
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
