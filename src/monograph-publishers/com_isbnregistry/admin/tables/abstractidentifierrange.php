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
 * Abstract Identifier Range Table class
 *
 * @since  1.0.0
 */
abstract class IsbnRegistryTableAbstractIdentifierRange extends JTable {

    /**
     * Constructor
     *
     * @param   String Name of the table
     * @param   JDatabaseDriver  &$db  A database connector object
     */
    function __construct($tableName, &$db) {
        parent::__construct($tableName, 'id', $db);
    }

    /**
     * Stores an Indetifier Range.
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

        // Get date and user
        $date = JFactory::getDate();
        $user = JFactory::getUser();

        if ($this->id) {
            // Existing item
            $this->modified_by = $user->get('username');
            $this->modified = $date->toSql();
        } else {
            // New item
            $this->created_by = $user->get('username');
            $this->created = $date->toSql();
            $this->category = strlen($this->range_begin);
            $this->free = $this->range_end - $this->range_begin + 1;
            $this->next = $this->range_begin;
        }

        return parent::store($updateNulls);
    }

    /**
     * Deletes an ISBN Range.
     *
     * @param   integer  $pk  Primary key of the ISBN range to be deleted.
     *
     * @return  boolean  True on success, false on failure.
     *
     */
    public function delete($pk = null) {
        // Item can be deleted only if no ISBNs have been used yet
        if (strcmp($this->range_begin, $this->next) != 0) {
            // If ISBNs have been used, raise a warning
            JFactory::getApplication()->enqueueMessage(JText::_('COM_ISBNREGISTRY_IDENTIFIER_RANGES_DELETE_FAILED'), 'warning');
            // Return false as the item can't be deleted
            return false;
        }
        // No ISBNs have been used, delete the item
        return parent::delete($pk);
    }

    /**
     * Returns the identifier range identified by the given id. The range must
     * be marked as active.
     * @param integer $rangeId id of the range to be fetched
     * @param boolean $mustBeActive must range be active
     * @return object identifier range object on success; null on failure
     */
    public function getRange($rangeId, $mustBeActive) {
        $conditions = array(
            $this->_db->quoteName('id') . " = " . $this->_db->quote($rangeId)
        );
        if ($mustBeActive) {
            array_push($conditions, $this->_db->quoteName('is_active') . " = " . $this->_db->quote(true));
            array_push($conditions, $this->_db->quoteName('is_closed') . " = " . $this->_db->quote(false));
        }
        // Database query
        $query = $this->_db->getQuery(true);
        $query->select('*');
        $query->from($this->_db->quoteName($this->_tbl));
        $query->where($conditions);
        $this->_db->setQuery((string) $query);

        return $this->_db->loadObject();
    }

    /**
     * Updates the given identifier range to the database. This method must
     * be used when the number of used identifiers is being increased.
     * @param Object $range object to be updated
     * @return boolean true on success
     */
    public function updateIncrease($range) {
        // Conditions for which records should be updated.
        $conditions = array(
            $this->_db->quoteName('id') . ' = ' . $this->_db->quote($range->id),
            $this->_db->quoteName('free') . ' = ' . $this->_db->quote(($range->free + 1)),
            $this->_db->quoteName('taken') . ' = ' . $this->_db->quote(($range->taken - 1))
        );
        return $this->updateRange($range, $conditions);
    }

    /**
     * Updates the given identifier range to the database. This method must
     * be used when the number of used identifiers is being decreased.
     * @param Object $range object to be updated
     * @return boolean true on success
     */
    public function updateDecrease($range) {
        // Conditions for which records should be updated.
        $conditions = array(
            $this->_db->quoteName('id') . ' = ' . $this->_db->quote($range->id),
            $this->_db->quoteName('free') . ' = ' . $this->_db->quote(($range->free - 1)),
            $this->_db->quoteName('taken') . ' = ' . $this->_db->quote(($range->taken + 1))
        );
        return $this->updateRange($range, $conditions);
    }

    /**
     * Updates the given identifier range to the database.
     * @param Object $range object to be updated
     * @param array $conditions conditions for the update operation
     * @return boolean true on success
     */
    protected function updateRange($range, $conditions) {
        $query = $this->_db->getQuery(true);

        // Fields to update.
        $fields = array(
            $this->_db->quoteName('free') . ' = ' . $this->_db->quote($range->free),
            $this->_db->quoteName('taken') . ' = ' . $this->_db->quote($range->taken),
            $this->_db->quoteName('next') . ' = ' . $this->_db->quote($range->next),
            $this->_db->quoteName('is_active') . ' = ' . $this->_db->quote($range->is_active),
            $this->_db->quoteName('is_closed') . ' = ' . $this->_db->quote($range->is_closed)
        );

        // Set update query
        $query->update($this->_db->quoteName($this->_tbl))->set($fields)->where($conditions);
        $this->_db->setQuery($query);

        // Execute query
        $result = $this->_db->execute();

        // If operation succeeded, one row was affected
        if ($this->_db->getAffectedRows() == 1) {
            return true;
        }
        return false;
    }

    /**
     * Delete all identifier ranges related to the publisher identified by
     * the given publisher id.
     * @param int $publisherId publisher id
     * @return int number of deleted rows
     */
    public function deleteByPublisherId($publisherId) {
        $query = $this->_db->getQuery(true);

        // Delete all identifier ranges related to the publisher
        $conditions = array(
            $this->_db->quoteName('publisher_id') . ' = ' . $this->_db->quote($publisherId)
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
