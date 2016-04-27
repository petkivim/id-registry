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
 * ISSN Used Table class
 *
 * @since  1.0.0
 */
class IssnRegistryTableIssnused extends JTable {

    /**
     * Constructor
     *
     * @param   JDatabaseDriver  &$db  A database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__issn_registry_issn_used', 'id', $db);
    }

    /**
     * Stores a publication.
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
            $this->created_by = $user->get('username');
            $this->created = $date->toSql();
        }

        return parent::store($updateNulls);
    }

    /**
     * Adds new ISSN Used object to the database.
     * @param array $params array that contains the parameters
     * @return int id of the new issn used entry
     */
    public function addNew($params) {
        // Set values
        $this->issn = $params['issn'];
        $this->publication_id = $params['publication_id'];
        $this->issn_range_id = $params['issn_range_id'];
        // Add object to db
        if (!$this->store()) {
            return 0;
        }
        return $this->id;
    }

    /**
     * Delete ISSN identified by the given publication id.
     * @param int $publicationId publication id
     * @return int number of deleted rows
     */
    public function deleteByPublicationId($publicationId) {
        $query = $this->_db->getQuery(true);

        // Delete all batches related to the publisher
        $conditions = array(
            $this->_db->quoteName('publication_id') . ' = ' . $this->_db->quote($publicationId)
        );

        $query->delete($this->_db->quoteName($this->_tbl));
        $query->where($conditions);

        $this->_db->setQuery($query);
        // Execute query
        $result = $this->_db->execute();
        // Return the number of deleted batches
        return $this->_db->getAffectedRows();
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
     * Returns the row identified by the given ISSN number.
     * @param string $issn ISSN to be searched
     * @return object ISSN used object mathing the given ISSN or null
     */
    public function findByIssn($issn) {
        // Database connection
        $query = $this->_db->getQuery(true);
        // Create query
        $query->select('*');
        $query->from($this->_db->quoteName($this->_tbl));
        $query->where($this->_db->quoteName('issn') . ' = ' . $this->_db->quote($issn));
        $this->_db->setQuery($query);
        // Return result
        return $this->_db->loadObject();
    }

    /**
     * Returns the last ISSN that was given from the range identified by the 
     * given id. 
     * @param int $rangeId ISSN range id
     * @return string last ISSN that was given from the ISSN range identified
     * by the given id
     */
    public function getLast($rangeId) {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Create the query
        $query->select('issn')
                ->from($this->_db->quoteName($this->_tbl))
                ->where($this->_db->quoteName('issn_range_id') . ' = ' . $this->_db->quote($rangeId))
                ->order('issn DESC')
                ->setLimit('1');
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadResult();
    }

}
