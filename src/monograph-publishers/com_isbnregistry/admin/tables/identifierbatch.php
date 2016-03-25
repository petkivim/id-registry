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
 * Identifier batch Table class
 *
 * @since  1.0.0
 */
class IsbnRegistryTableIdentifierbatch extends JTable {

    /**
     * Constructor
     *
     * @param   JDatabaseDriver  &$db  A database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__isbn_registry_identifier_batch', 'id', $db);
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
        if (is_array($this->params)) {
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
     * Adds new identifier batch to the database.
     * @param array $params array that contains the parameters
     * @return int id of the new identifier batch entry
     */
    public function addNew($params) {
        // Set values
        $this->identifier_type = $params['identifier_type'];
        $this->identifier_count = $params['identifier_count'];
        $this->publisher_id = $params['publisher_id'];
        $this->publication_id = $params['publication_id'];
        $this->publisher_identifier_range_id = $params['publisher_identifier_range_id'];
        $this->identifier_canceled_used_count = $params['identifier_canceled_used_count'];

        // Add object to db
        if (!$this->store()) {
            return 0;
        }
        return $this->id;
    }

    /**
     * Updates the publication id of the identifier batch identified by
     * the given id.
     * @param int $identifierBatchId batch identifier id
     * @param int $publicationId publication id
     * @return boolean true on success; otherwise false
     */
    public function updatePublicationId($identifierBatchId, $publicationId) {
        // Conditions for which records should be updated.
        $conditions = array(
            'id' => $identifierBatchId
        );

        // Load object
        if (!$this->load($conditions)) {
            return false;
        }

        // Update publication id
        $this->publication_id = $publicationId;

        // Update object to DB
        return $this->store();
    }

    /**
     * Get all batch ids related to the publisher identified by the given
     * publisher id.
     * @param int $publisherId publisher id
     * @return array array of batch ids
     */
    public function getBatchIdsByPublisher($publisherId) {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Create the query
        $query->select('id')
                ->from($this->_db->quoteName($this->_tbl))
                ->where($this->_db->quoteName('publisher_id') . ' = ' . $this->_db->quote($publisherId));
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadColumn();
    }

    /**
     * Delete all identifier batches related to the publisher identified by
     * the given publisher id.
     * @param int $publisherId publisher id
     * @return int number of deleted rows
     */
    public function deleteByPublisherId($publisherId) {
        $query = $this->_db->getQuery(true);

        // Delete all batches related to the publisher
        $conditions = array(
            $this->_db->quoteName('publisher_id') . ' = ' . $this->_db->quote($publisherId)
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
     * Delete the batch identified by the given batch id
     * @param int $batchId batch id
     * @return boolean true on success, false on failure
     */
    public function deleteBatch($batchId) {
        $query = $this->_db->getQuery(true);

        // Delete the batch
        $conditions = array(
            $this->_db->quoteName('id') . ' = ' . $this->_db->quote($batchId)
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
     * Get the identfier type of the batch identified by the given
     * identifier batch id.
     * @param int $identifierBatchId identifier batch id
     * @return string identifier type: ISBN or ISMN
     */
    public function getIdentifierType($identifierBatchId) {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Create the query
        $query->select('identifier_type')
                ->from($this->_db->quoteName($this->_tbl))
                ->where($this->_db->quoteName('id') . ' = ' . $this->_db->quote($identifierBatchId));
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadResult();
    }

    /**
     * Returns an identifier batch mathcing the given id.
     * @param int $identifierBatchId id of the identifier batch
     * @return Identifierbatch identifier batch matching the given id
     */
    public function getIdentifierBatch($identifierBatchId) {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Create the query
        $query->select('*')
                ->from($this->_db->quoteName($this->_tbl))
                ->where($this->_db->quoteName('id') . ' = ' . $this->_db->quote($identifierBatchId));
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadObject();
    }

    /**
     * Returns the id of the last batch from the publisher identifier range
     * identified by the given id.
     * @param int $publisherIdentifierRangeId publisher identifier range id
     * @return int id of the last batch from the publisher identifier range
     * identified by the given id
     */
    public function getLast($publisherIdentifierRangeId) {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Create the query
        $query->select('id')
                ->from($this->_db->quoteName($this->_tbl))
                ->where($this->_db->quoteName('publisher_identifier_range_id') . ' = ' . $this->_db->quote($publisherIdentifierRangeId))
                ->order('id DESC')
                ->setLimit('1');
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadResult();
    }

    /**
     * Increase identifier batch canceled count by one.
     * @param int $identifierBatchId id of the identifier batch
     * @param int $count current count
     * @return boolean true on success, false on failure
     */
    public function increaseCanceledCount($identifierBatchId, $count) {
        $conditions = array(
            'id' => $identifierBatchId,
            'identifier_canceled_count' => $count
        );

        // Load object
        if (!$this->load($conditions)) {
            return false;
        }

        // Update publication id
        $this->identifier_canceled_count = $this->identifier_canceled_count + 1;

        // Update object to DB
        return $this->store();
    }

    /**
     * Increase identifier batch deleted count by one.
     * @param int $identifierBatchId id of the identifier batch
     * @param int $count current count
     * @return boolean true on success, false on failure
     */
    public function increaseDeletedCount($identifierBatchId, $count) {
        $conditions = array(
            'id' => $identifierBatchId,
            'identifier_deleted_count' => $count
        );

        // Load object
        if (!$this->load($conditions)) {
            return false;
        }

        // Update deleted count
        $this->identifier_deleted_count = $this->identifier_deleted_count + 1;

        // Update object to DB
        return $this->store();
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
