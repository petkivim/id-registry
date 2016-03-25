<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 		Petteri Kivimäki
 * @copyright	Copyright (C) 2015 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Identifier Table class
 *
 * @since  1.0.0
 */
class IsbnRegistryTableIdentifier extends JTable {

    /**
     * Constructor
     *
     * @param   JDatabaseDriver  &$db  A database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__isbn_registry_identifier', 'id', $db);
    }

    /**
     * Stores an identifier.
     *
     * @param   boolean  $updateNulls  True to update fields even if they are null.
     *
     * @return  boolean  True on success, false on failure.
     *
     * @since   1.6
     */
    public function store($updateNulls = false) {
        // Transform the params field
        if (is_array($this->params)) {
            $registry = new Registry;
            $registry->loadArray($this->params);
            $this->params = (string) $registry;
        }

        return parent::store($updateNulls);
    }

    /**
     * Adds new identifiers to the database.
     * @param array $identifiers array that contains the identifiers to be added
     * @param int $identifierBatchId batch id of the identifiers
     * @return bool true on success; otherwise false
     */
    public function addNew($identifiers, $identifierBatchId) {
        // Create new query object.
        $query = $this->_db->getQuery(true);

        // Insert columns.
        $columns = array('identifier', 'identifier_batch_id', 'publisher_identifier_range_id', 'publication_type');

        // Prepare the insert query.
        $query->insert($this->_db->quoteName($this->_tbl))->columns($this->_db->quoteName($columns));

        // Loop through identifiers
        foreach ($identifiers as $identifier) {
            $query->values(array("'" . $identifier['identifier'] . "', " . $identifierBatchId . ", " . $identifier['publisher_identifier_range_id'] . ", '" . $identifier['publication_type'] . "'"));
        }

        // Set the query 
        $this->_db->setQuery($query);
        $this->_db->execute();
        // Return false if number of affected rows is zero
        if ($this->_db->getAffectedRows() == 0) {
            return false;
        }
        return true;
    }

    /**
     * Returns identifiers with the given batch id.
     * @param int $identifierBatchId
     * @param boolean $orderByIdentifier when true the results are sorted by
     * identfier in desceinding order, by default the value is false which
     * means that the results are sorted by id in ascending order
     * @return list of identifier objects
     */
    public function getIdentifiers($identifierBatchId, $orderByIdentifier = false) {
        // Create new query object.
        $query = $this->_db->getQuery(true);

        // Conditions for which records should be fetched
        $conditions = array(
            $this->_db->quoteName('identifier_batch_id') . ' = ' . $this->_db->quote($identifierBatchId)
        );

        // Create the query
        $query->select('*')
                ->from($this->_db->quoteName($this->_tbl))
                ->where($conditions)
                ->order(($orderByIdentifier ? 'identifier DESC' : 'id ASC'));
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadObjectList();
    }

    /**
     * Delete all identifiers related to the batch identified by
     * the given batch id.
     * @param int $batchId batch id
     * @return int number of deleted rows
     */
    public function deleteByBatchId($batchId) {
        $query = $this->_db->getQuery(true);

        // Delete all identifiers related to the batch
        $conditions = array(
            $this->_db->quoteName('identifier_batch_id') . ' = ' . $this->_db->quote($batchId)
        );

        $query->delete($this->_db->quoteName($this->_tbl));
        $query->where($conditions);

        $this->_db->setQuery($query);
        // Execute query
        $result = $this->_db->execute();
        // Return the number of deleted rows
        return $this->_db->getAffectedRows();
    }

    /**
     * Return the identifier object matching the given identifier string.
     * @param string $identifier identifier string
     * @return object identifier object
     */
    public function getIdentifier($identifier) {
        // Database connection
        $query = $this->_db->getQuery(true);
        // Create query
        $query->select('i.id, i.identifier, i.identifier_batch_id, ib.identifier_type, ib.identifier_count, ib.identifier_canceled_count, ib.identifier_canceled_used_count, ib.identifier_deleted_count, ib.publication_id, ib.publisher_id, i.publisher_identifier_range_id');
        $query->from($this->_db->quoteName($this->_tbl) . ' AS i');
        $query->join('INNER', '#__isbn_registry_identifier_batch AS ib ON ib.id = i.identifier_batch_id');
        $query->where($this->_db->quoteName('i.identifier') . ' = ' . $this->_db->quote($identifier));
        $this->_db->setQuery($query);
        // Return result
        return $this->_db->loadObject();
    }

    /**
     * Starts a transaction.
     */
    public function transactionStart() {
        $this->_db->transactionStart();
    }

    /**
     * Commits a transaction.
     */
    public function transactionCommit() {
        $this->_db->transactionCommit();
    }

    /**
     * Transaction rollback.
     */
    public function transactionRollback() {
        $this->_db->transactionRollback();
    }

}
