<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
 * @author 		Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Message types Model
 *
 * @since  1.0.0
 */
class IssnregistryModelForms extends JModelList {

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

        // Get search string        
        $search = $this->getState('filter.search');
        // Get has quitted value
        $status = $this->getState('filter.status');

        // Create the base select statement.
        $query->select('*')->from($db->quoteName('#__issn_registry_form') . ' as a');

        // Check status value
        if (is_numeric($status)) {
            switch ($status) {
                case 1:
                    $query->where('a.status = "NOT_HANDLED"');
                    break;
                case 2:
                    $query->where('a.status = "NOT_NOTIFIED"');
                    break;
                case 3:
                    $query->where('a.status = "COMPLETED"');
                    break;
                case 4:
                    $query->where('a.status = "REJECTED"');
                    break;
            }
        }

        // Build search
        if (!empty($search)) {
			if (preg_match('/^[\d]+$/', $search) === 1) {
				$query->where($db->quoteName('a.id') . ' = ' . $db->quote($search));
			} else {
				$search = $db->quote('%' . str_replace(' ', '%', trim($search) . '%'));
				$query->where('(a.publisher LIKE ' . $search . ' OR a.contact_person LIKE ' . $search . ')');
			}
        }

        $query->order('a.created DESC');

        return $query;
    }

}
