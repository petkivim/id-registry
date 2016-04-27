<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * ISSN Canceled Table class
 *
 * @since  1.0.0
 */
class IssnRegistryTableIssncanceled extends JTable {

    /**
     * Constructor
     *
     * @param   JDatabaseDriver  &$db  A database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__issn_registry_issn_canceled', 'id', $db);
    }

    /**
     * Stores a canceled ISSN.
     *
     * @param   boolean  $updateNulls  True to update fields even if they are null.
     *
     * @return  boolean  True on success, false on failure.
     *
     * @since   1.6
     */
    public function store($updateNulls = false) {
        // Transform the params field
        if (isset($this->params) && is_array($this->params)) {
            $registry = new Registry;
            $registry->loadArray($this->params);
            $this->params = (string) $registry;
        }

        if (!$this->id) {
            // Get date and user
            $date = JFactory::getDate();
            $user = JFactory::getUser();
            // New item
            $this->canceled_by = $user->get('username');
            $this->canceled = $date->toSql();
        }

        return parent::store($updateNulls);
    }

    /**
     * Adds new issn canceled to the database.
     * @param array $params array that contains the parameters
     * @return int id of the new issn canceled entry
     */
    public function addNew($params) {
        // Set values
        $this->issn = $params['issn'];
        $this->issn_range_id = $params['issn_range_id'];
        // Add object to db
        if (!$this->store()) {
            return 0;
        }
        return $this->id;
    }

    /**
     * Delete the ISSN identified by the ISSN range id and ISSN.
     * @param int $rangeId ISSN range id
     * @param string $issn ISSN to be deleted
     * @return boolean true on success, false on failure
     */
    public function deleteIssn($rangeId, $issn) {
        $query = $this->_db->getQuery(true);

        // Delete ISSN
        $conditions = array(
            $this->_db->quoteName('issn_range_id') . ' = ' . $this->_db->quote($rangeId),
            $this->_db->quoteName('issn') . ' = ' . $this->_db->quote($issn)
        );

        $query->delete($this->_db->quoteName($this->_tbl));
        $query->where($conditions);

        $this->_db->setQuery($query);
        // Execute query
        $result = $this->_db->execute();
        // If affected rows returns 1, operation succeeded
        if ($this->_db->getAffectedRows() == 1) {
            return true;
        }
        // Otherwise return false
        return false;
    }

    /**
     * Returns the smallest canceled ISSN object (fifo) that was given from the 
     * range identified by the given id. If id is not defined, the smallest
     * issn from any range is returned.
     * @param int $rangeId ISSN range id
     * @return string smallest ISSN that was given from the ISSN range identified
     * by the given id
     */
    public function getIssn($rangeId = 0) {
        // Get query
        $query = $this->_db->getQuery(true);

        // Create the query
        $query->select('*')->from($this->_db->quoteName($this->_tbl));
        if ($rangeId > 0) {
            $query->where($this->_db->quoteName('issn_range_id') . ' = ' . $this->_db->quote($rangeId));
        }
        $query->order('issn ASC')->setLimit('1');
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadObject();
    }

}
