<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Publications Model
 *
 * @since  1.0.0
 */
class IssnregistryModelPublications extends JModelList {

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
        parent::populateState('a.title', 'asc');
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
        // Get form id URL parameter
        $formId = JFactory::getApplication()->input->getInt('formId');

        // If publisher id is not null, the request comes from embed
        // layout and no other filtering is needed
        if ($publisherId != null) {
            // Create the base select statement.
            $query->select('*')
                    ->from($db->quoteName('#__issn_registry_publication'));
            $query->where($db->quoteName('publisher_id') . ' = ' . $db->quote($publisherId));
            $query->where('status != "NO_ISSN_GRANTED"');
            $query->order('title ASC');

            return $query;
        } else if ($formId != null) {
            // Create the base select statement.
            $query->select('*')
                    ->from($db->quoteName('#__issn_registry_publication'));
            $query->where($db->quoteName('form_id') . ' = ' . $db->quote($formId));
            $query->order('id DESC');

            return $query;
        }
        // Get search string        
        $search = $this->getState('filter.search');
        // Get has quitted value
        $status = $this->getState('filter.status');

        // Create the base select statement.
        $query->select('a.*, p.official_name')->from($db->quoteName('#__issn_registry_publication') . ' AS a');
        $query->join('LEFT', '#__issn_registry_publisher AS p ON p.id = a.publisher_id');
        $query->join('INNER', '#__issn_registry_form AS f ON f.id = a.form_id');

        // Check status value
        if (is_numeric($status)) {
            switch ($status) {
                case 1:
                    $query->where('a.status = "NO_PREPUBLICATION_RECORD"');
                    break;
                case 2:
                    $query->where('a.status = "ISSN_FROZEN"');
                    break;
                case 3:
                    $query->where('a.status = "WAITING_FOR_CONTROL_COPY"');
                    break;
                case 4:
                    $query->where('a.status = "COMPLETED"');
                    break;
                case 5:
                    $query->where('a.status = "NO_ISSN_GRANTED"');
                    break;
            }
        }

        // Build search
        if (!empty($search)) {
            $search = $db->quote('%' . str_replace(' ', '%', trim($search) . '%'));
            $query->where('(a.title LIKE ' . $search . ' OR a.issn LIKE ' . $search . ' OR a.additional_info LIKE ' . $search . ' OR p.official_name LIKE ' . $search . ' OR p.contact_person LIKE ' . $search . ' OR f.publisher LIKE ' . $search . ' OR f.contact_person LIKE ' . $search . ')');
        }

        $query->order('a.created DESC');

        return $query;
    }

}
