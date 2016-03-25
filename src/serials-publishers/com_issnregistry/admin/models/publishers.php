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
 * Publishers Model
 *
 * @since  1.0.0
 */
class IssnregistryModelPublishers extends JModelList {

    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'lang_code', 'a.lang_code'
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

        $langCode = $this->getUserStateFromRequest($this->context . '.filter.lang_code', 'filter_lang_code', '');
        $this->setState('filter.lang_code', $langCode);

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
        // Get language code
        $langCode = $this->getState('filter.lang_code');

        // Create the base select statement.
        $query->select('DISTINCT a.id, a.official_name, a.contact_person')
                ->from($db->quoteName('#__issn_registry_publisher') . ' AS a');

        // Set lang code
        if (!empty($langCode)) {
            $query->where('a.lang_code = ' . $db->quote($langCode));
        }

        // Build search
        if (!empty($search)) {
            $search = $db->quote('%' . str_replace(' ', '%', trim($search) . '%'));
            $query->where('(a.official_name LIKE ' . $search . ' OR a.contact_person LIKE ' . $search . ')');
        }

        // Set order
        $query->order('official_name ASC');

        return $query;
    }

}
